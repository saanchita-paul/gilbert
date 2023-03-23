<?php

namespace App\Services\GoogleAds;

use App\Models\ClickConversion;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;
use Google\Ads\GoogleAds\Util\V13\ResourceNames;
use Google\Ads\GoogleAds\V13\Services\ClickConversion AS ClickConversionCore;
use Google\Ads\GoogleAds\V13\Services\CustomVariable;
use Google\ApiCore\ApiException;
use Illuminate\Http\Client\Request;

class UploadClickConversionService {

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
        $this->conversionDateTime = config('google_ads.conversion_date_time');
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

    public function uploadClick($gclId): bool {
        if (empty($gclId)) {
            throw new \UnexpectedValueException(
                "GCL_ID is needed but not provided"
            );
        }

        $clickConversion = new ClickConversionCore([
            'conversion_action' => ResourceNames::forConversionAction($this->customerID, $this->conversionActionID),
            'conversion_value' => $this->conversionValue,
            'conversion_date_time' => $this->conversionDateTime,
            'currency_code' => 'USD'
        ]);

        // Sets the single specified ID field.
        $clickConversion->setGclid($gclId);

        // Issues a request to upload the click conversion.
        $conversionUploadServiceClient = $this->googleAdsClient->getConversionUploadServiceClient();
        $response = $conversionUploadServiceClient->uploadClickConversions(
            $this->customerID,
            [$clickConversion],
            true
        );

        // Prints the status message if any partial failure error is returned.
        // Note: The details of each partial failure error are not printed here, you can refer to
        if ($response->hasPartialFailureError()) {
            printf(
                "Partial failures occurred: '%s'.%s",
                $response->getPartialFailureError()->getMessage(),
                PHP_EOL
            );
            return false;
        } else {
            // Prints the result if exists.
            $uploadedClickConversion = $response->getResults()[0];
            printf(
                "Uploaded click conversion that occurred at '%s' from Google Click ID '%s' " .
                "to '%s'.%s",
                $uploadedClickConversion->getConversionDateTime(),
                $uploadedClickConversion->getGclid(),
                $uploadedClickConversion->getConversionAction(),
                PHP_EOL
            );
            return true;
        }


    }


}
