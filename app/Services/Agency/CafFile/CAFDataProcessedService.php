<?php


namespace App\Services\Agency\CafFile;


use App\Models\ConnectionApplication;
use App\MovingUtilityData;
use App\Plan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class CAFDataProcessedService
{
    /**
     * @var \DateTime
     */
    protected $dateTime;

    /**
     * LeadDataExportQueryService constructor.
     *
     * @param \DateTime $dateTime
     */
    public function __construct(\DateTime $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public function urgentWithSolar(): Collection
    {
        $holidayDateService = new CheckHolidayService($this->dateTime);

        return ConnectionApplication::query()
            ->with('connectionServices')
            ->where('status', '=',ConnectionApplication::STATUS_SUBMITTED)
            ->where('has_solar','=', ConnectionApplication::HAS_SOLAR)
            ->where('moving_date', '<=', $holidayDateService->getNextBusinessDay(7)->format('Y-m-d 23:59:00'))
            ->whereHas('connectionServices', function (Builder $service) {
                $service->where('provider_name','=','ea');
            })
            ->get();
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public function urgentWithoutSolar(): Collection
    {
        $holidayDateService = new CheckHolidayService($this->dateTime);

        return ConnectionApplication::query()
            ->with('connectionServices')
            ->where('status', '=',ConnectionApplication::STATUS_SUBMITTED)
            ->where('has_solar', '=', ConnectionApplication::NO_SOLAR)
            ->where('moving_date', '<=', $holidayDateService->getNextBusinessDay(7)->format('Y-m-d 23:59:00'))
            ->whereHas('connectionServices', function (Builder $service) {
                $service->where('provider_name','=','ea');
            })->get();
    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public function nonUrgentWithSolar(): Collection
    {
        $holidayDateService = new CheckHolidayService($this->dateTime);
        return ConnectionApplication::query()
            ->with('connectionServices')
            ->where('status', ConnectionApplication::STATUS_SUBMITTED)
            ->where('has_solar', '=',ConnectionApplication::HAS_SOLAR)
            ->where('moving_date', '>', $holidayDateService->getNextBusinessDay(7)->format('Y-m-d 23:59:00'))
            ->whereHas('connectionServices', function (Builder $service) {
                $service->where('provider_name','=','ea');
            })->get();


    }

    /**
     * @return Collection
     * @throws \Exception
     */
    public function nonUrgentWithoutSolar(): Collection
    {
        $holidayDateService = new CheckHolidayService($this->dateTime);

        return ConnectionApplication::query()
            ->with('connectionServices')
            ->where('status', ConnectionApplication::STATUS_SUBMITTED)
            ->where('has_solar', '=', ConnectionApplication::NO_SOLAR)
            ->where('moving_date', '>', $holidayDateService->getNextBusinessDay(7)->format('Y-m-d 23:59:00'))
            ->whereHas('connectionServices', function (Builder $service) {
                $service->where('provider_name','=','ea');
            })->get();
    }

    /**
     * Will be implemented when we do have QLD power on/off question on moving_utility_data table.
     *
     * @return Collection
     */
    public function urgentQld()
    {
        return new Collection([]);
    }

    /**
     * Will be implemented when we do have QLD power on/off question on moving_utility_data table.
     *
     * @return Collection
     */
    public function nonUrgentQld()
    {
        return new Collection([]);
    }
}
