<?php
return [
    'refresh_token' => env("PROPERTY_ME_REFRESH_TOKEN", "3fa172352a51afcb0e4755a3bd44a214fc6f066d7383ebe813c28457f06d4d90"),

    'client_id_v1' => env("PROPERTY_ME_CLIENT_ID_V1", "4327dc6d-a137-43c2-aabb-80992a7e3fcc"),
    'client_secret_v1' => env("PROPERTY_ME_CLIENT_SECRET_V1", "af7a6283-6dee-4870-83f0-0638d8cc0d82"),
    'client_id_v2' => env("PROPERTY_ME_CLIENT_ID_V2", "0efaa3d4-e63f-4922-8c12-d519d6227196"),
    'client_secret_v2' => env("PROPERTY_ME_CLIENT_SECRET_v2", "f4eb9a1e-a446-472a-84b6-065a6012ad13"),
    'client_version' => env('PROPERTY_ME_CLIENT_VERSION', 'v2'),


    "token_url" => env("PROPERTY_ME_REFRESH_TOKEN_API", "https://login.propertyme.com/connect/token"),
    "api_root_url" => env("PROPERTY_ME_API_ROOT_URL", "https://app.propertyme.com"),
    "get_contact_url" => env("PROPERTY_ME_GET_CONTACT_URL", "/api/v1/contacts"),
    "get_lots_url" => env("PROPERTY_ME_GET_LOTS_URL", "/api/v1/lots"),
    "get_tenancies_url" => env("PROPERTY_ME_GET_TENANCIES_URL", "/api/v1/tenancies"),
    'no_of_days'  => env('CONNECT_ME_NO_OF_DAYS', 1),
    'o_auth_url' => env("PROPERTY_ME_O_AUTH_URL", 'https://login.propertyme.com/connect/authorize'),
    'o_auth_callback_uri' => env("PROPERTY_ME_OAUTH_CALLBACK", '/property-me/callback'),

    'label' => env('PROPERTY_ME_LABEL', '|hood|'),
    'support_emails' => env('PROPERTY_ME_SUPPORT_EMAILS', 'justtlenin@gmail.com'),
];
