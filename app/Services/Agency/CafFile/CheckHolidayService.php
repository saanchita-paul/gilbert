<?php


namespace App\Services\Agency\CafFile;


use App\AustralianHoliday;
use Carbon\Carbon;

class CheckHolidayService
{
    public static $interval = 'P1D';
    /**
     * @var \DateTime
     */
    protected $dateTime;

    public static $state;
    /**
     * @var string[]
     */
    public static array $weeklyHolidays = ['Saturday', 'Sunday'];

    /**
     * @var \DateTime[]
     */
    protected $dates = [];

    /**
     * @var \DateTime
     */
    protected $nextBusinessDay;

    /**
     * CheckHolidayService constructor.
     *
     * @param \DateTime|string $dateTime
     */
    public function __construct($dateTime = '')
    {
        $this->dateTime = $dateTime instanceof \DateTime ? $dateTime : new \DateTime($dateTime);
    }

    public function isTomorrow(): bool
    {
        $tomorrow = Carbon::now()->setTimezone('+10:00')->addDay();
        return $this->dateTime->format('Y-m-d') == $tomorrow->format('Y-m-d');
    }

    public function isAfterNextGivenHours(int $hours): bool
    {
        $targetDay = Carbon::now()->setTimezone('+10:00')->addHours($hours);
        return Carbon::parse($this->dateTime)->gte($targetDay);
    }

    /**
     * check if date is in past .
     *
     * @return bool
     */
    public function isPastDate(): bool
    {
        return Carbon::parse($this->dateTime)->isPast();
    }

    /**
     * Is time is before 11:30 am or not.
     *
     * @return bool
     */
    public function isBeforeElevenThirtyAm(): bool
    {
        $now = Carbon::now()->setTimezone('+10:00');
        return (int)$now->format('Hi') <= 1130;
    }

    /**
     * Is time is before given time (in 24hrs hhmm int) or not.
     *
     * @return bool
     */
    public function isBeforeGivenTime(int $time): bool
    {
        $now = Carbon::now()->setTimezone('+10:00');
        return (int)$now->format('Hi') <= $time;
    }

    /**
     * Is time is after given time (in 24hrs hhmm int) or not.
     *
     * @return bool
     */
    public function isAfterGivenTime(int $time): bool
    {
        $now = Carbon::now()->setTimezone('+10:00');
        return (int)$now->format('Hi') > $time;
    }


    /**
     * @param null $state
     *
     * @return bool
     */
    public function isHoliday($state = null): bool
    {
        return in_array($this->dateTime->format('l'), static::$weeklyHolidays) || $this->isPublicHolidayExceptWeekend($this->dateTime, $state);
    }

    /**
     * Get next 3 business days. We we need a DateTime object of the next third business days. E.g. 12 March 2021
     *
     * @param int $days
     *
     * @return \DateTime
     * @throws \Exception
     */
    public function getNextBusinessDay(int $days): \DateTime
    {
        $count = 0;
        $daysAhead = sprintf('+%d days', $days * 2);
        $this->dates = new \DatePeriod($this->dateTime, new \DateInterval(static::$interval), new \DateTime($daysAhead));
        foreach ($this->dates as $dateTime) {
            if (!in_array($dateTime->format('l'), static::$weeklyHolidays)) {
                if ($count == $days) {
                    $this->nextBusinessDay = $dateTime;
                    break;
                }
                $count++;
            }
        }
        return $this->nextBusinessDay;
    }

    public function getNextWorkingDay(int $days = 0): \DateTime {
        if (!Carbon::parse($this->dateTime)->isPast()) {
            $finalDate = clone $this->dateTime;
        } else {
            $finalDate = Carbon::now();
        }

        $daysAhead = sprintf('+%d weekday', $days);
        $finalDate =  $finalDate->modify($daysAhead);
        $dateFound = false;
        while (!$dateFound) {
            if (in_array($finalDate->format('l'), static::$weeklyHolidays) || $this->isPublicHolidayExceptWeekend($finalDate, null)) {
                $finalDate =  $finalDate->modify('+1 weekday');
            } else {
                $dateFound = true;
            }
        }
        return $finalDate;
    }


    /**
     * @param \DateTime $dateTime
     * @param null      $state
     *
     * @return bool
     */
    public function isPublicHolidayExceptWeekend(\DateTime $dateTime, $state = null): bool
    {
        return AustralianHoliday::query()
            ->where('date', $dateTime->format('Y-m-d'))
            ->where('jurisdiction', $state)
            ->exists();
    }
}
