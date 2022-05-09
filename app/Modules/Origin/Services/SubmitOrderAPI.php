<?php

namespace Origin\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class SubmitOrderAPI extends BaseOriginAPI
{
    const METHODNAME = 'SubmitOrder:'; 

    const MAP_CONNECTION_TYPE = [
        "move" => "CUST_MOVE",
        // "switch" => "CUST_SWT",
        // "change" => "CUST_PRCH",
        // "cancel" => "CUST_CANC",
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
        "Lady" => "0001",
        "Mrs" => "0002",
        "Dr" => "0003",
        "Sir" => "0004",
        "Prof" => "0005",
        "Sr" => "0006",
        "Rev" => "0007",
        "Mr" => "0008",
        "Miss" => "0009",
        "Fr" => "0010",
        "Ms" => "0011",
    ];

    const MAP_CONTACT_TYPE = [
        "joint" => "1",
        "authorized" => "2",
        "primary" => "6",
    ];


    /**
     * @var array $data
     */
    public function __construct(private array $data)
    {
        parent::__construct();
    }

    /**
     * @return array|boolean=false
     */
    public function hasError(){
        $validator = Validator::make($this->data, [
            "Connection" => 'required|in:' . implode(',', array_keys(self::MAP_CONNECTION_TYPE)),
            "SaleDate" => 'required',
            "ConnectionDate" => 'required',
            "IsExistingCustomer" => 'required|boolean',
            'IsEmailBilling' => 'required|boolean',
            'NMI_MIRN' => 'required|boolean',
            "ProductInfo" => 'required|array',
            "ProductInfo.productID" => 'required',
            "ProductInfo.customerTypeID" => 'required|in:' . implode(',', array_keys(self::MAP_CUSTOMER_TYPE)),
            "ProductInfo.divisionID" => 'required',
            "AddressInfo" => 'required|array',
            "AddressInfo.addressInfo" => 'required|array',
            "AddressInfo.addressID" => 'required',
            "ResidentialCustomerInfo" => 'required|array',
            "ResidentialCustomerInfo.title" => 'required',
            "ResidentialCustomerInfo.firstname" => 'required',
            "ResidentialCustomerInfo.lastname" => 'required',
            "ResidentialCustomerInfo.dob" => 'required',
            "ResidentialCustomerInfo.phone" => 'required',
            "ResidentialCustomerInfo.phonetype" => 'required|in:' . implode(',', array_keys(self::MAP_PHONE_TYPE)),
            "ResidentialCustomerInfo.email" => 'required|email:rfc,dns',
            // "ConcessionCardInfo" => 'array',
            "ContactPersonInfo" => 'array',
            "ContactPersonInfo.title" => 'required',
            "ContactPersonInfo.firstname" => 'required',
            "ContactPersonInfo.lastname" => 'required',
            "ContactPersonInfo.dob" => 'required',
            "ContactPersonInfo.phone" => 'required',
            "ContactPersonInfo.phonetype" => 'required',
            "ContactPersonInfo.email" => 'required|email:rfc,dns',
        ]);

        if($validator->fails()){
            return $validator->errors()->messages();
        }

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
        // $body = $this->data ?? [];
        $body = $this->getDummyData(); // test data

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

    /**
     * @return string
     */
    private function getPartnerReferenceNumber(){
        // $alpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numeric = '0123456789';
        // $alphaLength = 4;
        $numericLength = 6;
        $string = 'ORGN';

        // for ($i = 0; $i < $alphaLength; $i++) {
        //     $string .= $alpha[mt_rand(0, strlen($alpha) - 1)];
        // }

        for ($i = 0; $i < $numericLength; $i++) {
            $string .= $numeric[mt_rand(0, strlen($numeric) - 1)];
        }

        return $string; 
    }

    /**
     * @return array
     */
    private function getFormattedData() : array{
        return [
            "OrderType" => "Contract",
            "ConnectionScenarioID" => self::MAP_CONNECTION_TYPE[$this->data['Connection']],
            "OrderStatus" => "Submitted",
            "PartnerReferenceNumber" => $this->getPartnerReferenceNumber(),
            "DateOfSale" => $this->data['SaleDate'],
            "CancellationReason" => "",
            "CustomerTypeID" => $this->data['ProductInfo']['customerTypeID'] ?? "0001",
            "IsExistingCustomer" => $this->data['IsExistingCustomer'],
            "OrderItems" => [
                [
                    "OrderItemType" => "MoveIn", // check again
                    "NMI_MIRN" => $this->data['NMI_MIRN'],
                    "ProductID" => $this->data['ProductInfo']['productID'],
                    "EffectiveFromDate" => $this->data['ConnectionDate'],
                    "SPAppointmentID" => "",
                    "OrderAddressID" => $this->data['AddressInfo']['addressID'],
                    "DivisionID" => $this->data['ProductInfo']['divisionID'],
                    "IsEmailBilling" => $this->data['IsEmailBilling'],
                    ""
                ],
            ],
            "OrderAddresses" => [
                [
                    "Address" => array_diff_key($this->data["AddressInfo"]["addressInfo"], array_flip(["__metadata"])),
                    "OrderAddressID" => $this->data["AddressInfo"]['addressID'],
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
                "Type" => $this->data['ProductInfo']['customerTypeID'] ?? "0001", 
                "IsEmailPrefCorrChannel" => true, // check again
                "EnableMarketingOffers" => false, //check again
                "ResidentialCustomerInfo" => [
                    "Title" => self::MAP_TITLE_TYPE[$this->data['ResidentialCustomerInfo']['title']], 
                    "FirstName" => $this->data['ResidentialCustomerInfo']['firstname'], 
                    "LastName" => $this->data['ResidentialCustomerInfo']['lastname'], 
                    "DateOfBirth" => $this->data['ResidentialCustomerInfo']['dob'], 
                ], 
                "ConcessionCardInfo" => [
                    "CardTypeID" => "", 
                    "CardNumber" => "", 
                    "StartDate" => null, 
                    "EndDate" => null, 
                ], 
                "PhoneNumbers" => [
                    [
                        "PhoneNumber" => $this->data['ResidentialCustomerInfo']['phone'], 
                        "TypeID" => self::MAP_PHONE_TYPE[$this->data['ResidentialCustomerInfo']['phonetype']], 
                        "IsDefault" => true, 
                    ], 
                ], 
                "Emails" => [
                    [
                        "Email" => $this->data['ResidentialCustomerInfo']['email'], 
                        "IsDefault" => true, 
                    ], 
                ], 
                "ContactPersons" => [
                    [
                        "Title" => self::MAP_TITLE_TYPE[$this->data['ResidentialCustomerInfo']['title']],
                        "FirstName" => $this->data['ResidentialCustomerInfo']['firstname'], 
                        "LastName" => $this->data['ResidentialCustomerInfo']['lastname'],
                        "HomePhone" => "", 
                        "Mobile" => $this->data['ResidentialCustomerInfo']['phone'], 
                        "DateOfBirth" => $this->data['ResidentialCustomerInfo']['dob'],
                        "FunctionTypeID" => self::MAP_CONTACT_TYPE['primary'], 
                    ],
                    [
                        "Title" => self::MAP_TITLE_TYPE[$this->data['ContactPersonInfo']['title']],
                        "FirstName" => $this->data['ContactPersonInfo']['firstname'],
                        "LastName" => $this->data['ContactPersonInfo']['lastname'], 
                        "HomePhone" => "", 
                        "Mobile" => $this->data['ContactPersonInfo']['phone'], 
                        "DateOfBirth" => $this->data['ContactPersonInfo']['dob'], 
                        "FunctionTypeID" => self::MAP_CONTACT_TYPE['authorized'], 
                    ], 
                ],
            ],
        ]; 
    }

    /**
     * @return array
     */
    private function getDummyData() : array {
        return [
            "OrderType" => "Contract", 
            "ConnectionScenarioID" => "CUST_MOVE", 
            "OrderStatus" => "Submitted", 
            "PartnerReferenceNumber" => $this->getPartnerReferenceNumber(), 
            "DateOfSale" => Carbon::now()->toDateTimeLocalString(), // "2022-05-05T16:22:00" 
            "CancellationReason" => "", 
            "CustomerTypeID" => "0001", 
            "IsExistingCustomer" => false, 
            "OrderItems" => [
                    [
                        "OrderItemType" => "MoveIn", 
                        "NMI_MIRN" => "62037972735", 
                        "ProductID" => "02899ca6-cb0e-1edb-9afe-a244ca947708", 
                        "EffectiveFromDate" => Carbon::now()->toDateTimeLocalString(), 
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
    }

}
