<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternetServiceInfo extends Model
{
    protected $guarded = ['id'];

    // Modem types
    const BYO = 'byo';
    const STANDARD = 'standard';
    const UPGRADED = 'upgraded';

    // charities
    const OZHARVEST = "oz_harvest";
    const INDIGENOUS_LITERACY_FOUNDATION = "indigenous_literacy_foundation";
    const HEART_KIDS = "heart_kids";
    const NATIONAL_BREAST_CANCER_FOUNDATION = "national_breast_cancer_foundation";
    const ASYLUM_SEEKER_RESOURCE_CENTRE = "asylum_seeker_resource_centre";
    const CHILD_FUND_AUSTRALIA = "child_fund_australia";
    const RAINFOREST_RESCUE = "rainforest_rescue";
    const SLEEPBUS = "sleepbus";
    const BURN_BRIGHT = "burn_bright";
    const SAVE_A_DOG_SCHEME = "save_a_dog_scheme";

    const MODEM_MAPPER = [
        self::BYO => 'BYO Modem',
        self::STANDARD => 'Standard Modem $225 once off',
        self::UPGRADED => 'Upgraded Modem $325 once off',
    ];

    const CHARITY_MAPPER = [
        self::OZHARVEST => 'OzHarvest',
        self::INDIGENOUS_LITERACY_FOUNDATION => 'Indigenous Literacy Foundation',
        self::HEART_KIDS => 'HeartKids',
        self::NATIONAL_BREAST_CANCER_FOUNDATION => 'National Breast Cancer Foundation',
        self::ASYLUM_SEEKER_RESOURCE_CENTRE => 'Asylum Seeker Resource Centre',
        self::CHILD_FUND_AUSTRALIA => 'ChildFund Australia',
        self::RAINFOREST_RESCUE => 'Rainforest Rescue',
        self::SLEEPBUS => 'Sleepbus',
        self::BURN_BRIGHT => 'Burn Bright',
        self::SAVE_A_DOG_SCHEME => 'Save-A-Dog Scheme',
    ];


    public function connectionApplication(): BelongsTo
    {
        return $this->belongsTo(ConnectionApplication::class);
    }

    public function connectionService(): BelongsTo
    {
        return $this->belongsTo(ConnectionService::class);
    }

    public function goodtelPlan(): BelongsTo
    {
        return $this->belongsTo(GoodtelPlan::class);
    }
}
