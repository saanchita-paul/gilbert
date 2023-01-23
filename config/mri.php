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
        'search_tenancies_by_tag' => '/residentialproperty/v1/tenancies/search',
        'update_tenancies_tag' => '/residentialproperty/v1/tags/{tag_id}/entities',
        'get_all_tags' => '/residentialproperty/v1/taggroups',
    ],
    'sub_days' => env('MRI_FETCH_SUB_DAYS', 1),
    'hood_tag_group_name' => env('MRI_HOOD_TAG_GROUP', 'Connect with HOOD'),
    'hood_tag_name' => env('MRI_HOOD_TAG', 'YES'),
    'get_tenancies_page_size' => env('MRI_PAGE_SIZE', 100),
    'max_get_notes_count' => env('MRI_MAX_GET_NOTES_COUNT', 2), // max times run get notes API
    'start_check_notes_count' => env('MRI_START_CHECK_NOTES_COUNT', 2), // start validate and send email for detail missing after checked notes enough times
];
