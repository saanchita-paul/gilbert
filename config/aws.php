<?php

return [
    'credentials' => [
        'key'    => env('AWS_ACCESS_KEY_ID', ''), #todo
        'secret' => env('AWS_SECRET_ACCESS_KEY', ''), #todo
    ],
    'region' => env('AWS_REGION', 'ap-southeast-2'), #todo
    'version' => 'latest',

    //SNS SMS
    'sms_type' => env('AWS_SNS_SMS_TYPE', 'Transactional'),
    'sender_id' => env('AWS_SNS_SENDER_ID', 'SmileStyler')
];
