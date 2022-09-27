<?php
return [
    'px_pay_user' => env('POWERSHOP_PX_PAY_USER', 'PowershopSignUp_Dev'),
    'px_pay_key' => env('POWERSHOP_PX_PAY_KEY', 'f598f2c0e3199578c499a778f33657b601f24f361af110fe0a22a542e5a1a657'),
    'px_pay_url' => env('POWERSHOP_PX_PAY_REDIRECT_URL', 'https://sec.windcave.com/pxaccess/pxpay.aspx'),

    'base_url' => env('POWERSHOP_BASE_URL', 'https://qa.test.powershop.com.au/'),
    'send_customer_data_url' => env('SEND_CUSTOMER_DATA_URL', 'api/signup'),
    'ping_url' => env('PING_URL', 'api/signup/ping'),
    'secret_token' => env('POWERSHOP_SECRET_TOKEN', 'Token token=bd3ebe302f742553eef05f496ad6946a'),
    'use_dummy_data' => env('POWERSHOP_DUMMY_DATA', false),
];
