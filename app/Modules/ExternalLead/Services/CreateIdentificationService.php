<?php

namespace ExternalLead\Services;

use App\Models\ConnectionApplication;
use App\Models\Identification;
use App\Services\AddressMapperService;

class CreateIdentificationService
{
    public function save(ConnectionApplication $app, array $data)
    {
        $mapperService = new TAppMapper();
        $addressService = new AddressMapperService();

        $identification = new Identification();
        $identification->connection_application_id = $app->id;
        $identification->type = $data['tenancy_identification_type'] ?
            $mapperService->mapIdType($data['tenancy_identification_type']) : null;
        $identification->card_number = $data['tenancy_identification_number'] ?? null;
        $identification->state = $data['tenancy_identification_state'] ?
            $addressService->mapState($data['tenancy_identification_state']) : null;
        $identification->country = $data['tenancy_identification_country'] ?
            $addressService->mapCountry($data['tenancy_identification_country']) : null;
        $identification->card_color = $data['tenancy_medicare_card_color'] ?
            strtoupper($data['tenancy_medicare_card_color']) : null;
        $identification->special_number = $data['tenancy_medicare_reference_number'] ?? null;
        $identification->expire_date = $data['tenancy_indentification_expire_date'] ?? null;
        $identification->save();

        return $identification;
    }
}
