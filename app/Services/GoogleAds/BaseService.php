<?php

namespace App\Services\GoogleAds;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClient;
use Google\Ads\GoogleAds\Lib\V13\GoogleAdsClientBuilder;

class BaseService {

    protected GoogleAdsClient $googleAdsClient;

    protected mixed $oAuth2Credential;

    private string $iniFilePath;


    public function __construct()
    {
        $this->iniFilePath = config('google_ads.credentials_file');

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
