<?php

namespace ExternalLead\Services;

use App\Models\ConnectionApplication;
use App\Models\ConnectionApplicationSecondaryACC as AuthorisedPerson;
use App\Services\AddressMapperService;

class CreateAuthorizedPersonService
{
    public function save(ConnectionApplication $app, array $data = [])
    {
        if (empty($data['secondary_account'])) {
            return;
        }
        $mapperService = new TAppMapper();
        $addressService = new AddressMapperService();

        $authorisedPerson = new AuthorisedPerson();
        $authorisedPerson->connection_application_id = $app->id;
        $authorisedPerson->title = !empty($data['secondary_account']['title']) ?
            ucwords($data['secondary_account']['title']) : null;
        $authorisedPerson->first_name = $data['secondary_account']['first_name'] ?? null;
        $authorisedPerson->last_name = $data['secondary_account']['last_name'] ?? null;
        $authorisedPerson->middle_name = $data['secondary_account']['middle_name'] ?? null;
        $authorisedPerson->email = $data['secondary_account']['email'] ?? null;
        $authorisedPerson->phone = $data['secondary_account']['phone_number'] ?? null;
        $authorisedPerson->role = !empty($data['secondary_account']['permission_type']) ?
            AuthorisedPerson::ROLE_TYPE_MAPPER[$data['secondary_account']['permission_type']] : null;
        $authorisedPerson->dob = $data['secondary_account']['dob'] ?? null;

        $authorisedPerson->identification_type = !empty($data['secondary_account']['identification']['type']) ?
            $mapperService->mapIdType($data['secondary_account']['identification']['type']) : null;
        $authorisedPerson->card_number = $data['secondary_account']['identification']['number'] ?? null;
        $authorisedPerson->state = !empty($data['secondary_account']['identification']['state']) ?
            $addressService->mapState($data['secondary_account']['identification']['state']) : null;
        $authorisedPerson->country = !empty($data['secondary_account']['identification']['country']) ?
            $addressService->mapCountry($data['secondary_account']['identification']['country']) : null;
        $authorisedPerson->card_color = !empty($data['secondary_account']['identification']['medicare_card_color']) ?
            strtoupper($data['secondary_account']['identification']['medicare_card_color']) : null;
        $authorisedPerson->special_number = $data['secondary_account']['identification']['medicare_reference_number'] ?? null;
        $authorisedPerson->expire_date = $data['secondary_account']['identification']['expire_date'] ?? null;

        $authorisedPerson->save();

        return $authorisedPerson;
    }
}
