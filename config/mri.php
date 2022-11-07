<?php

return [
    'base_url' => env('MRI_BASE_URL', 'https://uatapi.propertytree.io'),
    'app_id' => env('MRI_APP_ID', 'f89d9246-4e4a-437f-a6ba-1940282b097d'),
    'app_key' => env('MRI_APP_KEY', '4e1df42e-5c53-4762-b07a-79f8d731e0bc'),
    'subscription_key' => env('MRI_SUBSCRIPTION_KEY', '574800735b3b4effa9d8ef84d57d345f'),
    'to_mail_address' => env('MRI_OFFICE_EMAIL', 'mdshakilhossain091@gmail.com'),
    'endpoints' => [
        'get_mri_office_key_pairs' => '/apikey/v1/application_keys/',
        'get_all_agents' => '/residentialproperty/v1/Agents'
    ],
];
