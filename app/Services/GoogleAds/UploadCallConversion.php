<?php

namespace App\Services\GoogleAds;

use App\Models\CallConversion;
use Exception;

class UploadCallConversion
{

    private array $calls;

    /**
     * @throws Exception
     */
    public static function upload(): void
    {
        (new static())->run();
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $service = new UploadCallConversionService();
        $res = $service->setCallConversions($this->calls)->uploadCall();

        $this->updateUploadedAt($res);
    }

    private function getClicks(): void
    {
        $this->calls = CallConversion::query()
            ->whereNotNull('conversion_date')
            ->whereNotNull('caller_id')
            ->where('status', CallConversion::STATUS_FETCHED)
            ->where('uploaded_at', false)
            ->where('uploaded_at', false)
            ->select(['conversion_date', 'caller_id', 'call_start_at'])
            ->get()
            ->toArray();
    }

    private function updateUploadedAt(array $gclIds): void
    {
        CallConversion::query()
            ->whereIn('caller_id', $gclIds)
            ->update(['uploaded_at' => now()]);
    }
}
