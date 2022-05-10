<?php

namespace Origin\Services;

use App\Models\OriginPlan;

class GetPlans
{
    const MAP_FUEL_TYPE = [
        'electricity' => '01',
        'gas' => '02',
        'water' => '03',
    ];

    const MAP_CUSTOMER_TYPE = [
        'resident' => '0001',
        'business' => '0002',
    ];

    /**
     * @return array
     */
    public static function getActivePlans($fuel_type = null, $customer_type = null){
        $query = OriginPlan::where('status', 'ACTIVE');
        $filtered = [];

        if(!empty($fuel_type) && in_array($fuel_type, array_keys(self::MAP_FUEL_TYPE))){
            $query->where('division_id', self::MAP_FUEL_TYPE[$fuel_type]);
            $filtered['fuel_type'] = $fuel_type;
        }

        if(!empty($customer_type) && in_array($customer_type, array_keys(self::MAP_CUSTOMER_TYPE))){
            $query->where('customer_type_id', self::MAP_CUSTOMER_TYPE[$customer_type]);
            $filtered['customer_type'] = $customer_type;
        }

        $returnData = [
            'isFiltered' => !empty($filtered),
            'filteredBy' => $filtered,
            'plans' => $query->get()->toArray(),
        ];
        
        return $returnData;
    }
}
