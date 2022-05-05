<?php

namespace Origin\Services;

class SubmitOrderAPI extends BaseOriginAPI
{
    const METHODNAME = 'SubmitOrder:';

    private $dummyData = [
        "OrderType" => "Contract", 
        "ConnectionScenarioID" => "CUST_MOVE", 
        "OrderStatus" => "Submitted", 
        "PartnerReferenceNumber" => "ADAM000003", 
        "DateOfSale" => "2022-05-03T12:00:00", 
        "CancellationReason" => "", 
        "CustomerTypeID" => "0001", 
        "IsExistingCustomer" => false, 
        "OrderItems" => [
                [
                    "OrderItemType" => "MoveIn", 
                    "NMI_MIRN" => "62037972735", 
                    "ProductID" => "02899ca6-cb0e-1edb-9afe-a244ca947708", 
                    "EffectiveFromDate" => "2022-05-02T12:00:00", 
                    "SPAppointmentID" => "", 
                    "OrderAddressID" => "CRM0010470040", 
                    "DivisionID" => "01", 
                    "IsEmailBilling" => true, 
                ], 
        ], 
        "OrderAddresses" => [
            [
                "Address" => [
                    "StandardFlag" => false, 
                    "City" => "STANHOPE", 
                    "District" => "", 
                    "PostalCode" => "3623", 
                    "POBoxPostalCode" => "", 
                    "POBox" => "", 
                    "POBoxType" => "", 
                    "Street" => "HILL RD", 
                    "StreetType" => "", 
                    "StreetSuffix" => "", 
                    "HouseNo" => "297", 
                    "LotNo" => "", 
                    "Building" => "WORKSHOP", 
                    "Floor" => "", 
                    "FloorType" => "", 
                    "RoomNo" => "", 
                    "RoomType" => "", 
                    "CountryID" => "AU", 
                    "Region" => "VIC", 
                    "TimeZone" => "AUSVIC", 
                    "TaxJurisdictionCode" => "", 
                    "LanguageID" => "E", 
                    "ShortForm" => "", 
                    "IsQASValid" => false, 
                    "DPID" => "", 
                ], 
                "OrderAddressID" => "CRM0010470040", 
                "IsPrimaryResidence" => true, 
                "IsAccessRequirement" => false, 
                "IsUnrestrainedAnimal" => false, 
                "IsLifeSupport" => false, 
                "IsLifeSupportGas" => false, 
                "IsElectricalWork" => false, 
                "AdditionalAccessInformation" => "", 
            ],
        ], 
        "CustomerInfo" => [
            "Type" => "0001", 
            "IsEmailPrefCorrChannel" => true, 
            "EnableMarketingOffers" => false, 
            "ResidentialCustomerInfo" => [
                "Title" => "0008", 
                "FirstName" => "Adam", 
                "LastName" => "Johnson", 
                "DateOfBirth" => "1998-03-06T06:00:00", 
            ], 
            "ConcessionCardInfo" => [
                "CardTypeID" => "", 
                "CardNumber" => "", 
                "StartDate" => null, 
                "EndDate" => null, 
            ], 
            "PhoneNumbers" => [
                [
                    "PhoneNumber" => "0402922579", 
                    "TypeID" => "3", 
                    "IsDefault" => true, 
                ], 
            ], 
            "Emails" => [
                [
                    "Email" => "testuser@mailinator.com", 
                    "IsDefault" => true, 
                ], 
            ], 
            "ContactPersons" => [
                [
                "Title" => "0002", 
                "FirstName" => "Amy", 
                "LastName" => "Johnson", 
                "HomePhone" => "0397655416", 
                "Mobile" => "0412345678", 
                "DateOfBirth" => "1997-04-13T03:00:00", 
                "FunctionTypeID" => "6", 
                ], 
            ],
        ], 
    ]; 
 
 

    const MAP_CONNECTION_TYPE = [
        "customer" => [
            "move" => "CUST_MOVE",
            // "switch" => "CUST_SWT",
            // "change" => "CUST_PRCH",
            "cancel" => "CUST_CANC",
        ],
        // "prospect" => [
        //     "move" => "PROS_MOVE",
        //     "switch" => "POST_SWT",
        // ],
    ];

    const MAP_CUSTOMER_TYPE = [
        "residential" => "0001",
        "business" => "0002",
    ];

    const MAP_PHONE_TYPE = [
        "landline" => "1",
        "mobile" => "3",
    ];

    const MAP_TITLE_TYPE = [
        "lady" => "0001",
        "mrs" => "0002",
        "dr" => "0003",
        "sir" => "0004",
        "prof" => "0005",
        "sr" => "0006",
        "rev" => "0007",
    ];


    /**
     * @var array $data
     */
    public function __construct(private array $data)
    {
        parent::__construct();
    }

    /**
     * @return boolean
     */
    public function validate(){
        return false;
    }

    /**
     * Get product info from origin 
     * 
     * @return array
     * 
     */
    public function submit(){
        $url = config('origin.baseurl') . config('origin.endpoints.submit_order');
        $body = $this->dummyData;

        $responseData = $this->postApi($url, $body, self::METHODNAME . 'CustomerMoveIn');

        if(empty($responseData))
            return false;

        $formattedData = [
            'OrderHeaderID' => $responseData['OrderHeaderID'],
            'OriginReferenceNumber' => $responseData['ReferenceNumber'],
            'HoodReferenceNumber' => $responseData['PartnerReferenceNumber'],
        ];

        return $formattedData;
    }

}
