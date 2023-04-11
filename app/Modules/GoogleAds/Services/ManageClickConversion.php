<?php

namespace GoogleAds\Services;

use App\Models\ClickConversion;
use Exception;

/**
 *
 */
class ManageClickConversion
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
        $this->getClicks();
        $service = new UploadClickConversionAPI();
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
            ->whereNull('uploaded_at')
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
