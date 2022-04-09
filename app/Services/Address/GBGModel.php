<?php


namespace App\Services\Address;

use App\Services\Address\AddressModel;

class GBGModel{

    /**
     * @var null
     */
    private $street_number;

    public function __construct(private ?array $gbgRawData = null)
    {
        $this->gbgRawData =  $gbgRawData;
        $this->firstResult    =  $gbgRawData['payload'][0] ?? null;
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
       $this->setValue('unknown');
       $this->setValue('exception');
    }

    private function setValue(string $name)
    {
        $this->$name = data_get($this->addressArray , $name);
    }

    public function getConnectionApplicationVersion(): AddressModel
    {
        return new AddressModel(
            unit_number: $this->flatUnitNumber,
            address_text: $this->fullAddress,
            street_number: $this->streetNumber,
            street_name: $this->streetName,
            street_type: $this->streetType,
            postcode: $this->postcode,
            city: $this->locality,
            state: $this->state,
            country: $this->country,
            street_name_only: $this->streetName,
        );
    }

    public function __toString()
    {
        return $this->gbgRawData;
    }

}
