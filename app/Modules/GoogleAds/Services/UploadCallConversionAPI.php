<?php

namespace GoogleAds\Services;


use Carbon\Carbon;
use Exception;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Services\CallConversion;
use Google\Ads\GoogleAds\V13\Services\CallConversionResult;
use Log;

class UploadCallConversionAPI extends BaseGoogleConversionService {


    private array $callConversions = [];



    public function setCallConversions(array $calls): static
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
                'conversion_date_time' => $this->formatDate($conversion['conversion_date']),
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
     * @throws Exception
     */
    public function uploadCall(): array
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
                $mgs = "Partial failures occurred: {$response->getPartialFailureError()->getMessage()}";
                Log::error($mgs);
                throw new Exception($mgs);
            } else {
                // Prints the result if exists.
                /** @var CallConversionResult $uploadedCallConversion */

                #todo: handle success properly
                $uploadedCallConversion = $this->parseResponse($response);
                printf(
                    "Uploaded call conversion that occurred at '%s' for caller ID '%s' to the "
                    . "conversion action with resource name '%s'.%s",
                    $uploadedCallConversion->getCallStartDateTime(),
                    $uploadedCallConversion->getCallerId(),
                    $uploadedCallConversion->getConversionAction(),
                    PHP_EOL
                );
                return  $this->parseResponse($response);
            }
        } catch (Exception $exception) {
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
        $callerIds = [];
        foreach ($response->getResults() as $result) {
            $callerIds[] = $result->getCallerId();
        }

        return $callerIds;
    }
}
