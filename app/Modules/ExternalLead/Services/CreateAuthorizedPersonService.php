<?php

namespace ExternalLead\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC as AuthorisedPerson;
use App\Services\AddressMapperService;

class CreateAuthorizedPersonService
{
    public function save(ConnectionApplication $app, array $data)
    {
        $mapperService = new TAppMapper();
        $addressService = new AddressMapperService();

        $authorisedPerson = new AuthorisedPerson();
        $authorisedPerson->connection_application_id = $app->id;
        $authorisedPerson->title = $data['tenancy_secondary_title'] ?
            ucwords($data['tenancy_secondary_title']) : null;
        $authorisedPerson->first_name = $data['tenancy_secondary_first_name'] ?? null;
        $authorisedPerson->last_name = $data['tenancy_secondary_last_name'] ?? null;
        $authorisedPerson->middle_name = $data['tenancy_secondary_middle_name'] ?? null;
        $authorisedPerson->email = $data['tenancy_secondary_email'] ?? null;
        $authorisedPerson->phone = $data['tenancy_secondary_phone_number'] ?? null;
        $authorisedPerson->role = $data['tenancy_secondary_permission_type'] ?
            AuthorisedPerson::ROLE_TYPE_MAPPER[$data['tenancy_secondary_permission_type']] : null;
        $authorisedPerson->dob = $data['tenancy_secondary_dob'] ?? null;

        $authorisedPerson->identification_type = $data['secondary_tenancy_identification_type'] ?
            $mapperService->mapIdType($data['secondary_tenancy_identification_type']) : null;
        $authorisedPerson->card_number = $data['secondary_tenancy_identification_number'] ?? null;
        $authorisedPerson->state = $data['secondary_tenancy_identification_state'] ?
            $addressService->mapState($data['secondary_tenancy_identification_state']) : null;
        $authorisedPerson->country = $data['secondary_tenancy_identification_country'] ?
            $addressService->mapCountry($data['secondary_tenancy_identification_country']) : null;
        $authorisedPerson->card_color = $data['secondary_tenancy_medicare_card_color'] ?
            strtoupper($data['secondary_tenancy_medicare_card_color']) : null;
        $authorisedPerson->special_number = $data['secondary_tenancy_medicare_reference_number'] ?? null;
        $authorisedPerson->expire_date = $data['secondary_tenancy_identification_expire_date'] ?? null;

        $authorisedPerson->save();

        return $authorisedPerson;
    }
}
