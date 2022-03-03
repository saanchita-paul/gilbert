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

    public function mapState($type)
    {
        $lowerType = strtolower($type);
        return match($lowerType) {
            'alley'=> 'Ally',
            'arcade'=> 'Arc',
            'avenue'=> 'Ave',
            'boulevard'=> 'Bvd',
            'bypass'=> 'Bypa',
            'circuit'=> 'Cct',
            'close'=> 'Cl',
            'corner'=> 'Crn',
            'court'=> 'Ct',
            'crescent'=> 'Cres',
            'cul-de-sac'=> 'Cds',
            'drive'=> 'Dr',
            'esplanade'=> 'Esp',
            'green'=> 'Grn',
            'grove'=> 'Gr',
            'highway'=> 'Hwy',
            'junction'=> 'Jnc',
            'lane'=> 'Lane',
            'link'=> 'Link',
            'mews'=> 'Mews',
            'parade'=> 'Pde',
            'place'=> 'Pl',
            'ridge'=> 'Rdge',
            'road'=> 'Rd',
            'square'=> 'Sq',
            'street'=> 'St',
            'terrace'=> 'Tce',
            default => $type
        };
    }
}
