<?php
return [
    'create_contact' => env('HUB_SPOT_CREATE_CONTACT_URL', 'https://api.hubapi.com/contacts/v1/contact?hapikey='),
    'update_contact' => env('HUB_SPOT_UPDATE_CONTACT_URL', 'https://api.hubapi.com/contacts/v1/contact/vid/${id}/profile?hapikey='),
    'api_key' => env('HUB_SPOT_API_KEY', '721bd41e-2f81-4279-9ebc-ccf1822f740d')
];
