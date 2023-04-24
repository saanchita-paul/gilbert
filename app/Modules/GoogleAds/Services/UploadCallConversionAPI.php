<?php

namespace GoogleAds\Services;

use Carbon\Carbon;
use Exception;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Services\CallConversion;
use Google\Ads\GoogleAds\V13\Services\CallConversionResult;
use Log;
use Google\Ads\GoogleAds\Util\V13\GoogleAdsErrors;
use Google\Ads\GoogleAds\Util\V13\PartialFailures;
use App\Models\CallConversion as CallConversionModel;

class UploadCallConversionAPI extends BaseGoogleConversionService
{


    private array $callConversions = [];

    public function __construct()
    {
        parent::__construct();
        $this->conversionActionID = config('google_ads.conversion_action_ids.call');
    }

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
            $conversionData = [
                'conversion_action' =>
                    ResourceNames::forConversionAction($this->customerID, $this->conversionActionID),
                'caller_id' => $this->formatPhoneNumber($conversion['caller_id']),
                'call_start_date_time' => $this->formatDate($conversion['call_start_at']),
                'conversion_date_time' => $this->formatDate($conversion['conversion_date']),
                'conversion_value' => $this->conversionValue,
                'currency_code' => $this->currency,
            ];

            info('creating call conversion', $conversionData);
            $conversions[] = new CallConversion($conversionData);
        }

        return $conversions;
    }

    private function formatDate(string $date): string
    {
        return Carbon::parse($date)->format('Y-m-d H:i:sP');
    }

    private function formatPhoneNumber(string $phoneNumber): string
    {
        return preg_replace('/[^0-9+]/', '', preg_replace('/^0/', "+61", $phoneNumber));
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
            if ($response->hasPartialFailureError()) {
                $mgs = "Partial failures occurred: {$response->getPartialFailureError()->getMessage()}";
                Log::error($mgs);
                $this->printResults($response);
                throw new Exception($mgs);
            } else {
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
            info("Uploaded call conversion", [
                'call_start_date' => $result->getCallStartDateTime(),
                'caller_id' => $result->getCallerId(),
                'action' => $result->getConversionAction()
            ]);
            $callerIds[] = $result->getCallerId();
        }

        return $callerIds;
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
                $callConversion = CallConversionModel::find($this->callConversions[$operationIndex]['id'] ?? 0);

                if ($callConversion && !empty($errorList)) {
                    $callConversion->reason = json_encode($errorList);
                    $callConversion->status = CallConversionModel::STATUS_UPLOAD_FAILED;
                    $callConversion->save();
                }
            } else {
                // the current iteration is successfully submitted to Google Ads
                $gclId = $this->callConversions[$operationIndex]['gcl_id'] ?? null;
                if (!empty($gclId)) {
                    $successfulGclIds[] = $gclId;
                }
            }
            $operationIndex++;
        }
        return $successfulGclIds;
    }
}
