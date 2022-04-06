<?php

return [
    'root_url' => env('TSA_BASE_URL','https://hood.tsagroup-tech.com'),
    'insert_url' => env('TSA_INSERT_URI','/api/campaign/4/list/10/insert'),
    'call_history' => env('TSA_INSERT_URI','/api/campaign/4/list/10/lead/'),
    'tsa_lead_id' => env('TSA_LEAD_URI', '/api/campaign/lead/search'),
    'x_api_service_name' => env('TSA_X_API_SERVICE_NAME','hoodai'),
    'x_api_token' => env('TSA_X_API_TOKEN','MmUxMDMxZDA3YWM5ZTQ2NDc5ZTdlYzgyNjVmMTZj'),
    'app_url' => env('APP_URL','https://crmagency.hood.ai'),
];
