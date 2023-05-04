<?php


return [
    'customer_id' => env('CUSTOMER_ID', 2596462964),
    'conversion_action_ids' => [
        'click' => env('CLICK_CONVERSION_ACTION_ID', 1035689406),
        'call' => env('CALL_CONVERSION_ACTION_ID', 1035684396),
    ],
    'conversion_action_id' => env('CONVERSION_ACTION_ID', 1035689406),
    'conversion_value' => env('CONVERSION_VALUE', 200),
    'conversion_date_time' => env('CONVERSION_DATE_TIME'), #todo: need to remove
    'ads_credentials_file' => env('ADS_CREDENTIALS_FILE', '/var/www/gbert/google_ads_php.ini'), #todo: need to remove
    'credentials_file' => env('GOOGLE_ADS_CREDENTIALS_FILE'),
    'currency' => env('GOOGLE_ADS_CURRENCY', 'AUD')
];
