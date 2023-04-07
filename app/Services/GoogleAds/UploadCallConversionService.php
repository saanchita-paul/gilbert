<?php

namespace App\Services\GoogleAds;

use App\Models\ClickConversion;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Services\CallConversion;
use Google\Ads\GoogleAds\V13\Services\CallConversionResult;
use Google\Ads\GoogleAds\V13\Services\ClickConversion AS ClickConversionCore;
use Google\Ads\GoogleAds\V13\Services\CustomVariable;
use Google\ApiCore\ApiException;
use Illuminate\Http\Client\Request;

class UploadCallConversionService {

    protected  $oAuth2Credential;
    protected  $googleAdsClient;
    protected string $customerID;
    protected string $conversionActionID;
    protected string $conversionDateTime;
    protected string $conversionValue;
    protected string $iniFilePath;


    public function __construct()
    {
        $this->customerID = config('google_ads.customer_id');
        $this->conversionActionID = config('google_ads.conversion_action_id');
//        $this->conversionDateTime = config('google_ads.conversion_date_time');
        $this->conversionDateTime = '2022-01-01 19:32:45-05:00';
        $this->conversionValue = config('google_ads.conversion_value');
        $this->iniFilePath = config('google_ads.ads_credentials_file');
//        $this->iniFilePath = "/home/shihab/www/src/gilbert/google_ads_php.ini";

        $this->buildOAuth2Token();
        $this->buildGoogleClient();
    }

    public function buildOAuth2Token(): void
    {
        $this->oAuth2Credential = (new OAuth2TokenBuilder())
            ->fromFile($this->iniFilePath)
            ->build();
    }

    public function buildGoogleClient(): void
    {
        $this->googleAdsClient = (new GoogleAdsClientBuilder())
            ->fromFile($this->iniFilePath)
            ->withOAuth2Credential($this->oAuth2Credential)
            ->build();
    }

    public function uploadCall(): bool
    {
        // Creates a call conversion by specifying currency as USD.
        $callConversion = new CallConversion([
            'conversion_action' =>
                ResourceNames::forConversionAction($this->customerID, $this->conversionActionID),
            'caller_id' => 'tel:+61411858473',
            'call_start_date_time' => now()->addDays(-5)->toDateTimeString(),
            'conversion_date_time' => now()->toDateTimeString(),
            'conversion_value' => $this->conversionValue,
            'currency_code' => 'USD'
        ]);
//        if (!is_null($conversionCustomVariableId) && !is_null($conversionCustomVariableValue)) {
//            $callConversion->setCustomVariables([new CustomVariable([
//                'conversion_custom_variable' => ResourceNames::forConversionCustomVariable(
//                    $customerId,
//                    $conversionCustomVariableId
//                ),
//                'value' => $conversionCustomVariableValue
//            ])]);
//        }

        // Issues a request to upload the call conversion.
        $conversionUploadServiceClient = $this->googleAdsClient->getConversionUploadServiceClient();
        $response = $conversionUploadServiceClient->uploadCallConversions(
            $this->customerID,
            [$callConversion],
            true
        );

        // Prints the status message if any partial failure error is returned.
        // Note: The details of each partial failure error are not printed here, you can refer to
        // the example HandlePartialFailure.php to learn more.
        if ($response->hasPartialFailureError()) {
            printf(
                "Partial failures occurred: '%s'.%s",
                $response->getPartialFailureError()->getMessage(),
                PHP_EOL
            );
        } else {
            // Prints the result if exists.
            /** @var CallConversionResult $uploadedCallConversion */
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

        return true;

    }


}
