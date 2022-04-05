<?php


namespace App\Services\Address;

use App\Services\Address\GBGModel;
use App\Services\Address\AddressModel;
use App\Services\Address\LookUpOptions;

class LookUpService{

    public function __construct(public ?AddressModel $addressModel = null)
    {
        
    }

    private function setData($data = null)
    {
        $location = new GBGModel($data);
        $this->locationModel = $location->getConnectionApplicationVersion();
    }

    public function findAddressByText(string $text, LookUpOptions $options) : AddressModel 
    {
        $data = null;
        $this->setData($data);
        return $this->locationModel;
    }

    public function findAddressById(string $id, LookUpOptions $options) : AddressModel 
    {
        $data = null;
        $this->setData($data);
        return $this->locationModel;
    }

    public function addressCleansing(string $text, LookUpOptions $options) : AddressModel 
    {
        $data = null;
        $this->setData($data);
        return $this->locationModel;
    }

}