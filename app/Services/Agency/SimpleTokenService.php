<?php

namespace App\Services\Agency;

use App\Modules\Reporting\Services\ExportSubmissionReport;

class SimpleTokenService{

    public function __construct()
    {

    }

    public function getAccessToken()
    {
        return 'token';
    }

    public function verifyAccessToken($token)
    {
        $savedToken = 'token';
        if($savedToken === 'token'){
            return true;
        } else {
            return false;
        }
    }

}
