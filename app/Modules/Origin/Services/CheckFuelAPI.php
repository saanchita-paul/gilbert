<?php

namespace Origin\Services;

class CheckFuelAPI extends BaseOriginAPI
{

    const METHODNAME = 'CheckingFuelAvailability';

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


    /**
     * @var string $option
     */
    public function __construct(private string $option, private string $addressID)
    {
        parent::__construct();
        if(in_array($this->option, array_keys(self::MAP_CUSTOMER_TYPE))){
            $this->option = self::MAP_CUSTOMER_TYPE[$this->option];
        } 
    }

    /**
     * Check fuel availability from origin for an address 
     * 
     * @return void
     * 
     */
    public function fetch(){
        if(empty($this->option) || empty($this->addressID))
            return false;
        
        $url = config('origin.baseurl') . config('origin.endpoints.check_fuel');
        $params = [
            'CustomerTypeID' => sprintf('\'%s\'', $this->option),
            'OrderAddressID' => sprintf('\'%s\'', $this->addressID),
        ];

        $responseData = $this->getApi($url, $params, self::METHODNAME);

        if(empty($responseData))
            return false;

        $fuelOffers = [];    
        foreach($responseData['results'] as $fuel){
            $fuelOffers[] = [
                'fuelType' => self::MAP_FUEL_TYPE[$fuel['DivisionID']] ?? 'Unknown',
                'fuelSequenceNumber' => $fuel['DivisionSequence'],
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
