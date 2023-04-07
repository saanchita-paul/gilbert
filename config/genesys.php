<?php

return [
    'token_url' => env('GENESYS_TOKEN_URL', 'https://login.mypurecloud.com.au/oauth/token'),
    'base_url' => env('GENESYS_BASE_URL', "https://api.mypurecloud.com.au"),
    'client_id' => env('GENESYS_CLIENT_ID', "f8cee49c-9a4f-4ed3-8afe-8be7173ad45d"),
    'client_secret' => env('GENESYS_CLIENT_SECRET', "SJZfiO5pNVm_rIa7Yp_wcabpNWqgQTdR8tjOXuaz6ZE"),
    'office_id' => env('CALL_CONVERSION_OFFICE', 184),
    'endpoints' => [
        'detail_conversation' => "/api/v2/analytics/conversations/details/query",
    ],
    'call_center_numbers' => [
        '61398525700',
        '61398525701',
    ],
];
