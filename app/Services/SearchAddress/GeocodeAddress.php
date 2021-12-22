<?php

namespace App\Services\SearchAddress;

class GeocodeAddress{
    
    public function __construct(object $geoCodeRawData)
    {   
        $this->geoCodeRawData =  $geoCodeRawData;
        $this->firstResult    =  $geoCodeRawData['results'][0] ?? null;
        $this->addressArray   =  $this->firstResult['address_components'] ?? null;
        
        if($this->addressArray)
        {
            $this->setAddress();
        }
    }

    private function setAddress()
    {
       $this->setValue('subpremise');
       $this->setValue('street_number');
       $this->setValue('locality');
       $this->setValue('postal_code');
       $this->setValue('country');
       $this->setValue('route');
       $this->setValue('administrative_area_level_1');
       $this->setValue('administrative_area_level_2');
       $this->formatted_address = $this->firstResult['formatted_address'] ?? null;
       $this->location_type = $this->firstResult['geometry']["location_type"] ?? null;
    }

    private function setValue(string $name)
    {
        $longName = $name.'_long_name';
        $shortName = $name.'_short_name';
        $this->$longName = null;
        $this->$shortName = null;
        
        $data =  $this->geoCodeRawData['results'][0]['address_components'];
        
        foreach ($data as $value) {
                 if(in_array($name, $value['types']) ){
                     $this->$longName = $value['long_name'];
                     $this->$shortName = $value['short_name'];
                     break;
                 }
             }
    }

    public function getConnectionApplicationVersion(){
        $connectionApplication = new ConnectionApplicationMapper($this);
        return $connectionApplication;
    }

    public function __toString() 
    {
        return $this->geoCodeRawData;
    }
}