<?php

namespace App\Services\PowerShop;

use App\Models\ConnectionApplication;
use Carbon\Carbon;


class SameDayConnectionService
{
    private int $applicationId;
    private string $submitType;

    public function __construct(array $data)
    {
//        $this->applicationId = $applicationId;
//        $this->submitType = $submitType;
    }

    const MAP_TIME = [
        "New South Wales" => '1 PM',
        "Victoria" => '3 PM',
        "Queensland" => '10 AM',
        "South Australia" => '1 PM'
    ];

    const SERVICE_TYPE = ['energy', 'power', 'gas'];

    public function validateSameDayConnection()
    {
        if (!in_array($this->submitType, self::SERVICE_TYPE)) {
            return null;
        }

        return $this->validateElectricity();
    }

    private function validateElectricity()
    {
        $nowTime = Carbon::now()->toTimeString();

        $application = ConnectionApplication::query()
            ->select('id', 'moving_date', 'state')
            ->where('id', $this->applicationId)
            ->first();

        $connectionDate = $application->moving_date;
        $state = $application->state;

        return true;
    }

}
