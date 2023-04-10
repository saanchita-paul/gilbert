<?php

namespace GoogleAds\Services;

use Exception;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Services\ClickConversion as ClickConversionCore;
use Log;

class UploadClickConversionAPI extends BaseGoogleConversionService
{

    private array $clickConversions = [];


    public function setClickConversions(array $clicks): static
    {
        $this->clickConversions = $clicks;

        return $this;
    }

    /**
     * @return ClickConversionCore[]
     */
    private function buildPayload(): array
    {
        $payloads = [];

        foreach ($this->clickConversions as $click) {
            $click = new ClickConversionCore([
                'conversion_action' => ResourceNames::forConversionAction($this->customerID, $this->conversionActionID),
                'conversion_value' => $this->conversionValue,
                'conversion_date_time' => $click['conversion_date'],
                'currency_code' => $this->currency
            ]);
            $click->setGclid($click['gcl_id']);
        }

        return $payloads;
    }

    /**
     * @throws Exception
     */
    public function uploadClick(): array
    {
        try {
            $clickConversions = $this->buildPayload();


            // Issues a request to upload the click conversion.
            $conversionUploadServiceClient = $this->googleAdsClient->getConversionUploadServiceClient();
            $response = $conversionUploadServiceClient->uploadClickConversions(
                $this->customerID,
                $clickConversions,
                true
            );

            // Prints the status message if any partial failure error is returned.
            // Note: The details of each partial failure error are not printed here, you can refer to
            if ($response->hasPartialFailureError()) {
                $mgs = "Partial failures occurred: {$response->getPartialFailureError()->getMessage()}";
                Log::error($mgs);
                throw new Exception($mgs);
            } else {
                // Prints the result if exists.
                return $this->parseResponse($response);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            Log::error($exception->getTraceAsString());
            throw new Exception($exception);
        }
    }

    /**
     * @param $response
     * @return array
     */
    private function parseResponse($response): array
    {
        $gclIds = [];
        foreach ($response->getResults() as $result) {
            $gclIds[] = $result->getGclid();
        }

        return $gclIds;
    }


}
