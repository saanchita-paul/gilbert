<?php

namespace App\Modules\PropertyMe\Services;

use App\Jobs\CreateHubspotProperty;
use App\Models\ConnectionApplication;
use App\Models\Office;
use PropertyMe\PropertyMeLead;

class SaveToConnectionApplication
{

    public function __construct(private Office $office)
    {
    }

    public function run(PropertyMeLead $lead)
    {
        $leadData = json_decode($lead->all_fields_dump, true);


        $application = ConnectionApplication::query()->create([
            'source' => ConnectionApplication::SOURCE_PROPERTY_ME,
            'office_id' => $this->office->id,
            'agency_id' => $this->office->agency->id,
            'status' => ConnectionApplication::STATUS_UNASSIGNED,

            'first_name' => $this->extractContact($leadData, 'FirstName'),
            'title' => $this->getUserTitle($this->extractContact($leadData, 'Salutation')),
            'last_name' => $this->extractContact($leadData, 'LastName'),
            'email' => $this->extractContact($leadData, 'Email'),
            'phone' => $this->extractContact($leadData, 'CellPhone'),
            'homephone' => $this->extractContact($leadData, 'HomePhone'),

            'tenancy_type' => $this->getTenancyType($leadData),
            'property_type' => $this->getTenancyType($leadData),
            'is_email_billing' => $this->getIsEmailBilling($this->extractContact($leadData, 'CommunicationPreferences')),

            'unit_number' => $this->extractContact($leadData, 'PhysicalAddress.Unit'),
            'street_number' => $this->extractContact($leadData, 'PhysicalAddress.Number'),
            'street_name' => $this->extractContact($leadData, 'PhysicalAddress.Street'),
            'street_address' => $this->getStreetAddress($leadData),
            'postcode' => $this->extractContact($leadData, 'PhysicalAddress.PostalCode'),
            'city' => $this->extractContact($leadData, 'PhysicalAddress.Suburb'),
            'state' => $this->extractContact($leadData, 'PhysicalAddress.State'),
            'country' => $this->extractContact($leadData, 'PhysicalAddress.Country'),
            'address_text' => $this->extractContact($leadData, 'PhysicalAddress.Text'),

            'billing_unit_number' => $this->extractContact($leadData, 'PostalAddress.Unit'),
            'billing_street_number' => $this->extractContact($leadData, 'PostalAddress.Number'),
            'billing_street_name' => $this->extractContact($leadData, 'PostalAddress.Street'),
            'billing_street_address' => $this->getStreetAddress($leadData, 'PostalAddress'),
            'billing_postcode' => $this->extractContact($leadData, 'PostalAddress.PostalCode'),
            'billing_city' => $this->extractContact($leadData, 'PostalAddress.Suburb'),
            'billing_state' => $this->extractContact($leadData, 'PostalAddress.State'),
            'billing_country' => $this->extractContact($leadData, 'PostalAddress.Country'),
            'billing_address_text' => $this->extractContact($leadData, 'PostalAddress.Text'),

        ]);

        $this->saveApplicationId($application->id, $lead);
        CreateHubspotProperty::dispatch($application->id);
    }


    private function getTenancyType($leadData): ?int
    {
        return data_get($leadData, 'IsTenant')
            ? 1
            : (data_get($leadData, 'IsOwner') ? 2 : null);
    }

    private function extractContact($leadData, $key)
    {
        return data_get($leadData, "PrimaryContactPerson.$key") ?? data_get($leadData, "ContactPersons.0.$key");
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
}
