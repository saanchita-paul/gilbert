<?php

namespace App\Services;

class AddressMapperService
{
    const STATE_NSW = "New South Wales";
    const STATE_VIC = "Victoria";
    const STATE_QLD = "Queensland";
    const STATE_SA = "South Australia";
    const STATE_NT = "Northern Territory";
    const STATE_TAS = "Tasmania";
    const STATE_ACT = "Australian Capital Territory";
    const STATE_WA = "Western Australia";

    const Country_AUS = "Australia";
    
    public function mapState($state)
    {
        return match($state) {
            'NSW' => self::STATE_NSW,
            'VIC' => self::STATE_VIC,
            'QLD' => self::STATE_QLD,
            'SA' => self::STATE_SA,
            'NT' => self::STATE_NT,
            'TAS' => self::STATE_TAS,
            'ACT' => self::STATE_ACT,
            'WA' => self::STATE_WA,
            default => $state
        };
    }

    public function mapCountry($country)
    {
        return match($country) {
            'AUS' => self::Country_AUS,
            default => $country
        };
    }





}
