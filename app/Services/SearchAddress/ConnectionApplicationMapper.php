<?php

namespace App\Services\SearchAddress;

class ConnectionApplicationMapper
{

    public function __construct(GeocodeAddress $geocodeAddress)
    {
        $this->street_number =  $geocodeAddress->street_number_long_name ?? null;
        $this->address_unit  =  $geocodeAddress->subpremise_long_name ?? null;
        $this->street_address=  $geocodeAddress->route_long_name ?? null;
        $this->postcode      =  $geocodeAddress->postal_code_long_name ?? null;
        $this->state         =  $geocodeAddress->administrative_area_level_1_long_name ?? null;
        $this->city          =  $geocodeAddress->locality_long_name ?? null;
        $this->country       =  $geocodeAddress->country_long_name ?? null;
        $this->address_text  =  $geocodeAddress->formatted_address ?? null;
    }
}