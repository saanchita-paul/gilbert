<?php


namespace App\Services\Address;

class AutocompleteModel{
    
    private string $id;
    private string $type;
    private string $fullAddress;


    private string $buildingName;
    private string $country;
    private string $eid;
    private string $exception;
    private string $flatUnitNumber;
    private string $flatUnitType;
    private string $floorLevelNumber;
    private string $floorLevelType;
    private string $locality;
    private string $lotNumber;
    private string $postal;
    private string $postalNumber;
    private string $postalType;
    private string $postcode;
    private string $state;
    private string $street;
    private string $street2;
    private string $streetName;
    private string $streetNumber;
    private string $streetSuffix;
    private string $streetType;
    private string $subdwelling;
    private string $_type;


    private string $Barcode;
    private string $Bsp;
    private string $CountryIso2;
    private string $CountryIso3;
    private string $DPID;
    private string $GNAFConfidence;
    private string $GNAFGroupPID;
    private string $GNAFLocalityPID;
    private string $GNAFPID;
    private string $GNAFPIDPosition;
    private string $GNAFPointPID;
    private string $GNAFReliability;
    private string $GeocodeLevel;
    private string $Latitude;
    private string $Longitude;
    private string $MeshblockId;
    private string $PAFPosition;
    private string $ParcelID;

    public function __construct()
    {
        
    }
}