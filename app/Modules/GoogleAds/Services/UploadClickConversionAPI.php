<?php

namespace GoogleAds\Services;

use Exception;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Services\ClickConversion as ClickConversionCore;
use Log;
use Illuminate\Support\Carbon;
use Google\Ads\GoogleAds\Util\V13\GoogleAdsErrors;
use Google\Ads\GoogleAds\Util\V13\PartialFailures;
use App\Models\ClickConversion;

class UploadClickConversionAPI extends BaseGoogleConversionService
{

    private array $clickConversions = [];

    public function __construct()
    {
        parent::__construct();
        $this->conversionActionID = config('google_ads.conversion_action_ids.click');
    }

    public function setClickConversions(array $clicks): static
    {
        info("UploadClickConversionAPI: setting clicks (refer context)", $clicks);
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
            $gclId = $click['gcl_id'];
            $click = new ClickConversionCore([
                'conversion_action' => ResourceNames::forConversionAction($this->customerID, $this->conversionActionID),
                'conversion_value' => $this->conversionValue,
                'conversion_date_time' => $this->formatDate($click['conversion_date']),
                'currency_code' => $this->currency
            ]);
            $click->setGclid($gclId);
            $payloads[] = $click;
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
                $successfulGclIds = $this->printResults($response);
                if (!empty($successfulGclIds)) {
                    return $successfulGclIds;
                }
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
            info("Uploaded click conversion", [
                'conversion_date_time' => $result->getConversionDateTime(),
                'gclid' => $result->getGclid(),
                'action' => $result->getConversionAction(),
            ]);

            $gclIds[] = $result->getGclid();
        }

        return $gclIds;
    }

    private function printResults($response)
    {
        // Finds the failed operations by looping through the results.
        $successfulGclIds = [];
        $operationIndex = 0;
        foreach ($response->getResults() as $result) {
            /** @var AdGroup $result */
            if (PartialFailures::isPartialFailure($result)) {
                // the current iteration failed
                $errors = GoogleAdsErrors::fromStatus(
                    $operationIndex,
                    $response->getPartialFailureError()
                );
                $errorList = [];
                foreach ($errors as $error) {
                    info('operation failed', [
                        'index' => $operationIndex,
                        'message' => $error->getMessage()
                    ]);
                    $errorList[] = $error->getMessage();
                }
                $clickConversion = ClickConversion::find($this->clickConversions[$operationIndex]['id'] ?? 0);

                if ($clickConversion && !empty($errorList)) {
                    $clickConversion->reason = json_encode($errorList);
                    $clickConversion->status = ClickConversion::STATUS_UPLOAD_FAILED;
                    $clickConversion->save();
                }
            } else {
                // the current iteration is successfully submitted to Google Ads
                $gclId = $this->clickConversions[$operationIndex]['gcl_id'] ?? null;
                if (!empty($gclId)) {
                    $successfulGclIds[] = $gclId;
                }
            }
            $operationIndex++;
        }
        return $successfulGclIds;
    }

    private function formatDate(string $date): string
    {
        return Carbon::parse($date)->format('Y-m-d H:i:sP');
    }


}
