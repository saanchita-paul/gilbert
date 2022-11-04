<?php

namespace App\Services\MRI;

use App\Models\MriOffice;
use Carbon\Carbon;

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
     * @return string
     */
    public function saveData(): string
    {
        $app_id = 'f89d9246-4e4a-437f-a6ba-1940282b097d';
        $responseData = (new MriApplicationKeyService())->getData();

        $details = [
            'office_id' => $this->officeId,
            'application_id' => $app_id,
            'key' => $responseData['key'],
            'company_name' => $responseData['company_name'],
            'activation_date' => $this->formatDate($responseData['activation_date'])
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
