<?php

namespace App\Services\Agency;

use App\Models\APILog;
use App\Models\Identification;
use Illuminate\Support\Carbon;
use App\Models\ConnectionService;
use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\HubspotHistory;
use Illuminate\Database\Eloquent\Model;
use App\Services\Logger\ErrorLogService;
use Illuminate\Database\Eloquent\Collection;
use PropertyMe\Services\SaveContacts;

class HubspotContactService
{
    const STATUS_NEW = 'NEW';
    const STATUS_BAD_TIMING = 'BAD_TIMING';
    const STATUS_OPEN_DEAL = 'OPEN_DEAL';
    const STATUS_CONNECTED = 'CONNECTED';
    const STATUS_UNQUALIFIED = 'UNQUALIFIED';
    const STATUS_IN_PROGRESS = 'IN_PROGRESS';

    private array|Collection|ConnectionApplication|Model $application;

    public function __construct(int $id)
    {
        $this->application = ConnectionApplication::findOrFail($id);
        $this->application->load(['identification', 'connectionServices']);
    }

    /**
     * creating new contact
     *
     * @throws \Exception
     */
    public function create()
    {
        $url = config('hub_spot.create_contact') . config('hub_spot.api_key');
        $url = APILog::setLoggerQuery($url, APILog::API_HB_CREATE_CONTACT);

        $response = Http::post($url, [
            "properties" => $this->getProperties()
        ]);
        $body = json_decode($response->body(), true);

//        if (empty($body['vid'])) {
//            Log::error($response->body());
//            throw new \Exception("[HubspotContactService] failed to create contact");
//        }

        $this->application->update(['hubspot_contact_id' => $body['vid']]);

        self::saveHistoricalData($body);

        return $body;
    }

    /**
     * updating contact
     *
     * @throws \Exception
     */
    public function update()
    {
        if ($this->application->is_skip_hubspot) {
            throw new \exception(sprintf('HubspotContactService:update SKIP - Application ID %s is flagged skip due to not being latest hubspot details', strval($this->application->id)));
        }
        $vid = $this->application->hubspot_contact_id;

        if (empty($vid)) {
            throw new \exception('HubspotContactService:update FAIL - Application is missing hubspot contact id.');
        }

        $url = str_replace('${id}', $vid, config('hub_spot.update_contact')) . config('hub_spot.api_key');
        $url = APILog::setLoggerQuery($url, APILog::API_HB_UPDATE_CONTACT);

        $response = Http::post($url, [
            "properties" => $this->getProperties()
        ]);

        if (!$response->successful()) {
            Log::info('[HubspotContactService]: response body');
            Log::info($response->body());
            throw new \Exception('[HubspotContactService] Contact update failed, check api_logs for details.');
        }

        $body = json_decode($response->body(), true);
        if (empty($body)) {
            try {
                $getContactEmail = self::getContactByEmail($this->application->email); 
                if ($getContactEmail['exists'])
                    $body = $getContactEmail['body'];
            } catch (\Exception $e) {
                Log::error('HubspotContactService:getContactByEmail - FAIL (skipping get hubspot response for updated application)', ['error' => $e->getMessage()]);
            }
        }
        self::saveHistoricalData($body);

        return $body;
    }

    /**
     * getting vid
     *
     * @throws \Exception
     */
    public function getContactByEmail($email)
    {
        $url = str_replace('${email}', $email, config('hub_spot.get_contact_by_email')) . config('hub_spot.api_key');
        $url = APILog::setLoggerQuery($url, APILog::API_HB_GET_CONTACT_BY_EMAIL);

        $response = Http::get($url);
        $exists = !($response->status() === 404);

        return [
            'exists' => $exists,
            'body' => json_decode($response->body(), true)
        ];
    }

    private function getProperties(): array
    {
        return [
            [
                "property" => "hood_id",
                "value" => $this->application->id
            ],
            [
                "property" => "phone",
                "value" => $this->application->phone
            ],
            [
                "property" => "lifecyclestage",
                "value" => 'salesqualifiedlead'
            ],
            [
                "property" => "hood_office_id",
                "value" => $this->application->office_id
            ],
            [
                "property" => "hood_agency_id",
                "value" => $this->application->agency_id
            ],
            [
                "property" => "hood_created_by",
                "value" => $this->application->created_by
            ],
            [
                "property" => "hood_assigned_to",
                "value" => $this->application->assigned_to
            ],
            [
                "property" => "hood_title",
                "value" => $this->application->title
            ],
            [
                "property" => "firstname",
                "value" => $this->application->first_name
            ],
            [
                "property" => "lastname",
                "value" => $this->application->last_name
            ],
            [
                "property" => "email",
                "value" => $this->application->email
            ],
            [
                "property" => "hood_phone",
                "value" => $this->application->phone
            ],
            [
                "property" => "hood_tenancy_type",
                "value" => $this->application->tenancy_type
            ],
            [
                "property" => "hood_dob",
                "value" => $this->application->dob
            ],
            [
                "property" => "connection_date",
                "value" => $this->getTimestamp($this->application->moving_date)
            ],
            [
                "property" => "hood_address_unit",
                "value" => $this->application->address_unit
            ],
            [
                "property" => "hood_street_address",
                "value" => $this->application->street_address
            ],
            [
                "property" => "hood_city",
                "value" => $this->application->city
            ],
            [
                "property" => "hood_postcode",
                "value" => $this->application->postcode
            ],
            [
                "property" => "hood_state",
                "value" => $this->application->state
            ],
            [
                "property" => "hood_country",
                "value" => $this->application->country
            ],
            [
                "property" => "hood_additional_instruction",
                "value" => $this->application->additional_instruction
            ],
            [
                "property" => "hood_address_text",
                "value" => $this->application->address_text
            ],
            [
                "property" => "hood_reason",
                "value" => $this->application->reason
            ],
            [
                "property" => "hood_is_email_billing",
                "value" => $this->application->is_email_billing
            ],
            [
                "property" => "hood_property_type",
                "value" => $this->application->property_type
            ],
            [
                "property" => "hood_has_life_support",
                "value" => $this->application->has_life_support
            ],
            [
                "property" => "hood_has_solar",
                "value" => $this->application->has_solar
            ],
            [
                "property" => "hood_nmi",
                "value" => $this->application->nmi
            ],
            [
                "property" => "hood_mirn",
                "value" => $this->application->mirn
            ],
            [
                "property" => "hood_supplier",
                "value" => $this->application->supplier
            ],
            [
                "property" => "hood_plan_type",
                "value" => $this->application->plan_type
            ],
            [
                "property" => "hood_status",
                "value" => $this->application->status
            ],
            [
                "property" => "hood_created_at",
                "value" => $this->application->created_at
            ],
            [
                "property" => "hood_updated_at",
                "value" => $this->application->updated_at
            ],
            [
                "property" => "hood_unit_number",
                "value" => $this->application->unit_number
            ],
            [
                "property" => "hood_street_number",
                "value" => $this->application->street_number
            ],
            [
                "property" => "hood_ea_sales_id",
                "value" => $this->application->ea_sales_id
            ],
            [
                "property" => "hood_identity_type",
                "value" => $this->getIdentityType($this->application->identification?->type),
            ],
            [
                "property" => "hood_medicare_expire_date",
                "value" => $this->formatDate(Identification::TYPE_MEDICARE, $this->application->identification?->expire_date),
            ],
            [
                "property" => "hood_medicare_special_number",
                "value" => $this->application->identification?->special_number ?? '',
            ],
            [
                "property" => "hood_medicare_card_color",
                "value" => $this->application->identification?->card_color ?? 'null',
            ],
            [
                "property" => "hood_medicare_number",
                "value" => $this->checkIDType(Identification::TYPE_MEDICARE, $this->application->identification?->card_number) ?? '',
            ],
            [
                "property" => "hood_driving_licence_expire_date",
                "value" => $this->formatDate(Identification::TYPE_DRIVING_LICENCE, $this->application->identification?->expire_date),
            ],
            [
                "property" => "hood_driving_licence_state",
                "value" => $this->checkIDType(Identification::TYPE_DRIVING_LICENCE, $this->application->identification?->state) ?? '',
            ],
            [
                "property" => "hood_driving_licence_number",
                "value" => $this->checkIDType(Identification::TYPE_DRIVING_LICENCE, $this->application->identification?->card_number) ?? '',
            ],
            [
                "property" => "hood_passport_expire_date",
                "value" => $this->formatDate(Identification::TYPE_PASSPORT, $this->application->identification?->expire_date),
            ],
            [
                "property" => "hood_passport_number",
                "value" => $this->checkIDType(Identification::TYPE_PASSPORT, $this->application->identification?->card_number) ?? '',
            ],
            [
                "property" => "hood_passport_country",
                "value" => $this->application->identification?->country ?? '',
            ],
            [
                "property" => "hood_services",
                "value" => join(',', $this->application->connectionServices->pluck('service_type')->toArray()),
            ],
            [
                "property" => "hs_lead_status",
                "value" => $this->getStatus(),
            ],
            [
                "property" => "hood_business",
                "value" => $this->getHoodBusiness(),
            ],
            [
                "property" => "hood_lead_source",
                "value" => $this->getLeadSource(),
            ],
            [
                "property" => "hood_real_estate_agency",
                "value" => $this->application->getAgencyName(),
            ],
        ];
    }

    /**
     * @param $type
     * @param string|null $date
     * @return string
     */
    private function formatDate($type, ?string $date): ?string
    {
        $value = $this->checkIDType($type, $date);
        try {
            return $value ? (new Carbon($value)) : null;
        } catch (\Exception $exception) {
            Log::error("[HubspotContactService] Failed parsing expire date for type: $type, value: $value");
            Log::error($exception->getTraceAsString());
            return null;
        }
    }

    /**
     * @param int|null $type
     * @return string
     */
    private function getIdentityType(?int $type): string
    {
        return $type ? Identification::MAP_TYPE[$type] : '';
    }

    /**
     * @param $type
     * @param $value
     * @return mixed|null
     */
    private function checkIDType($type, $value)
    {
        return $this->application->identification?->type === $type ? $value : null;
    }

    /**
     * @return string|null
     */
    private function getStatus(): ?string
    {
        return match ($this->application->status) {
            ConnectionApplication::STATUS_SUBMITTED => $this->getSubmittedStatusFromService(),
            ConnectionApplication::STATUS_UNASSIGNED,
            20,
            ConnectionApplication::STATUS_ASSIGNED => self::STATUS_NEW,
            ConnectionApplication::STATUS_CLOSED => self::STATUS_BAD_TIMING,
            ConnectionApplication::STATUS_ESCALATED,
            ConnectionApplication::STATUS_EA_PROCESSINF => self::STATUS_OPEN_DEAL,
            default => $this->noStatusMatchFailScope()
        };
    }

    /**
     * @return string
     */
    private function getSubmittedStatusFromService(): string
    {
        $serviceStatuses = $this->application->connectionServices?->whereIn('service_type', [
            ConnectionService::TYPE_ELECTRICITY,
            ConnectionService::TYPE_GAS
        ])->pluck('status');

        $status = $serviceStatuses->contains(ConnectionService::STATUS_ACCEPTED)
            ? ConnectionService::STATUS_ACCEPTED
            : ($serviceStatuses->contains(ConnectionService::STATUS_REJECTED) ? ConnectionService::STATUS_REJECTED : $serviceStatuses->first());

        return match ($status) {
            ConnectionService::STATUS_ACCEPTED => self::STATUS_CONNECTED,
            ConnectionService::STATUS_REJECTED => self::STATUS_UNQUALIFIED,
            default => self::STATUS_IN_PROGRESS
        };
    }

    /**
     * @return string|null
     */
    private function noStatusMatchFailScope(): ?string
    {
        Log::error("[Hubspot Service] No status match for application: {$this->application->id}");
        ErrorLogService::send('[Hubspot Service] No status matched for application id: ' . $this->application->id , ['taige.alhadweh@hood.ai']);
        return null;
    }

    private function getHoodBusiness()
    {
        $source = $this->application->source;

        return match ($source) {
            ConnectionApplication::SOURCE_FOXIE => 'Foxie',
            default => 'HOOD'
        };

    }

    private function getLeadSource()
    {
        $agencyName = $this->application?->agency?->id;

        return match ($agencyName) {
           17 => 'HOOD',
            default => 'REA'
        };
    }

    /**
     * @param $date
     * @return int|null
     */
    public function getTimestamp($date): int|null
    {
        return $date ? Carbon::parse($date)->timestamp * 1000 : null;
    }

    /**
     * @param contact_id
     * @param oldApplicationId
     * @param hubspot_response
     * 
     * @return int 
     */
    public function saveHistoricalData($hubspot_response = ''){  
        $newHistory = new HubspotHistory();
        $newHistory->contact_id = $this->application->hubspot_contact_id;
        $newHistory->connection_application_id = $this->application->id;
        $newHistory->email = $this->application->email;
        $newHistory->address_as_text = $this->application->address_text ?? '';
        $newHistory->hubspot_response = json_encode($hubspot_response) ?? '';
        $newHistory->save();

        return $newHistory->id;
    }

    public function getOldApplicationData($hubspot_contact_id = '') {
        $query = ConnectionApplication::where('email', $this->application->email)
                                ->where('id', '<>', $this->application->id);
        
        if (!empty($hubspot_contact_id)) 
            $query->where('hubspot_contact_id', $hubspot_contact_id);
        
        return $query->orderBy('id', 'DESC')->first();
    }

    public function setContactId($contact_id) {
        $this->application->update(['hubspot_contact_id' => $contact_id]);
    }

    public function setOldHubspotFlag() {
        $this->application->update(['is_skip_hubspot' => true]);
    }
}
