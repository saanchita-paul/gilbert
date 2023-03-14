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
        $identification->type = !empty($data['primary_account']['identification']['type'])
            ? $mapperService->mapIdType($data['primary_account']['identification']['type'])
            : null;
        $identification->card_number = $data['primary_account']['identification']['number'] ?? null;
        $identification->state = !empty($data['primary_account']['identification']['state'])
            ? $addressService->mapState($data['primary_account']['identification']['state'])
            : null;
        $identification->country = !empty($data['primary_account']['identification']['country'])
            ? $addressService->mapCountry($data['primary_account']['identification']['country'])
            : null;
        $identification->card_color = !empty($data['primary_account']['identification']['medicare_card_color'])
            ? strtoupper($data['primary_account']['identification']['medicare_card_color'])
            : null;
        $identification->special_number = $data['primary_account']['identification']['medicare_reference_number'] ?? null;
        $identification->expire_date = $data['primary_account']['identification']['expire_date'] ?? null;

        $identification->save();

        return $identification;
    }
}
