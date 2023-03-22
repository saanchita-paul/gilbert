<?php

namespace App\Services\hubspot;

use App\Models\ClickConversion;
use App\Models\ConnectionApplication;
use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;

class HubspotService {
    protected array $applications = [];

    public function __construct() {
        // trying to fetch unresolved gcl_id
        $this->fetchConnectionApplications();

        //fetch their gcl_id from hubspot
        $this->fetchGclId();

        //send those data to the google ads api

    }

    private function fetchConnectionApplications(): void {
        $this->applications = ConnectionApplication::query()
            ->with('clickConversion', function ($query){
                $query->whereNull('gcl_id')
                    ->where('is_uploaded_click', ClickConversion::IS_UPLOADED_CLICK_FALSE);
            })
            ->whereHas('clickConversion')
            ->get()
            ->toArray();
    }

    private function fetchGclId(): void {
        foreach ($this->applications AS $app){
            // we have to call hubspot api
        }
    }

    private function sendAnalytics(){
        $customerId = env('CUSTOMER_ID');
        $conversionId = env('CONVERSION_ID');
        $conversionDateTime = env('CONVERSION_DATE_TIME');
        $conversionValue = env('CONVERSION_VALUE');
        $adsCredentialFile = env('ADS_CREDENTIALS_FILE');

        $oAuth2Credential = (new OAuth2TokenBuilder())
            ->fromFile('/home/shihab/www/src/google-ads-laravel/google_ads_php.ini')
            ->build();
    }
}
