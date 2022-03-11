<?php


namespace App\Services\Address;

class AddressModel{

    /**
     *
     */
    const STATE_NSW = 'New South Wales';
    /**
     *
     */
    const STATE_VIC = 'Victoria';
    /**
     *
     */
    const STATE_QLD = 'Queensland';
    /**
     *
     */
    const STATE_SA  = 'South Australia';
    /**
     *
     */
    const STATE_NT  = 'Northern Territory';
    /**
     *
     */
    const STATE_TAS = 'Tasmania';
    /**
     *
     */
    const STATE_ACT = 'Australian Capital Territory';
    /**
     *
     */
    const STATE_WA = 'Western Australia';

    /**
     *
     */
    const MAP_STATES = [
        'nsw' => self::STATE_NSW,
        'vic' => self::STATE_VIC,
        'qld' => self::STATE_QLD,
        'sa'  => self::STATE_SA,
        'nt'  => self::STATE_NT,
        'tas' => self::STATE_TAS,
        'act' => self::STATE_ACT,
        'wa'  => self::STATE_WA,
    ];

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
    ) 
    {
        if ($this->state && strlen($this->state) < 4) 
        {
            $this->state = self::MAP_STATES[strtolower($this->state)] ?? null;
        }
    }
    

    public function getUnitNumber(){
        return $this->unit_number;
    }
    
    public function getAddressText(){
        return $this->address_text;
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