<?php

namespace App\Modules\Reporting\Services;

use App\Services\TimeZoneService;
use Carbon\Carbon;

trait SetDateRage
{
    private function setDateRange(string $start, string $end)
    {
        $this->timezone = TimeZoneService::getTimeZoneInt();

        $this->startDate = Carbon::parse($start, tz: $this->timezone)->setTimezone(0)->toDateTimeString();
        $this->endDate = Carbon::parse($end, tz: $this->timezone)
            ->addHours(23)
            ->addMinutes(59)
            ->addSeconds(59)
            ->setTimezone(config('app.timezone'))->toDateTimeString();
    }

    private function setDateRangeNoTz(string $start, string $end)
    {

        $this->startDate = Carbon::parse($start)->toDateTimeString();
        $this->endDate = Carbon::parse($end)
            ->addHours(23)
            ->addMinutes(59)
            ->addSeconds(59)
            ->toDateTimeString();
    }
}
