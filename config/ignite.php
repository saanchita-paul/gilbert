<?php

return [
    'create_contact' => env('HUB_SPOT_CREATE_CONTACT_URL', 'https://api.hubapi.com/contacts/v1/contact?hapikey='),
    'update_contact' => env('HUB_SPOT_UPDATE_CONTACT_URL', 'https://api.hubapi.com/contacts/v1/contact/vid/${id}/profile?hapikey='),
    'api_key' => env('HUB_SPOT_API_KEY', '1c0e91c4-2dbc-4f31-992c-e1341e5144c7')
];
