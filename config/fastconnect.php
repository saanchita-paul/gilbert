<?php

return [
    'root_url' => env('FC_BASE_URL','https://sandbox.fastconnect.net.au'),
    'get_token_uri' => env('FC_TOKEN_URI','/oauth/token?grant_type=client_credentials&scope=datafind'),
    'search_nmi_mirn_uri' => env('FC_NMI_MIR_NURI', '/api/datafind/address'),
    'base64_key' => env('FC_BASE64_KEY','Basic c2FuZGJveF9HcWxNejlaR3d0OE03dzdZNzdGUzV5elI6bndJQXM5WTJKR3NmVEVNVU1CU25JWWFKam4wMW9vT1hkNjZrMFhWY2E3S016SGI4'),
    'get_water_token_uri' => env('FC_TOKEN_URI','/oauth/token?grant_type=client_credentials&scope=datafind product'),
    'get_product_group_uri' => env('FC_WATER_PRODUCT_GROUP', '/api/order/groups'),
    'get_product_details_uri' => env('FC_WATER_PRODUCT_DETAILS', '/api/order/products'),
    'submit_water_lead_url' => env('FC_SUBMIT_LEAD_URL', '/api/order/submit'),
    'submitted_water_status_lead_url' => env('FC_SUBMITTED_LEAD_URL', '/api/order/status/customer_reference'),
    'get_countries' => env('GET_COUNTRIES', '/api/order/countries'),
];
