<?php


namespace App\Services\Address;

class AddressModel{

    /**
     *
     */
    const STATE_NSW = 'new south wales';
    /**
     *
     */
    const STATE_VIC = 'victoria';
    /**
     *
     */
    const STATE_QLD = 'queensland';
    /**
     *
     */
    const STATE_SA  = 'south australia';
    /**
     *
     */
    const STATE_NT  = 'northern territory';
    /**
     *
     */
    const STATE_TAS = 'tasmania';
    /**
     *
     */
    const STATE_ACT = 'australian capital territory';
    /**
     *
     */
    const STATE_WA = 'western australia';
    /**
     *
     */
    const STATE_SHORT_NSW = 'nsw';
    /**
     *
     */
    const STATE_SHORT_VIC = 'vic';
    /**
     *
     */
    const STATE_SHORT_QLD = 'qld';
    /**
     *
     */
    const STATE_SHORT_SA  = 'sa';
    /**
     *
     */
    const STATE_SHORT_NT  = 'nt';
    /**
     *
     */
    const STATE_SHORT_TAS = 'tas';
    /**
     *
     */
    const STATE_SHORT_ACT = 'act';
    /**
     *
     */
    const STATE_SHORT_WA = 'wa';
    /**
     *
     */
    const COUNTRY_AU = 'au';

    /**
     *
     */
    const MAP_COUNTRY = [
        self::COUNTRY_AU => "AUSTRALIA",
    ];

    /**
     *
     */
    const MAP_STATES_SHORT_TO_LONG = [
        self::STATE_SHORT_NSW => self::STATE_NSW,
        self::STATE_SHORT_VIC => self::STATE_VIC,
        self::STATE_SHORT_QLD => self::STATE_QLD,
        self::STATE_SHORT_SA  => self::STATE_SA,
        self::STATE_SHORT_NT  => self::STATE_NT,
        self::STATE_SHORT_TAS => self::STATE_TAS,
        self::STATE_SHORT_ACT => self::STATE_ACT,
        self::STATE_SHORT_WA  => self::STATE_WA,
    ];

    const MAP_STATES_LONG_TO_SHORT = [
        self::STATE_NSW => self::STATE_SHORT_NSW,
        self::STATE_VIC => self::STATE_SHORT_VIC,
        self::STATE_QLD => self::STATE_SHORT_QLD,
        self::STATE_SA  => self::STATE_SHORT_SA,
        self::STATE_NT  => self::STATE_SHORT_NT,
        self::STATE_TAS => self::STATE_SHORT_TAS,
        self::STATE_ACT => self::STATE_SHORT_ACT,
        self::STATE_WA  => self::STATE_SHORT_WA,
    ];

    public function __construct(
        private ?string $unit_number = null,
        private ?string $address_text = null,
        private ?string $street_number = null,
        private ?string $street_name = null,
        private ?string $street_name_only = null,
        private ?string $street_type = null,
        private ?string $postcode = null,
        private ?string $city = null,
        private ?string $state = null,
        private ?string $state_short = null,
        private ?string $country = null,
        private ?bool $is_address_complete = false,
    ) 
    {
        $this->street_address = $this->unit_number == null || $this->unit_number == "" ? "" : $this->unit_number . "/". $this->street_number;
        $this->street_address = trim($this->street_address . " " . $this->street_name . " ");

        if( $this->address_text == null || $this->address_text == "" )
        {
            $this->address_text = $this->street_address . " " . $this->city . " " . $this->state . " " . $this->postcode . " " . $this->country;
        }

        if ($this->state && strlen($this->state) < 4) 
        {
            $this->state_short = $this->state;
            $this->state = ucwords( self::MAP_STATES_SHORT_TO_LONG[strtolower($this->state)] ) ?? null;
        } 
        else 
        { 
            $this->state_short = ucfirst(self::MAP_STATES_LONG_TO_SHORT[strtolower($this->state)]) ?? null;
        }

        if ($this->country && strlen($this->country) < 3) 
        {
            $this->country = ucfirst(self::MAP_COUNTRY[strtolower($this->country)]) ?? null;
        }
    }
    

    public function getUnitNumber()
    {
        return $this->unit_number;
    }
    
    public function getAddressText()
    {
        return $this->address_text;
    }
    
    public function getStreetAddress()
    {
        return $this->street_address;
    }

    public function getStreetNumber()
    {
        return $this->street_number;
    }

    public function getStreetName()
    {
        return $this->street_name;
    }

    public function getStreetNameOnly()
    {
        return $this->street_name_only;
    }

    public function getStreetType()
    {
        return $this->street_type;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getStateShort()
    {
        return $this->state_short;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getIsAddressComplete()
    {
        return $this->is_address_complete;
    }

    public function setIsAddressComplete(bool $is_address_complete)
    {
        $this->is_address_complete = $is_address_complete;
    }
}