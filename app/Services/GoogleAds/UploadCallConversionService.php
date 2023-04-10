<?php

namespace App\Services\GoogleAds;


use Carbon\Carbon;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Services\CallConversion;
use Google\Ads\GoogleAds\V13\Services\CallConversionResult;
use Log;

class UploadCallConversionService extends BaseService {

    protected string $customerID;
    protected string $conversionActionID;
    protected string $conversionValue;

    private array $callConversions = [];
    private $currency;


    public function __construct()
    {
        parent::__construct();

        $this->customerID = config('google_ads.customer_id');
        $this->conversionActionID = config('google_ads.conversion_action_id');
        $this->currency = config('google_ads.currency');
        $this->conversionValue = config('google_ads.conversion_value');
    }


    public static function upload(array $conversions): bool
    {
        return (new UploadCallConversionService())->setCallConversions($conversions)->uploadCall();
    }


    private function setCallConversions(array $calls): static
    {
        $this->callConversions = $calls;

        return $this;
    }


    /**
     * @return CallConversion[]
     */
    private function buildConversionsPayload(): array
    {
        $conversions = [];

        foreach ($this->callConversions as $conversion) {
            $conversions[] = new CallConversion([
                'conversion_action' =>
                    ResourceNames::forConversionAction($this->customerID, $this->conversionActionID),
                'caller_id' => $conversion['caller_id'],
                'call_start_date_time' => $this->formatDate($conversion['call_start_at']),
                'conversion_date_time' => $this->formatDate($conversion['conversion_value']),
                'conversion_value' => $this->conversionValue,
                'currency_code' => $this->currency,
            ]);
        }

        return $conversions;
    }

    private function formatDate(string $date): string
    {
        return Carbon::parse($date)->format('Y-m-d H:i:sP');
    }

    /**
     */
    public function uploadCall(): bool
    {
        try {
            // Creates a call conversion by specifying currency as USD.
            $callConversions = $this->buildConversionsPayload();

            // Issues a request to upload the call conversion.
            $conversionUploadServiceClient = $this->googleAdsClient->getConversionUploadServiceClient();
            $response = $conversionUploadServiceClient->uploadCallConversions(
                $this->customerID,
                $callConversions,
                true
            );
            // Prints the status message if any partial failure error is returned.
            // Note: The details of each partial failure error are not printed here, you can refer to
            // the example HandlePartialFailure.php to learn more.
            if ($response->hasPartialFailureError()) {
                Log::error("Partial failures occurred: {$response->getPartialFailureError()->getMessage()}");
            } else {
                // Prints the result if exists.
                /** @var CallConversionResult $uploadedCallConversion */

                #todo: handle success properly
                $uploadedCallConversion = $response->getResults()[0];
                printf(
                    "Uploaded call conversion that occurred at '%s' for caller ID '%s' to the "
                    . "conversion action with resource name '%s'.%s",
                    $uploadedCallConversion->getCallStartDateTime(),
                    $uploadedCallConversion->getCallerId(),
                    $uploadedCallConversion->getConversionAction(),
                    PHP_EOL
                );
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            return false;
        }

        return true;
    }
}
