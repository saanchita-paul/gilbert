<?php

return [
    'username' => env('ORIGIN_USERNAME'),
    'password' => env('ORIGIN_PASSWORD'),
    'baseurl' => env('ORIGIN_BASE_URL', 'https://gw-qas2.rtlsys.origin.com.au'),
    'endpoints' => [
        'get_product_info' => '/sap/opu/odata/sap/PRODUCT_CATALOG/Products',
        'validate_address_nmi_mirn' => '/sap/opu/odata/sap/SALES/ValidateSupplyAddressesByExtID',
        'check_fuel' => '/sap/opu/odata/sap/PRODUCT_CATALOG/GetOfferedDivisionByAddress',
        'get_xcsrf_token' => '/sap/opu/odata/sap/SALES/OrderHeaders',
        'submit_order' => '/sap/opu/odata/sap/SALES/OrderHeaders',
    ],
];