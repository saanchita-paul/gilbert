<?php

namespace Powershop\Services;

use App\Models\ConnectionService;

class PromotionCodeService
{
    public static function getCode(string $state, string $service_type) : string {
        // TODO: get code from chatbot api
        $code = '';

        if ($state == 'Victoria' && $service_type == ConnectionService::TYPE_ELECTRICITY) {
            $code = 'hoodswitchsaver100';
        }
        
        if ($state == 'Victoria' && $service_type == ConnectionService::TYPE_GAS) {
            $code = 'HoodPS100%CarbonNeutral';
        }

        if ($state == 'New South Wales' && in_array($service_type, [ConnectionService::TYPE_ELECTRICITY, ConnectionService::TYPE_GAS])){
            $code = 'HoodPS100%CarbonNeutral';
        }

        if (in_array($state, ['Queensland', 'South Australia']) && $service_type == ConnectionService::TYPE_ELECTRICITY){
            $code = 'HoodPS100%CarbonNeutral';            
        }

        return $code;
    }
}
