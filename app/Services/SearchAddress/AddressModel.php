<?php

namespace App\Services\SearchAddress;

/**
 *
 */
class AddressModel
{

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

    /**
     * @var string|null
     */
    public ?string $street_address = null;
    /**
     * @var string|null
     */public ?string $address_text = null;

    /**
     * @param string|null $unit_number
     * @param string|null $street_number
     * @param string|null $street_name
     * @param string|null $postcode
     * @param string|null $city
     * @param string|null $state
     * @param string|null $country
     */public function __construct(
        public ?string $unit_number = null,
        public ?string $street_number = null,
        public ?string $street_name = null,
        public ?string $postcode = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $country = null
    ) {
        $this->street_address = $this->unit_number ? $this->unit_number . "/". $this->street_number : $this->street_number;
        $this->street_address = trim($this->street_address . " " . $this->street_name . " ");

        if ($this->state && strlen($this->state) < 4) {
            $this->state = self::MAP_STATES[strtolower($this->state)] ?? null;
        }

        $this->address_text = trim(
            $this->street_address . "" .
            $this->city . " " .
            $this->state . " " .
            $this->postcode . " " .
            $this->country . " "
        );

    }
}
