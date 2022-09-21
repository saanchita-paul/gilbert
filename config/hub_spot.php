<?php
return [
    'create_contact' => env('HUB_SPOT_CREATE_CONTACT_URL', 'https://api.hubapi.com/contacts/v1/contact?hapikey='),
    'update_contact' => env('HUB_SPOT_UPDATE_CONTACT_URL', 'https://api.hubapi.com/contacts/v1/contact/vid/${id}/profile?hapikey='),
    'api_key' => env('HUB_SPOT_API_KEY', '1c0e91c4-2dbc-4f31-992c-e1341e5144c7'), 
    'get_contact_by_email' => env('HUB_SPOT_GET_CONTACT_BY_EMAIL_URL', 'https://api.hubapi.com/contacts/v1/contact/email/${email}/profile?hapikey='),

];
