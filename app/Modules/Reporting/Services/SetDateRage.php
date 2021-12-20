<?php

namespace App\Modules\Reporting\Services;

use Carbon\Carbon;

trait SetDateRage
{
    private function setDateRange(string $start, string $end)
    {
        $this->timezone = env("TIME_ZONE", 11) ?? 11;

        $this->start = $start;
        $this->end = $end;
        $this->start = Carbon::parse($start, tz: $this->timezone)->setTimezone(0)->toDateTimeString();
        $this->end = Carbon::parse($end, tz: $this->timezone)
            ->addHours(11)
            ->addMinutes(59)
            ->addSeconds(59)
            ->setTimezone(0)->toDateTimeString();
    }
}
