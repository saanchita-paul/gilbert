<?php

namespace Origin\Services;

use App\Models\ConnectionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class SubmitOrderAPI extends BaseOriginAPI
{
    const METHODNAME = 'Submit'; 

    const MAP_SUBMIT_TYPE = [
        'move' => 'CustomerMoveIn',
        // 'cancel' => 'CustomerCancel',
    ];

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
        // "primary" => "6",
    ];

    const MAP_CONCESSION_TYPE = [
        'DVA' => 'Dept. of Veteran Affairs',
        'HCC' => 'Health Care',
        'PCC' => 'Pensioner Concession',
        'QSC' => 'Queensland Seniors'
    ];

    const MAP_APPOINTMENT_ID = [
        '8:00am - 1:00pm' => '#0800#',
        '9:00am - 2:00pm' => '#0900#',
        '10:00am - 3:00pm' => '#1000#',
        '11:00am - 4:00pm' => '#1100#',
        '12:00pm - 5:00pm' => '#1200#',
        '1:00pm - 6:00pm' => '#1300#',
        '8:00am - 12:00pm' =>'AM',
        '1:00pm - 5:00pm' => 'PM', 
    ];

    const MAP_ADDITIONAL_INFO = [
        'CUST ON SITE',
        'KEYS IN METER BOX',
        'KEYS IN LETTER BOX',
        'Customer Consultation',
    ];

    /**
     * @var array $data
     */
    public function __construct(private array $data, private int $service_id = 0)
    {
        parent::__construct();
    }

    /**
     * @return array|boolean=false
     */
    public function hasError(){
        info('Attempt submit data to generate lead:', $this->data);
        $validator = Validator::make($this->data, [
            "connection" => 'in:' . implode(',', array_keys(self::MAP_CONNECTION_TYPE)),
            // "SaleDate" => 'required',
            "connectionDate" => 'required',
            "isExistingCustomer" => 'required|boolean',
            'isEmailBilling' => 'required|boolean',
            'isCorrespondenceEmail' => 'required|boolean', 
            "isAccessRequirement" => 'required|boolean',
            "isUnrestrainedAnimal" => 'required|boolean', 
            "isLifeSupport" => 'required|boolean', 
            "isLifeSupportGas" => 'required|boolean', 
            "isElectricalWork" => 'required|boolean',
            "isEnableMarketing" => 'required|boolean',
            "appointmentTime" => 'in:' . implode(',', array_keys(self::MAP_APPOINTMENT_ID)), //check
            'nmi_mirn' => 'required',
            "productInfo" => 'required|array',
            "productInfo.productId" => 'required',
            "productInfo.customerTypeId" => 'required|in:'.implode(',', self::MAP_CUSTOMER_TYPE),
            "productInfo.divisionId" => 'required',
            "addressInfo" => 'required|array',
            "addressInfo.addressInfo" => 'required|array',
            "addressInfo.addressId" => 'required',
            "residentialCustomerInfo" => 'required|array',
            "residentialCustomerInfo.title" => 'required|in:'.implode(',', array_keys(self::MAP_TITLE_TYPE)),
            "residentialCustomerInfo.firstname" => 'required',
            "residentialCustomerInfo.lastname" => 'required',
            "residentialCustomerInfo.dob" => 'required',
            "residentialCustomerInfo.phone" => 'required',
            "residentialCustomerInfo.phonetype" => 'required|in:' . implode(',', array_keys(self::MAP_PHONE_TYPE)),
            "residentialCustomerInfo.email" => 'required|email:rfc,dns',
            "concessionCardInfo" => 'array',
            "concessionCardInfo.type" => 'required_with:concessionCardInfo|in:'. implode(',', array_keys(self::MAP_CONCESSION_TYPE)),
            "concessionCardInfo.number" => 'required_with:concessionCardInfo',
            "concessionCardInfo.startDate" => 'required_with:concessionCardInfo',
            "concessionCardInfo.endDate" => 'required_with:concessionCardInfo',
            "contactPersonInfo" => 'array',
            "contactPersonInfo.title" => 'required_with:contactPersonInfo|in:'.implode(',', array_keys(self::MAP_TITLE_TYPE)),
            "contactPersonInfo.firstname" => 'required_with:contactPersonInfo',
            "contactPersonInfo.lastname" => 'required_with:contactPersonInfo',
            "contactPersonInfo.dob" => 'required_with:contactPersonInfo',
            // "contactPersonInfo.phone" => 'required_with:contactPersonInfo',
            // "contactPersonInfo.phonetype" => 'required_with:contactPersonInfo',
            // "contactPersonInfo.email" => 'required_with:contactPersonInfo|email:rfc,dns',
            "contactPersonInfo.type" => 'required_with:contactPersonInfo|in:'. implode(',', array_keys(self::MAP_CONTACT_TYPE)),
            "correspondenceAddress" => 'array',
            "correspondenceAddress.region" => 'required_with:correspondenceAddress',
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
        $methodname = self::METHODNAME . self::MAP_CONNECTION_TYPE[$this->data['connection']];

        $responseData = $this->postApi($url, $body, self::METHODNAME . 'CustomerMoveIn');

        if(empty($responseData))
            throw new \Exception(sprintf('Origin POST:%s - FAILED (Empty response from Origin)', $methodname));

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
     */
    private function getPartnerReferenceNumber(){
        $result = '';

        if($this->service_id != 0 && (config('origin.isTestReferenceNumber') || config('app.env') == 'local')){
            $digits = '0123456789';
            $alphas = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $alphaLen = 4;
            $digitLen = 6;

            $result = '';
            for($i = 0; $i < $alphaLen; $i++) {
                $random_character = $alphas[mt_rand(0, strlen($alphas) - 1)];
                $result .= $random_character;
            }
            for($i = 0; $i < $digitLen; $i++) {
                $random_character = $digits[mt_rand(0, strlen($digits) - 1)];
                $result .= $random_character;
            }
        }
        else{
            $result = 'HD'.strval($this->service_id);
        }
         
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
                    "EffectiveFromDate" => Carbon::parse($this->data['connectionDate'])->toDateTimeLocalString(),
                    "SPAppointmentID" => !empty($this->data['appointmentTime']) ? self::MAP_APPOINTMENT_ID[$this->data['appointmentTime']] : "",
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
                    "IsAccessRequirement" => $this->data['isAccessRequirement'], 
                    "IsUnrestrainedAnimal" => $this->data['isUnrestrainedAnimal'], 
                    "IsLifeSupport" => $this->data['isLifeSupport'], 
                    "IsLifeSupportGas" => $this->data['isLifeSupportGas'], 
                    "IsElectricalWork" => $this->data['isElectricalWork'], 
                    "AdditionalAccessInformation" => $this->data['isAccessRequirement'] ? $this->data['additionalAccessInformation'] : '',
                ],
            ],
            "CustomerInfo" => [
                "Type" => $this->data['productInfo']['customerTypeID'] ?? "0001", 
                "IsEmailPrefCorrChannel" => $this->data['isCorrespondenceEmail'],
                "EnableMarketingOffers" => $this->data['isEnableMarketing'],
                "ResidentialCustomerInfo" => [
                    "Title" => self::MAP_TITLE_TYPE[$this->data['residentialCustomerInfo']['title']], 
                    "FirstName" => $this->data['residentialCustomerInfo']['firstname'], 
                    "LastName" => $this->data['residentialCustomerInfo']['lastname'], 
                    "DateOfBirth" => $this->data['residentialCustomerInfo']['dob'], 
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
            ],
        ];
        
        if(isset($this->data['concessionCardInfo'])){
            $formatted['CustomerInfo']['ConcessionCardInfo'] = [
                "CardTypeID" => $this->data['concessionCardInfo']['type'], 
                "CardNumber" => $this->data['concessionCardInfo']['number'], 
                "StartDate" => $this->data['concessionCardInfo']['startDate'], 
                "EndDate" => $this->data['concessionCardInfo']['endDate'],
            ];
        }

        if(isset($this->data['contactPersonInfo'])){
            $formatted['CustomerInfo']['ContactPersons'][] = [
                "Title" => self::MAP_TITLE_TYPE[$this->data['contactPersonInfo']['title']],
                "FirstName" => $this->data['contactPersonInfo']['firstname'],
                "LastName" => $this->data['contactPersonInfo']['lastname'], 
                // "HomePhone" => "", 
                // "Mobile" => $this->data['contactPersonInfo']['phone'],
                "Email" => $this->data['contactPersonInfo']['email'] ?? '', 
                "DateOfBirth" => $this->data['contactPersonInfo']['dob'], 
                "FunctionTypeID" => self::MAP_CONTACT_TYPE[$this->data['contactPersonInfo']['type']],
            ];
        }

        if(isset($this->data['correspondenceAddress'])){
            $corAddr = [
              'RoomNo' => $this->data['correspondenceAddress']['roomNo'],
              'RoomType' => $this->data['correspondenceAddress']['roomType'],
              'HouseNo' => $this->data['correspondenceAddress']['houseNo'],
              'Street' => $this->data['correspondenceAddress']['street'],
              'StreetType' => $this->data['correspondenceAddress']['streetType'],
              'City' => $this->data['correspondenceAddress']['city'],
              'PostalCode' => $this->data['correspondenceAddress']['postcode'],
              'Region' => $this->data['correspondenceAddress']['region'],
              'CountryID' => 'AU',  
            ];

            $formatted['CustomerInfo']['CorrespondenceAddress'] = array_filter($corAddr, function($value) { return !empty($value); });
            for($i=0; $i < count($formatted['OrderItems']); $i++){
                $formatted['OrderItems'][$i]['UseBPCommunicationAddress'] = true;
            }
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
