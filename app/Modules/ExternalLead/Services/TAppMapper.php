<?php


namespace ExternalLead\Services;

use App\Models\Identification;

class TAppMapper
{

    public function mapTenancy($tenancy)
    {
        if($tenancy === 'renter') {
            return 1;
        } else {
            return 2;
        }
    }

    public function mapPhoneType($phoneType)
    {
        if($phoneType === 'mobile') {
            return 1;
        } else {
            return 2;
        }
    }

    public function mapYesNoToBool($isAggree)
    {
        if($isAggree === 'yes') {
            return true;
        } else {
            return false;
        }
    }

    public function mapPropertyType($propertyType)
    {
        if($propertyType === 'residential') {
            return 1;
        } else {
            return 2;
        }

    }

    public function mapIdType($type)
    {
        return match($type) {
            'medicare'=> Identification::TYPE_MEDICARE,
            'passport'=> Identification::TYPE_PASSPORT,
            'driver_license'=> Identification::TYPE_DRIVING_LICENCE,
            default => null
        };
    }



}
