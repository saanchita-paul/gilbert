<?php

namespace Origin\Services;

class CheckFuelAPI extends BaseOriginAPI
{

    const METHODNAME = 'CheckFuel';

    const MAP_CUSTOMER_TYPE = [
        "resident" => '0001',
        "business" => '0002',
    ];

    const MAP_FUEL_TYPE = [
        "01" => 'Electricity',
        "02" => 'Gas',
        "03" => 'Hot Water',
    ];

    const MAP_ELIGIBILITY_TYPE = [
        "01" => 'Fully Eligible',
        "02" => 'Partially Eligible',
        "03" => 'Not Eligible',
        "04" => 'Unknown'
    ];

    const ELIGIBLE_STATUSES = [
        "01",
        "04",
    ];


    /**
     * @var string $customerType
     * @var string $addressID
     */
    public function __construct(private string $customerType, private string $addressID, private string $divisionID = '')
    {
        parent::__construct();
        if(in_array($this->customerType, array_keys(self::MAP_CUSTOMER_TYPE))){
            $this->customerType = self::MAP_CUSTOMER_TYPE[$this->customerType];
        }
        else if(!in_array($this->customerType, self::MAP_CUSTOMER_TYPE)){
            $this->customerType = null;
        }
    }

    /**
     * Check fuel availability from origin for an address 
     * 
     * @return array
     * 
     * @throws exception
     */
    public function fetch(){
        if(empty($this->customerType) || empty($this->addressID))
            throw new \Exception(sprintf('Origin GET:%s - FAILED (Missing customer type or address id from connection application)', self::METHODNAME));

        if(!empty($this->divisionID) && !in_array($this->divisionID, array_keys(self::MAP_FUEL_TYPE)))
            throw new \Exception(sprintf('Origin GET:%s - FAILED (Invalid division ID to validate fuel type)', self::METHODNAME));

        $url = config('origin.baseurl') . config('origin.endpoints.check_fuel');
        $params = [
            'CustomerTypeID' => sprintf('\'%s\'', $this->customerType),
            'OrderAddressID' => sprintf('\'%s\'', $this->addressID),
        ];

        $responseData = $this->getApi($url, $params, self::METHODNAME);

        if(empty($responseData))
            throw new \Exception(sprintf('Origin GET:%s - FAILED (Empty response from Origin)', self::METHODNAME));

        $fuelOffers = [];    
        foreach($responseData['results'] as $fuel){
            if(!empty($this->divisionID) && $fuel['DivisionID'] == $this->divisionID && !in_array($fuel['EligibilityStatusID'], self::ELIGIBLE_STATUSES)){
                throw new \Exception(sprintf('Origin GET:%s - FAILED [%s](%s is not available for the address due to %s)', self::METHODNAME, $fuel['EligibilityStatusID'], self::MAP_FUEL_TYPE[$this->divisionID], $fuel['Reason']), self::CODE_REJECT);
            }
            $fuelOffers[] = [
                'fuelType' => self::MAP_FUEL_TYPE[$fuel['DivisionID']] ?? 'Unknown',
                'fuelSequenceNumber' => $fuel['DivisionSequence'],
                'statusCode' => $fuel['EligibilityStatusID'],
                'status' => self::MAP_ELIGIBILITY_TYPE[$fuel['EligibilityStatusID']] ?? 'Unknown',
                'errorReason' => $fuel['Reason'] ?? '',
            ];
        }
        $formattedData = [
            'fuelOffers' => $fuelOffers,
        ];

        return $formattedData;
    }

}
