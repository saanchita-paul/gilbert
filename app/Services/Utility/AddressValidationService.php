<?php


namespace App\Services\Utility;


class AddressValidationService
{
    private $address;
    private $validateKey;
    private $invalidFiedl;
    public function __construct( array $address, array $validateKey = ['city', 'street_number', 'postcode', 'state','street_name','tenancy_type'])
    {
        $this->address = $address;
        $this->validateKey = $validateKey;
    }

    public function validate()
    {
        foreach($this->validateKey as $key)
        {
            if(isset($this->address[$key]) && empty($this->address[$key])) {
                $this->invalidFiedl[$this->address[$key]];
            }
        }
        return $this->validateKey;
    }
}
