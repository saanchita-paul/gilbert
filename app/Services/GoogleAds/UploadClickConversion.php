<?php

namespace App\Services\GoogleAds;

use App\Models\ClickConversion;
use Exception;

/**
 *
 */
class UploadClickConversion
{

    /**
     * @var array
     */
    private array $clicks;


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
        $service = new UploadClickConversionService();
        $res = $service->setClickConversions($this->clicks)->uploadClick();

        $this->updateUploadedAt($res);
    }

    /**
     * @return void
     */
    private function getClicks(): void
    {
        $this->clicks = ClickConversion::query()
            ->whereNotNull('gcl_id')
            ->whereNotNull('conversion_date')
            ->where('uploaded_at', false)
            ->select(['conversion_date', 'gcl_id'])
            ->get()
            ->toArray();
    }

    /**
     * @param array $gclIds
     * @return void
     */
    private function updateUploadedAt(array $gclIds): void
    {
        ClickConversion::query()
            ->whereIn('gcl_id', $gclIds)
            ->update(['uploaded_at' => now()]);
    }
}
