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
     */
    public function __construct(private string $option, private string $id_value)
    {
        if(in_array($this->option, self::MAP_VALIDATE_TYPE)){
            $this->option = self::MAP_VALIDATE_TYPE[$this->option];
        } 
    }

    /**
     * Validate address from origin 
     * 
     * @return void
     * 
     */
    public function fetch(){
        if(empty($this->option) || empty($this->id_value))
            return false;
        
        $url = config('origin.baseurl') . config('origin.endpoints.validate_address_nmi_mirn');
        $params = [
            $this->option => $this->id_value
        ];

        $responseData = $this->getApi($url, $params, self::METHODNAME);

        if(empty($responseData))
            return false;

        $formattedData = [
            'addressID' => $responseData['OrderAddressID'],
            'addressInfo' => $responseData['Address'],
            'status' => self::MAP_ADDRESS_STATUS[$responseData['Status']] ?? 'invalid'
        ];

        return $formattedData;
    }

}
