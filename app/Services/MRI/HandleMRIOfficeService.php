<?php

namespace App\Services\MRI;

use App\Models\MriOffice;
use Carbon\Carbon;

class HandleMRIOfficeService
{
    /**
     * @param int $officeId
     * @return void
     */
    public function __construct(private int $officeId)
    {
    }

    /**
     * Save MRI office
     *
     * @param $mriOffice
     * @return void
     */
    public function saveMRIOffice($mriOffice): void
    {
        $mriOfficeDetails = [
            'office_id' => $this->officeId,
            'application_id' => config('mri.app_id'),
            'key' => $mriOffice['key'],
            'company_name' => $mriOffice['company_name'],
            'activation_date' => $this->formatDate($mriOffice['activation_date'])
        ];

        try {
            MriOffice::query()->create($mriOfficeDetails);
        } catch (\Exception $exception) {
            \Log::error('Error: ', [$exception->getMessage(), $exception->getTraceAsString()]);
        }
    }

    /**
     * Update MRI office
     *
     * @param $mriOffice
     * @return void
     */
    public function updateMRIOffice($mriOffice): void
    {
        $mriOfficeDetails = [
            'office_id' => $this->officeId,
            'application_id' => config('mri.app_id'),
            'key' => $mriOffice['key'],
            'company_name' => $mriOffice['company_name'],
            'activation_date' => $this->formatDate($mriOffice['activation_date'])
        ];

        try {
            MriOffice::query()->updateOrCreate(['office_id' => $this->officeId], $mriOfficeDetails);
        } catch (\Exception $exception) {
            \Log::error('Error: ', [$exception->getMessage(), $exception->getTraceAsString()]);
        }
    }

    /**
     * @param string $date
     * @return string|null
     */
    private function formatDate(string $date): ?string
    {
        return Carbon::parse($date)->format("Y-m-d H:i:s") ?? null;
    }
}
