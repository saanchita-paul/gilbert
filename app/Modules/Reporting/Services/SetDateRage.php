<?php

namespace App\Modules\Reporting\Services;

use Carbon\Carbon;

trait SetDateRage
{
    private function setDateRange(string $start, string $end)
    {
        $this->timezone = env("TIME_ZONE", 11) ?? 11;

        $this->startDate = Carbon::parse($start, tz: $this->timezone)->setTimezone(0)->toDateTimeString();
        $this->endDate = Carbon::parse($end, tz: $this->timezone)
            ->addHours(23)
            ->addMinutes(59)
            ->addSeconds(59)
            ->setTimezone(0)->toDateTimeString();
    }
}
