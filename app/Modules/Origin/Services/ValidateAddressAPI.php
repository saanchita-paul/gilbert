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

    const AVAILABLE_STATUSES = [
        '01',
    ];

    /**
     * @var string $validateType
     * @var string $num_val
     */
    public function __construct(private string $validateType, private string $num_val)
    {
        parent::__construct();
        if(in_array($this->validateType, array_keys(self::MAP_VALIDATE_TYPE))){
            $this->validateType = self::MAP_VALIDATE_TYPE[$this->validateType];
        } 
    }

    /**
     * Validate address from origin 
     * 
     * @return address
     * 
     * @throws exception
     */
    public function fetch(){
        if(empty($this->validateType) || empty($this->num_val))
            throw new \Exception(sprintf('Origin GET:%s - FAILED (Missing nmi_mirn from connection application)', self::METHODNAME));
        
        $url = config('origin.baseurl') . config('origin.endpoints.validate_address_nmi_mirn');
        $params = [
            $this->validateType => sprintf('\'%s\'', $this->num_val)
        ];

        $responseData = $this->getApi($url, $params, self::METHODNAME);

        if(empty($responseData))
            throw new \Exception(sprintf('Origin GET:%s - FAILED (Empty response from Origin)', self::METHODNAME));

        $validateData = $responseData['ValidateSupplyAddressesByExtID'];

        if(!in_array($validateData['Status'], self::AVAILABLE_STATUSES)){
            throw new \Exception(sprintf('Origin GET:%s - FAILED [%s](Invalid address status "%s")', self::METHODNAME, $validateData['Status'], self::MAP_ADDRESS_STATUS[$validateData['Status']] ?? 'invalid'), self::CODE_REJECT);
        }

        $formattedData = [
            'addressID' => $validateData['OrderAddressID'],
            'addressInfo' => $validateData['Address'],
            'validateStatus' => self::MAP_ADDRESS_STATUS[$validateData['Status']] ?? 'invalid'
        ];

        return $formattedData;
    }

}
