<?php
return [
    'refresh_token' => env("PROPERTY_ME_REFRESH_TOKEN", "3fa172352a51afcb0e4755a3bd44a214fc6f066d7383ebe813c28457f06d4d90"),
    'client_id' => env("PROPERTY_ME_CLIENT_ID", "4327dc6d-a137-43c2-aabb-80992a7e3fcc"),
    'client_secret' => env("PROPERTY_ME_CLIENT_SECRET", "af7a6283-6dee-4870-83f0-0638d8cc0d82"),
    "refresh_token_url" => env("PROPERTY_ME_REFRESH_TOKEN_API", "https://login.propertyme.com/connect/token"),
    "api_root_url" => env("PROPERTY_ME_API_ROOT_URL", "https://app.propertyme.com"),
    "get_contact_url" => env("PROPERTY_ME_GET_CONTACT_URL", "/api/v1/contacts"),
    'no_of_days'  => env('CONNECT_ME_NO_OF_DAYS', -1),
];
