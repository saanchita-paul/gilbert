<?php

namespace ExternalLead\Services;

use App\Models\ExternalSource;
use App\Models\ConnectionApplication;
use App\Models\Hazard;
use App\Services\Address\StreetTypeMapper;
use App\Services\AddressMapperService;
use App\Events\NotifyAgentAfterLeadCreation;
use App\Events\Agency\CreateApplicationEvent;
use App\Services\Utility\StateMapService;

class CreateAppService
{
    private AddressMapperService $addressMapperService;

    private $hazardData = [];

    public function __construct()
    {
        $this->addressMapperService = new AddressMapperService();
    }

    public function create(ExternalSource $source, array $data): ConnectionApplication
    {
        $defaultOffice = $source->defaultOffice;
        $newApp = new ConnectionApplication();

        $newApp->agency_id = $defaultOffice->agency_id;
        $newApp->office_id = $defaultOffice->id;
        $newApp->external_source_id = $source->id;
        $newApp->source = $source->source_id;

        $newApp = $this->mapConnectionApplicationFields($newApp, $data);
        $newApp->save();

        // Create hazards
        $this->hazardData = $this->mapHazardData($data);
        $newApp->hazards()->sync($this->hazardData);

        $mapAgentService = new MapAgentService();
        $newApp = $mapAgentService->map($source, $newApp, $data ?? []);

        $identificationService = new CreateIdentificationService();
        $identificationService->save($newApp, $data);

        $connectionService = new CreateConnectionService();
        $connectionService->save($newApp, $data);

        $authPersonService = new CreateAuthorizedPersonService();
        $authPersonService->save($newApp, $data);

        $this->dispatchAfterCreate($newApp);

        return $newApp;
    }

    private function mapPhoneType(?string $type): ?int
    {
        return match ($type) {
            'mobile' => 1,
            'homephone' => 2,
            'international_mobile' => 3,
            default => null
        };
    }

    private function mapPhoneField(?string $type): ?string
    {
        return match ($type) {
            'mobile' => 'phone',
            'homephone' => 'homephone',
            'international_mobile' => 'international_phone',
            default => null
        };
    }

    private function mapConnectionApplicationFields(ConnectionApplication $app, array $data)
    {
        $phoneType = $this->mapPhoneType(($data['primary_account']['phone_type'] ?? null));
        $phoneField = $this->mapPhoneField(($data['primary_account']['phone_type'] ?? null));

        $app->status = ConnectionApplication::STATUS_UNASSIGNED;
        $app->title = ucfirst(($data['primary_account']['title'] ?? null));
        $app->first_name = $data['primary_account']['first_name'] ?? null;
        $app->middle_name = $data['primary_account']['middle_name'] ?? null;
        $app->last_name = $data['primary_account']['last_name'] ?? null;
        $app->dob = $data['primary_account']['dob'] ?? null;
        $app->phone_type = $phoneType;
        $app->{$phoneField} = $data['primary_account']['phone_number'] ?? null;

        $app->email = $data['primary_account']['email'] ?? null;

        $app->tenancy_type = ConnectionApplication::TENANCY_MAPPING[($data['connection_details']['tenancy_type'] ?? null)] ?? null;
        $app->moving_date = $data['connection_details']['moving_date'] ?? null;
        $app->additional_instruction = $data['connection_details']['additional_instruction'] ?? null;
        $app->is_email_billing = $data['connection_details']['is_email_billing'] ?? false;
        $app->property_type = ConnectionApplication::PROPERTY_TYPE_MAPPING[($data['connection_details']['property_type'] ?? null)] ?? null;
        $app->is_power_life_support = $data['connection_details']['has_power_life_support'] ?? false;
        $app->is_gas_life_support = $data['connection_details']['has_gas_life_support'] ?? false;
        $app->has_solar = $data['connection_details']['has_solar'] ?? false;
        // $app->is_renovation_on = $data['connection_details']['is_renovation_on'] ?? false;
        $app->nmi = $data['connection_details']['nmi'] ?? null;
        $app->mirn = $data['connection_details']['mirn'] ?? null;

        $app->unit_number = $data['property_address']['unit_number'] ?? null;
        $app->street_number = $data['property_address']['street_number'] ?? null;
        $app->street_name = $data['property_address']['street_name'] ?? null;
        $app->street_name_only = $data['property_address']['street_name'] ?? null;
        $app->street_type = StreetTypeMapper::getShortForm(($data['property_address']['street_type'] ?? null));
        $app->city = $data['property_address']['suburb'] ?? ($data['property_address']['city'] ?? null);
        $app->postcode = $data['property_address']['postcode'] ?? null;
        $app->state = !empty($data['property_address']['state']) ?
            $this->addressMapperService->mapState(strtoupper($data['property_address']['state'])) : null;
        $app->country = $this->addressMapperService->mapCountry(($data['property_address']['country'] ?? null));
        $app->street_address = ($app->unit_number ? $app->unit_number . ' / ' : '') . $app->street_number . ' ' . $app->street_name . ' ' . $app->street_type;
        $app->address_text = $app->street_address . ', ' . $app->city . ' ' . $app->state . ' ' . $app->postcode;

        if (empty($data['billing_address'])) {
            $app->is_billing_same = true;
        } else {
            $app->billing_street_type = $data['billing_address']['street_type'] ?? null;
            $app->billing_unit_number = $data['billing_address']['unit_number'] ?? null;
            $app->billing_street_number = $data['billing_address']['street_number'] ?? null;
            $app->billing_street_name = $data['billing_address']['street_name'] ?? null;
            $app->billing_street_name_only = $data['billing_address']['street_name'] ?? null;
            $app->billing_city = $data['billing_address']['suburb'] ?? ($data['billing_address']['city'] ?? null);
            $app->billing_state = !empty($data['billing_address']['state']) ?
                $this->addressMapperService->mapState(strtoupper($data['billing_address']['state'])) : null;
            $app->billing_postcode = $data['billing_address']['postcode'] ?? null;
            $app->billing_street_address = ($app->billing_unit_number ? $app->billing_unit_number . ' / ' : '') . $app->billing_street_number . ' ' . $app->billing_street_name . ' ' . $app->billing_street_type;
            $app->billing_address_text = $app->billing_street_address . ', ' . $app->billing_city . ' ' . $app->billing_state . ' ' . $app->billing_postcode;
        }

        return $app;
    }

    private function dispatchAfterCreate(ConnectionApplication $app)
    {
        NotifyAgentAfterLeadCreation::dispatch($app->id);
        CreateApplicationEvent::dispatch($app->id);
    }

    /**
     * Map hazard data
     * @param $data
     * @return array
     */
    private function mapHazardData($data): array
    {
        $hazardData = [];

        if (isset($data['connection_details']['is_renovation_on']) && $data['connection_details']['is_renovation_on']) {
            $haz = Hazard::where('is_active', 1)
                ->where('powershop_value', "electrical_safety_issue")->first();
            if ($haz) {
                $hazardData[] = $haz->id;
            }
        }

        return $hazardData;
    }
}
