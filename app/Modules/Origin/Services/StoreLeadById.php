<?php

namespace Origin\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\ConnectionApplication;

class StoreLeadById
{
    /**
     * @var int $applicationId
     * @var array $data
     * 
     * @return array
     * 
     * @throws exception
     */
    public static function run(int $applicationId, array $data){
        $application = ConnectionApplication::find($applicationId);

        if(!$application){
            return false;
        }
    }
}