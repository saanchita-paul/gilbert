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
        $phoneType = strtolower($phoneType);
        return match ($phoneType) {
            'mobile' => 1,
            'international',
            'international mobile',
            'international mobile number',
            'international phone number' => 3,
            default => 2,
        };
    }

    public function mapPhone($phoneType, $phoneNumber)
    {
        $phoneType = strtolower($phoneType);
        return match ($phoneType) {
            'mobile' => $phoneNumber,
            default => null,
        };
    }

    public function mapInternationalPhone($phoneType, $phoneNumber)
    {
        $phoneType = strtolower($phoneType);
        return match ($phoneType) {
            'international mobile',
            'international mobile number',
            'international phone number' => $phoneNumber,
            default => null,
        };
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
