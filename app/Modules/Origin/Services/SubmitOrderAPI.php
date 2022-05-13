<?php

namespace Origin\Services;

use App\Models\ConnectionService;
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
        "resident" => "0001",
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
            "connection" => 'in:' . implode(',', array_keys(self::MAP_CONNECTION_TYPE)),
            // "SaleDate" => 'required',
            "connectionDate" => 'required',
            "isExistingCustomer" => 'required|boolean',
            'isEmailBilling' => 'required|boolean',
            'nmi_mirn' => 'required',
            "productInfo" => 'required|array',
            "productInfo.productId" => 'required',
            "productInfo.customerTypeId" => 'required|in:'.implode(',', self::MAP_CUSTOMER_TYPE),
            "productInfo.divisionId" => 'required',
            "addressInfo" => 'required|array',
            "addressInfo.addressInfo" => 'required|array',
            "addressInfo.addressId" => 'required',
            "residentialCustomerInfo" => 'required|array',
            "residentialCustomerInfo.title" => 'required',
            "residentialCustomerInfo.firstname" => 'required',
            "residentialCustomerInfo.lastname" => 'required',
            "residentialCustomerInfo.dob" => 'required',
            "residentialCustomerInfo.phone" => 'required',
            "residentialCustomerInfo.phonetype" => 'required|in:' . implode(',', array_keys(self::MAP_PHONE_TYPE)),
            "residentialCustomerInfo.email" => 'required|email:rfc,dns',
            // "ConcessionCardInfo" => 'array',
            "contactPersonInfo" => 'array',
            "contactPersonInfo.title" => 'required_with:contactPersonInfo',
            "contactPersonInfo.firstname" => 'required_with:contactPersonInfo',
            "contactPersonInfo.lastname" => 'required_with:contactPersonInfo',
            "contactPersonInfo.dob" => 'required_with:contactPersonInfo',
            "contactPersonInfo.phone" => 'required_with:contactPersonInfo',
            "contactPersonInfo.phonetype" => 'required_with:contactPersonInfo',
            "contactPersonInfo.email" => 'required_with:contactPersonInfo|email:rfc,dns',
        ]);

        if($validator->fails()){
            return $validator->errors()->messages();
        }

        return false;
    }

    // TODO:
    // submit GAS || ELECTRICITY

    /**
     * Get product info from origin 
     * 
     * @return array
     * 
     * if fail: save error in error table, then return string message
     * 
     */
    public function submit()
    {
        $url = config('origin.baseurl') . config('origin.endpoints.submit_order');
        $body = $this->getFormattedData($this->data);
        // $body = $this->getDummyData(); // test data

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
     * 
     * NOT COMPLETE! MAX COUNT CURRENTLY = 25999999
     */
    private function getPartnerReferenceNumber(){
        $count = ConnectionService::where('provider_name', ConnectionService::PROVIDER_ORIGIN)
                    ->whereNotNull('lead_reference')
                    ->count();
                    
        $strLen = 4;
        $digitLen = 6;
        $extra = 0;
        $str = '';
        
        if($count >= 1000000){
            $extra = (int) $count / 1000000;
    
            $count = $count % 1000000;
    
            for($i=$strLen; $i>0; $i--){
                $add = $extra % 26;
                $char = chr(ord('A') + $add);
                $str = $char . $str;
                $extra = max($extra - 26, 0);
            }
        }
        
        $str = str_pad($str, $strLen, "A", STR_PAD_LEFT);
        
        $digit = str_pad(strval($count), $digitLen, "0", STR_PAD_LEFT);
        
        $result = $str . $digit;

        return $result; 
    }

    /**
     * @return array
     */
    private function getFormattedData() : array{
        $formatted = [
            "OrderType" => "Contract",
            "ConnectionScenarioID" => self::MAP_CONNECTION_TYPE[$this->data['connection'] ?? 'move'],
            "OrderStatus" => "Submitted",
            "PartnerReferenceNumber" => $this->getPartnerReferenceNumber(),
            "DateOfSale" => Carbon::now()->setTimezone('Australia/Melbourne')->toDateTimeLocalString(), // "2022-05-05T16:22:00",
            "CancellationReason" => "",
            "CustomerTypeID" => $this->data['productInfo']['customerTypeId'],
            "IsExistingCustomer" => $this->data['isExistingCustomer'],
            "OrderItems" => [
                [
                    "OrderItemType" => "MoveIn", // check again
                    "NMI_MIRN" => $this->data['nmi_mirn'],
                    "ProductID" => $this->data['productInfo']['productId'],
                    "EffectiveFromDate" => $this->data['connectionDate'],
                    "SPAppointmentID" => "",
                    "OrderAddressID" => $this->data['addressInfo']['addressId'],
                    "DivisionID" => $this->data['productInfo']['divisionId'],
                    "IsEmailBilling" => $this->data['isEmailBilling'],
                ],
            ],
            "OrderAddresses" => [
                [
                    "Address" => array_diff_key($this->data["addressInfo"]["addressInfo"], array_flip(["__metadata"])),
                    "OrderAddressID" => $this->data["addressInfo"]['addressId'],
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
                "Type" => $this->data['productInfo']['customerTypeID'] ?? "0001", 
                "IsEmailPrefCorrChannel" => true, // check again
                "EnableMarketingOffers" => false, //check again
                "ResidentialCustomerInfo" => [
                    "Title" => self::MAP_TITLE_TYPE[$this->data['residentialCustomerInfo']['title']], 
                    "FirstName" => $this->data['residentialCustomerInfo']['firstname'], 
                    "LastName" => $this->data['residentialCustomerInfo']['lastname'], 
                    "DateOfBirth" => $this->data['residentialCustomerInfo']['dob'], 
                ], 
                "ConcessionCardInfo" => [
                    "CardTypeID" => "", 
                    "CardNumber" => "", 
                    "StartDate" => null, 
                    "EndDate" => null, 
                ], 
                "PhoneNumbers" => [
                    [
                        "PhoneNumber" => $this->data['residentialCustomerInfo']['phone'], 
                        "TypeID" => self::MAP_PHONE_TYPE[$this->data['residentialCustomerInfo']['phonetype']], 
                        "IsDefault" => true, 
                    ], 
                ], 
                "Emails" => [
                    [
                        "Email" => $this->data['residentialCustomerInfo']['email'], 
                        "IsDefault" => true, 
                    ], 
                ], 
                "ContactPersons" => [
                    [
                        "Title" => self::MAP_TITLE_TYPE[$this->data['residentialCustomerInfo']['title']],
                        "FirstName" => $this->data['residentialCustomerInfo']['firstname'], 
                        "LastName" => $this->data['residentialCustomerInfo']['lastname'],
                        "HomePhone" => "", 
                        "Mobile" => $this->data['residentialCustomerInfo']['phone'], 
                        "DateOfBirth" => $this->data['residentialCustomerInfo']['dob'],
                        "FunctionTypeID" => self::MAP_CONTACT_TYPE['primary'], 
                    ] 
                ],
            ],
        ]; 

        if(isset($this->data['contactPersonInfo'])){
            $formatted['CustomerInfo']['ContactPersons'][] = [
                "Title" => self::MAP_TITLE_TYPE[$this->data['contactPersonInfo']['title']],
                "FirstName" => $this->data['contactPersonInfo']['firstname'],
                "LastName" => $this->data['contactPersonInfo']['lastname'], 
                "HomePhone" => "", 
                "Mobile" => $this->data['contactPersonInfo']['phone'], 
                "DateOfBirth" => $this->data['contactPersonInfo']['dob'], 
                "FunctionTypeID" => self::MAP_CONTACT_TYPE['authorized'],
            ];
        }

        return $formatted;
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
