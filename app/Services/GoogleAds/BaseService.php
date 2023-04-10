<?php

namespace App\Services\GoogleAds;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;

class BaseService {

    private string $iniFilePath;

    protected GoogleAdsClient $googleAdsClient;

    protected mixed $oAuth2Credential;


    protected string $customerID;
    protected string $conversionActionID;
    protected string $conversionValue;

    protected string $currency;


    public function __construct()
    {
        $this->iniFilePath = config('google_ads.credentials_file');
        $this->customerID = config('google_ads.customer_id');
        $this->conversionActionID = config('google_ads.conversion_action_id');
        $this->currency = config('google_ads.currency');
        $this->conversionValue = config('google_ads.conversion_value');

        $this->buildOAuth2Token()->buildGoogleClient();

    }

    private function buildOAuth2Token(): BaseService
    {
        $this->oAuth2Credential = (new OAuth2TokenBuilder())
            ->fromFile($this->iniFilePath)
            ->build();

        return $this;
    }

    private function buildGoogleClient(): void
    {
        $this->googleAdsClient = (new GoogleAdsClientBuilder())
            ->fromFile($this->iniFilePath)
            ->withOAuth2Credential($this->oAuth2Credential)
            ->build();
    }
}
