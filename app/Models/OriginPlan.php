<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OriginPlan extends Model
{
    use HasFactory;

    const MAP_FUEL_TYPE = [
        '01' => 'electricity',
        '02' => 'gas',
        '03' => 'water'
    ];

    const MAP_CUSTOMER_TYPE = [
        '0001' => 'resident',
        '0002' => 'business'
    ];

    protected $fillable = ['product_code', 'campaign_id'];

    public function getFuelTypeAttribute(){
        return self::MAP_FUEL_TYPE[$this->division_id];
    }

    public function getCustomerTypeAttribute(){
        return self::MAP_CUSTOMER_TYPE[$this->customer_type_id];
    }
}
