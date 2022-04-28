<?php

namespace Origin\Services;

class GetProductInfoAPI extends BaseOriginAPI
{
    private ?string $product_code;
    private ?string $campaign_id;

    const METHODNAME = 'GetProductInfo';

    const MAP_PRODUCT_TYPE = [
        "electricity" => [
            "product_code" => "ELE_LWFZR_0002",
            "campaign_id" => "C-00078334"
        ],
        "gas" => [
            "product_code" => "GAS_LWFZR_0001",
            "campaign_id" => "C-00078334"
        ],
    ];

    /**
     * @var string $option
     */
    public function __construct(private string $option)
    {
        parent::__construct();
        if(in_array($this->option, array_keys(self::MAP_PRODUCT_TYPE))){
            $type = self::MAP_PRODUCT_TYPE[$this->option];
            $this->product_code = $type['product_code'];
            $this->campaign_id = $type['campaign_id'];
        } 
    }

    /**
     * Get product info from origin 
     * 
     * @return array
     * 
     */
    public function fetch(){
        if(empty($this->product_code) || empty($this->campaign_id))
            return false;
        $url = config('origin.baseurl') . config('origin.endpoints.get_product_info');
        $params = [
            '$filter' => sprintf("CampaignID eq '%s' and ProductCode eq '%s'", $this->campaign_id, $this->product_code)
        ];

        $responseData = $this->getApi($url, $params, self::METHODNAME);

        if(empty($responseData))
            return false;

        $productInfos = [];    
        foreach($responseData['results'] as $info){
            $productInfos[] = [
                'productID' => $info['ProductID'],
                'description' => $info['Description']    
            ];
        }

        $formattedData = [
            'productInfos' => $productInfos,
        ];

        return $formattedData;
    }

}
