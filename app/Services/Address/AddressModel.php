<?php


namespace App\Services\Address;

class AddressModel{

    public function __construct(
        private ?string $unit_number = null,
        private ?string $address_text = null,
        private ?string $street_number = null,
        private ?string $street_name = null,
        private ?string $street_type = null,
        private ?string $postcode = null,
        private ?string $city = null,
        private ?string $state = null,
        private ?string $country = null,
    ) {}
    

    public function getUnitNumber(){
        return $this->unit_number;
    }
    
    public function getAddressText(){
        return $this->unit_number;
    }

    public function getStreetNumber(){
        return $this->street_number;
    }

    public function getStreetName(){
        return $this->street_name;
    }

    public function getStreetType(){
        return $this->street_type;
    }

    public function getPostcode(){
        return $this->postcode;
    }

    public function getCity(){
        return $this->city;
    }

    public function getState(){
        return $this->state;
    }

    public function getCountry(){
        return $this->country;
    }
}