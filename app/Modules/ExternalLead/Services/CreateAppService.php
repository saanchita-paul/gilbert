<?php

namespace ExternalLead\Services;

use App\Models\ExternalSource;
use App\Models\ConnectionApplication;
use App\Services\AddressMapperService;
use App\Events\NotifyAgentAfterLeadCreation;
use App\Events\Agency\CreateApplicationEvent;

class CreateAppService
{
    public function create(ExternalSource $source, array $data): ConnectionApplication
    {
        $defaultOffice = $source->defaultOffice;
        $newApp = new ConnectionApplication();

        $newApp->agency_id = $defaultOffice->agency_id;
        $newApp->office_id = $defaultOffice->id;
        $newApp->external_source_id = $source->id;

        $newApp = $this->mapConnectionApplicationFields($newApp, $data);
        $newApp->save();

        $mapAgentService = new MapAgentService();
        $newApp = $mapAgentService->map($source, $newApp, $data['agent_email'] ?? '');

        $identificationService = new CreateIdentificationService();
        $identificationService->save($newApp, $data);

        $connectionService = new CreateConnectionService();
        $connectionService->save($newApp, $data);

        $authPersonService = new CreateAuthorizedPersonService();
        $authPersonService->save($newApp, $data);

        $this->dispatchAfterCreate($newApp);

        return $newApp;
    }

    private function mapConnectionApplicationFields(ConnectionApplication $app, array $data)
    {
        $mapperService = new TAppMapper();
        $addressService = new AddressMapperService();

        $app->title = $data['tenancy_title'] ?
            ucfirst($data['tenancy_title']) : null;
        $app->first_name = $data['tenancy_first_name'] ?? null;
        $app->middle_name = $data['tenancy_middle_name'] ?? null;
        $app->last_name = $data['tenancy_last_name'] ?? null;
        $app->dob = $data['tenancy_dob'] ?? null;
        $app->phone_type = $data['tenancy_phone_type'] ?
            $mapperService->mapPhoneType($data['tenancy_phone_type']) : null;
        $app->phone = $mapperService->mapPhone(
            $data['tenancy_phone_type'],
            $data['tenancy_phone_number']
        ) ?? null;
        $app->international_phone = $mapperService->mapInternationalPhone(
            $data['tenancy_phone_type'],
            $data['tenancy_phone_number']
        ) ?? null;
        $app->homephone = $data['tenancy_homephone'] ?? null;
        $app->email = $data['tenancy_email'] ?? null;
        $app->tenancy_type = $data['tenancy_type'] ?
            $mapperService->mapTenancy($data['tenancy_type']) : null;
        $app->moving_date = $data['tenancy_moving_date'] ?? null;
        $app->additional_instruction = $data['additional_instruction'] ?? null;

        $app->city = $data['tenancy_suburb'] ?? null;
        $app->postcode = $data['tenancy_postcode'] ?? null;
        $app->state = $data['tenancy_state'] ?
            $addressService->mapState($data['tenancy_state']) : null;
        $app->country = $data['tenancy_country'] ?
            $addressService->mapCountry($data['tenancy_country']) : null;
        // $app->address_text = $data[tenancy_address_text ?? null;
        $app->is_email_billing = $data['is_email_billing'] ?
            $mapperService->mapYesNoToBool($data['is_email_billing']) : null;
        $app->property_type = $data['tenancy_property_type'] ?
            $mapperService->mapPropertyType($data['tenancy_property_type']) : null;
        $app->has_life_support = $data['tenancy_has_life_support'] ?
            $mapperService->mapYesNoToBool($data['tenancy_has_life_support']) : null;
        $app->has_solar = $data['tenancy_has_solar'] ?
            $mapperService->mapYesNoToBool($data['tenancy_has_solar']) : null;


        $app->nmi = $data['tenancy_nmi'] ?? null;
        $app->mirn = $data['tenancy_mirn'] ?? null;
        $app->unit_number = $data['tenancy_unit_number'] ?? null;
        $app->street_number = $data['tenancy_street_number'] ?? null;
        $app->street_name = $data['tenancy_street_name'] ?? null;
        $app->street_name_only = $data['tenancy_street_name'] ?? null;
        $app->street_type = $data['tenancy_street_type'] ?? null;
        $app->address_text = $data['tenancy_address_text'] ?? null;
        $app->billing_street_type = $data['tenancy_billing_street_type'] ?? null;
        $app->billing_unit_number = $data['tenancy_billing_unit_number'] ?? null;
        $app->billing_street_number = $data['tenancy_billing_street_number'] ?? null;
        $app->billing_street_name = $data['tenancy_billing_street_name'] ?? null;
        $app->billing_street_name_only = $data['tenancy_billing_street_name'] ?? null;
        $app->billing_address_text = $data['tenancy_billing_address_text'] ?? null;
        $app->street_address = $data['tenancy_street_address'] ?? null;
        $app->billing_city = $data['tenancy_billing_city'] ?? null;
        $app->billing_street_address = $data['tenancy_billing_street_address'] ?? null;
        $app->billing_state = $data['tenancy_billing_state'] ?
            $addressService->mapState($data['tenancy_billing_state']) : null;
        $app->billing_postcode = $data['tenancy_billing_postcode'] ?? null;
        $app->is_renovation_on = $data['tenancy_is_renovation_on'] ?
            $mapperService->mapYesNoToBool($data['tenancy_is_renovation_on']) : null;
        $app->status = ConnectionApplication::STATUS_UNASSIGNED;
        return $app;
    }

    private function dispatchAfterCreate(ConnectionApplication $app)
    {
        NotifyAgentAfterLeadCreation::dispatch($app->id);
        CreateApplicationEvent::dispatch($app->id);
    }
}
