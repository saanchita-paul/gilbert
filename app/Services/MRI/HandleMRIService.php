<?php

namespace App\Services\MRI;

use App\Models\MriOffice;
use Carbon\Carbon;
use function MongoDB\BSON\toJSON;

class HandleMRIService
{
    /**
     * @param int $officeId
     * @return void
     */
    public function __construct(private int $officeId)
    {
    }

    /**
     *
     */
    public function saveData($data)
    {
        $details = [
            'office_id' => $this->officeId,
            'application_id' => config('mri.app_id'),
            'key' => $data['key'],
            'company_name' => $data['company_name'],
            'activation_date' => $this->formatDate($data['activation_date'])
        ];
        if (!empty($details)) {
            MriOffice::query()->create($details);
        }

        return 'Success';
    }

    /**
     * @param string $date
     * @return string|null
     */
    private function formatDate(string $date): ?string
    {
        return Carbon::parse($date)->format("Y-m-d H:i:s")  ?? null;
    }
}
