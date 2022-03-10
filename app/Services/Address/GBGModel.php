<?php


namespace App\Services\Address;

use App\Services\Address\AddressModel;

class GBGModel{

    /**
     * @var null
     */
    private $street_number;

    public function __construct(private ?object $gbgRawData = null)
    {
        $this->gbgRawData =  $gbgRawData;
        $this->firstResult    =  $gbgRawData['results'][0] ?? null;
        $this->addressArray   =  $this->firstResult ?? null;

        if($this->addressArray)
        {
            $this->setAddress();
        }
    }

    private function setAddress()
    {
       $this->setValue('country');
       $this->setValue('flatUnitNumber');
       $this->setValue('flatUnitType');
       $this->setValue('fullAddress');
       $this->setValue('id');
       $this->setValue('locality');
       $this->setValue('postcode');
       $this->setValue('state');
       $this->setValue('street');
       $this->setValue('streetName');
       $this->setValue('streetNumber');
       $this->setValue('streetType');
       $this->setValue('subdwelling');
    }

    private function setValue(string $name)
    {
        $this->$name = data_get($this->addressArray , $name);
    }

    public function getConnectionApplicationVersion(): AddressModel
    {
        return new AddressModel(
            address_text: $this->fullAddress,
            country: $this->country,
            unit_number: $this->flatUnitNumber,
            street_number: $this->streetNumber,
            street_name: $this->streetName,
            street_type: $this->streetType,
            postcode: $this->postcode,
            city: $this->locality,
            state: $this->state,
        );
    }

    public function __toString()
    {
        return $this->gbgRawData;
    }

}