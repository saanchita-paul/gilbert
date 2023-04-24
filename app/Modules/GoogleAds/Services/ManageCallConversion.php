<?php

namespace GoogleAds\Services;

use App\Models\CallConversion;
use Exception;

class ManageCallConversion
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
        $this->getCalls();

        if (count($this->calls) === 0) {
            info("ManageCallConversion: No new call to upload");
            return;
        }

        $service = new UploadCallConversionAPI();
        $res = $service->setCallConversions($this->calls)->uploadCall();

        $this->updateUploadedAt($res);
    }

    private function getCalls(): void
    {
        $this->calls = CallConversion::query()
            ->whereNotNull('conversion_date')
            ->whereNotNull('caller_id')
            ->where('status', CallConversion::STATUS_FETCHED)
            ->whereNull('uploaded_at')
            ->select(['id', 'conversion_date', 'caller_id', 'call_start_at'])
            ->get()
            ->toArray();
        // dump($this->calls);
    }

    private function updateUploadedAt(array $gclIds): void
    {
        CallConversion::query()
            ->whereIn('caller_id', $gclIds)
            ->update(['uploaded_at' => now()]);
    }
}
