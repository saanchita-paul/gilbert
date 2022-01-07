<?php

namespace App\Services\SearchAddress;

class AddressModel
{
    public function __construct(
        public ?string $unit_number = null,
        public ?string $street_number = null,
        public ?string $street_name = null,
        public ?string $postcode = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $country = null,
        public ?string $address_text = null,
    ) {
        $this->address_text = $this->unit_number ? $this->unit_number . "/". $this->street_number : $this->street_number;
        $this->address_text = trim(
            $this->address_text . " " .
            $this->street_name . " " .
            $this->city . " " .
            $this->state . " " .
            $this->postcode . " " .
            $this->country . " "
        );
    }
}
