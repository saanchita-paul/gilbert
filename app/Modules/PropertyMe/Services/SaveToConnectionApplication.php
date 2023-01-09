<?php

namespace App\Modules\PropertyMe\Services;

use App\Events\NotifyAgentAfterLeadCreation;
use App\Jobs\CreateHubspotProperty;
use App\Models\AgentProfile;
use App\Models\User;
use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC;
use App\Models\Identification;
use App\Models\Office;
use App\Notifications\ErrorLogNotification;
use App\Services\Address\AddressModel;
use App\Services\Address\StreetTypeMapper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Notification;
use PropertyMe\PropertyMeLead;
use App\Modules\PropertyMe\Services\DobIdentificationService;
use App\Models\ApplicationNote;
use App\Models\ConnectionService;
use App\Services\NotifyBadAgentMailService;

class SaveToConnectionApplication
{

    public function __construct(private Office $office) {}

    public function run(PropertyMeLead $lead, ?array $cleanseAddress = null, ?array $cleanseBillingAddress = null)
    {
        $leadData = json_decode($lead->all_fields_dump, true);

        // $data = "DOB: 01/01/1992 - Harry\nDL: PA1111222 NT - Harry\nDOB: 01/01/1993 - Tonmoy\nDL: 015432362 VIC - Tonmoy";
        $note_data = $this->getIdentificationDetails(
            data_get($leadData, 'Notes'),
            data_get($leadData, 'Id'),
            $this->extractContact($leadData, 'FirstName'),
            $this->extractContact($leadData, 'LastName')
        );

        $movingDate = data_get($lead, 'movingDate');
        unset($lead->movingDate);

        $appAddress = $this->mapAddress($leadData, $cleanseAddress);
        $appBillingAddress = $this->mapBillingAddress($leadData, $cleanseBillingAddress);

        $application = ConnectionApplication::query()->create(array_merge($appAddress, $appBillingAddress, [
            'source' => ConnectionApplication::SOURCE_PROPERTY_ME,
            'office_id' => $this->office->id,
            'agency_id' => $this->office->agency->id,
            'created_by' => $this->getCreatedById($lead),
            'status' => ConnectionApplication::STATUS_UNASSIGNED,

            'moving_date' => $movingDate,

            'first_name' => $this->extractContact($leadData, 'FirstName'),
            'title' => $this->getUserTitle($this->extractContact($leadData, 'Salutation')),
            'last_name' => $this->extractContact($leadData, 'LastName'),
            'email' => $this->extractContact($leadData, 'Email'),
            'phone' => $this->extractContact($leadData, 'CellPhone'),
            'homephone' => $this->extractContact($leadData, 'HomePhone'),
            'dob' => $this->extractNoteData($note_data, 'person.dob'),

            'tenancy_type' => $this->getTenancyType($leadData),
            'property_type' => $this->getTenancyType($leadData),
            'is_email_billing' => $this->getIsEmailBilling($this->extractContact($leadData, 'CommunicationPreferences')),


        ]));

        // auto adding water service to connection application
        if ($application->id) {

            NotifyBadAgentMailService::check(
                $application,
                'PropertyMe',
                $this->office->agency->name ?? '',
                $this->office->name ?? '',
                $lead->agent_email ?? ''
            );

            $connectionService = new ConnectionService();
            $connectionService->service_type = 'water';
            $connectionService->status = ConnectionService::STATUS_EA_PROCESSINF;
            $connectionService->connection_application_id = $application->id;
            $connectionService->save();
        }

        if ($this->extractNoteData($note_data, 'person.identification.type') !== null
            && $this->extractNoteData($note_data, 'person.identification.card_number') !== null
            && ($this->extractNoteData($note_data, 'person.identification.state') !== null
                || $this->extractNoteData($note_data, 'person.identification.country') !== null)
        ) {
            Identification::query()->create([
                'connection_application_id' => $application->id,
                'type' => $this->extractNoteData($note_data, 'person.identification.type'),
                'card_number' => $this->extractNoteData($note_data, 'person.identification.card_number'),
                'state' => $this->extractNoteData($note_data, 'person.identification.state'),
                'country' => $this->extractNoteData($note_data, 'person.identification.country'),
            ]);
        }

        if ($this->extractNoteData($note_data, 'invalid_note_data') !== null)
        {
            ApplicationNote::query()->create([
                'connection_application_id' => $application->id,
                'created_by' => $this->getCreatedByUserId($lead),
                'text' => $this->extractNoteData($note_data, 'invalid_note_data'),
                'type' => 'invalid_property_me_note',
                'title' => 'Invalid PropertyMe Note',
                'user_role' => 'hood_admin'
            ]);
        }

        $contactPerson = $this->leadHasContactPerson($leadData);
        if($contactPerson) {
            ConnectionApplicationSecondaryACC::query()->create([
                'connection_application_id' => $application->id,
                'title' => $this->getUserTitle($this->extractSecondaryContact($contactPerson, 'Salutation')),
                'first_name' => $this->extractSecondaryContact($contactPerson, 'FirstName'),
                'last_name' => $this->extractSecondaryContact($contactPerson, 'LastName'),
                'email' => $this->extractSecondaryContact($contactPerson, 'Email'),
                'phone' => $this->extractSecondaryContact($contactPerson, 'CellPhone'),
                'dob' => $this->extractNoteData($note_data, 'authorised_person.dob'),
            ]);
        }

        $this->saveApplicationId($application->id, $lead);
        NotifyAgentAfterLeadCreation::dispatch($application->id);
        CreateHubspotProperty::dispatch($application->id);

        return $application;
    }

    private function mapAddress($leadData, $address): array
    {
        if ($address) {
            return [
                'unit_number' => data_get($address, 'flatUnitNumber'),
                'street_number' => data_get($address, 'streetNumber'),
                'street_name_only' => data_get($address, 'streetName'),
                'street_type' => StreetTypeMapper::getShortForm(data_get($address, 'streetType')) ,
                'postcode' => data_get($address, 'postcode'),
                'city' => data_get($address, 'locality'),
                'state' => AddressModel::mapStateToLong(data_get($address, 'state')),
                'country' => 'Australia',
                'address_text' => data_get($address, 'fullAddress'),
            ];
        }
        return [
            'unit_number' => $this->extractContact($leadData, 'PhysicalAddress.Unit'),
            'street_number' => $this->extractContact($leadData, 'PhysicalAddress.Number'),
            'street_name' => $this->extractContact($leadData, 'PhysicalAddress.Street'),
            'street_address' => $this->getStreetAddress($leadData),
            'postcode' => $this->extractContact($leadData, 'PhysicalAddress.PostalCode'),
            'city' => $this->extractContact($leadData, 'PhysicalAddress.Suburb'),
            'state' => $this->extractContact($leadData, 'PhysicalAddress.State'),
            'country' => $this->extractContact($leadData, 'PhysicalAddress.Country'),
            'address_text' => $this->extractContact($leadData, 'PhysicalAddress.Text'),
        ];

    }
    private function mapBillingAddress($leadData, $address): array
    {
        if ($address) {
            return [
                'billing_unit_number' => data_get($address, 'flatUnitNumber'),
                'billing_street_number' => data_get($address, 'streetNumber'),
                'billing_street_name_only' => data_get($address, 'streetName'),
                'billing_street_type' => StreetTypeMapper::getShortForm(data_get($address, 'streetType')) ,
                'billing_postcode' => data_get($address, 'postcode'),
                'billing_city' => data_get($address, 'locality'),
                'billing_state' => AddressModel::mapStateToLong(data_get($address, 'state')),
                'billing_address_text' => data_get($address, 'fullAddress'),
            ];
        }
        return [
            'billing_unit_number' => $this->extractContact($leadData, 'PostalAddress.Unit'),
            'billing_street_number' => $this->extractContact($leadData, 'PostalAddress.Number'),
            'billing_street_name_only' => $this->extractContact($leadData, 'PostalAddress.Street'),
            'billing_street_type' => $this->getStreetAddress($leadData),
            'billing_postcode' => $this->extractContact($leadData, 'PostalAddress.PostalCode'),
            'billing_city' => $this->extractContact($leadData, 'PostalAddress.Suburb'),
            'billing_state' => $this->extractContact($leadData, 'PostalAddress.State'),
            'billing_address_text' => $this->extractContact($leadData, 'PostalAddress.Text'),
        ];

    }


    private function getTenancyType($leadData): ?int
    {
        return data_get($leadData, 'IsTenant')
            ? 1
            : (data_get($leadData, 'IsOwner') ? 2 : null);
    }

    private function extractContact($leadData, $key)
    {
        return data_get($leadData, "PrimaryContactPerson.$key");
    }

    private function extractSecondaryContact($leadData, $key)
    {
        return data_get($leadData, $key);
    }

    private function leadHasContactPerson($leadData)
    {
        return collect(data_get($leadData, 'ContactPersons'))->where('IsPrimary', false)->first();
    }

    private function extractNoteData($data, $key)
    {
        return data_get($data, $key);
    }

    private function getIsEmailBilling(?array $preferences): bool
    {
        return $preferences && in_array('ByEmail', $preferences);
    }

    private function getStreetAddress($leadData, $type = 'PhysicalAddress'): ?string
    {
        $name = $this->extractContact($leadData, "$type.Street");
        $number = $this->extractContact($leadData, "PhysicalAddress.Number");
        $unit = $this->extractContact($leadData, "$type.Unit");

        $address = $name;
        $address = $number ? "$number $address" : $address;
        return $number && $unit ? "$unit/$number $name" : $address;
    }

    /**
     * User Title
     *
     * @param string|null $title
     *
     * @return string|null
     */
    private function getUserTitle(?string $title): ?string
    {
        if ($title && in_array(strtolower($title), ConnectionApplication::AVAILABLE_USER_TITLES)) {
            return ucfirst($title);
        }
        return null;
    }

    private function saveApplicationId(int $id, PropertyMeLead $lead)
    {
        $lead->connection_application_id = $id;
        $lead->save();
    }

    public function getIdentificationDetails($data, $leadId, $firstName, $lastName)
    {
        return (new DobIdentificationService($data, $leadId, $firstName, $lastName))->get();
    }


//    /**
//     * @param string $id
//     * @return string|null
//     */
//    private function getMovingDate(string $id): ?string
//    {
//        return collect($this->tenancies)
//            ->filter(fn($value) => data_get($value, 'ContactId') === $id)
//            ->pluck('TenancyStart')
//            ->first();
//    }


    /**
     * @param PropertyMeLead $lead
     * @return int|null
     */
    private function getCreatedById(PropertyMeLead $lead): ?int
    {
        try {
            $agent = AgentProfile::whereHas(
                'user',
                fn(Builder $b) => $b->where('email', $lead->agent_email)
            )->firstOrFail();

            return $agent->id;
        } catch (\Exception $exception) {
            Log::error('Failed to map property me agent', [
                'mgs' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            ]);

            $this->sendErrorNotification($lead->lead_id);

            return null;
        }
    }

    /**
     * @param PropertyMeLead $lead
     * @return int|null
     */
    private function getCreatedByUserId(PropertyMeLead $lead): ?int
    {
        try {
            $user = User::where('email', $lead->agent_email)->firstOrFail();
            return $user->id;
        } catch (\Exception $exception) {
            Log::error('PropertyMe: No Hood Agent exists with the email', [
                'mgs' => $exception->getMessage(),
            ]);
            return null;
        }
    }

    private function sendErrorNotification(string $leadId)
    {
        $mgs = " System is failed to map an agent email when saving PropertyMe lead!"
            . "\n\n"
            . "\n[Properties for debugging]\n"
            . "\nServer url: " . config('app.url')
            . "\nTable: property_me_leads"
            . "\nColumn: lead_id"
            ."\nvalue: <strong>$leadId<strong>";

        $emails = explode(',', config('property_me.support_emails'));

        Notification::route('mail', $emails)->notify(new ErrorLogNotification($mgs, "Failed PropertyMe agent mapping"));
    }
}
