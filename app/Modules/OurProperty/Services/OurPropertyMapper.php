<?php


namespace App\Modules\OurProperty\Services;


use App\Models\Identification;

class OurPropertyMapper
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

    public function mapYesNoToBool($isEmailBilling)
    {
        if($isEmailBilling === 'yes') {
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
        switch ($type) {
            case 'medicare':
                return Identification::TYPE_MEDICARE;
            case 'passport':
                return Identification::TYPE_PASSPORT;
            case 'driver_license':
                return Identification::TYPE_DRIVING_LICENCE;
            default:
                return null;

        }
    }



}
