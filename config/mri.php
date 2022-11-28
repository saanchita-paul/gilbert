<?php

return [
    'base_url' => env('MRI_BASE_URL', 'https://uatapi.propertytree.io'),
    'app_id' => env('MRI_APP_ID', 'f89d9246-4e4a-437f-a6ba-1940282b097d'),
    'app_key' => env('MRI_APP_KEY', '4e1df42e-5c53-4762-b07a-79f8d731e0bc'),
    'subscription_key' => env('MRI_SUBSCRIPTION_KEY', '574800735b3b4effa9d8ef84d57d345f'),
    'support_emails' => env('MRI_SUPPORT_EMAILS', 'mdshakilhossain091@gmail.com'),
    'endpoints' => [
        'get_mri_office_key_pairs' => '/apikey/v1/application_keys/',
        'get_all_agents' => '/residentialproperty/v1/Agents',
        'get_tenancies' => '/residentialproperty/v1/Tenancies',
        'get_property_by_id' => '/residentialproperty/v1/Properties/',
        'get_notes' => '/residentialproperty/v1/Notes',
    ],
    'sub_days' => env('MRI_FETCH_SUB_DAYS', 1),
];
