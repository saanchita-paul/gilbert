<?php

namespace Origin\Services;

class ValidateAddressAPI extends BaseOriginAPI
{

    const METHODNAME = 'ValidateAddress';

    const MAP_VALIDATE_TYPE = [
        "nmi" => 'NMI',
        "mirn" => 'MIRN',
    ];

    const MAP_ADDRESS_STATUS = [
        '01' => 'Valid',
        '02' => 'Possible Match - Single',
        '03' => 'Possible Match - Multiple',
        '04' => 'Invalid',
    ];

    /**
     * @var string $option
     * @var string $num_val
     */
    public function __construct(private string $option, private string $num_val)
    {
        parent::__construct();
        if(in_array($this->option, array_keys(self::MAP_VALIDATE_TYPE))){
            $this->option = self::MAP_VALIDATE_TYPE[$this->option];
        } 
    }

    /**
     * Validate address from origin 
     * 
     * @return address
     * 
     */
    public function fetch(){
        if(empty($this->option) || empty($this->num_val))
            return false;
        
        $url = config('origin.baseurl') . config('origin.endpoints.validate_address_nmi_mirn');
        $params = [
            $this->option => sprintf('\'%s\'', $this->num_val)
        ];

        $responseData = $this->getApi($url, $params, self::METHODNAME);

        if(empty($responseData))
            return false;

        $validateData = $responseData['ValidateSupplyAddressesByExtID'];

        $formattedData = [
            'addressID' => $validateData['OrderAddressID'],
            'addressInfo' => $validateData['Address'],
            'validateStatus' => self::MAP_ADDRESS_STATUS[$validateData['Status']] ?? 'invalid'
        ];

        return $formattedData;
    }

}
