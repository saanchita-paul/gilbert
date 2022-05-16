<?php

namespace Origin\Services;

class GetProductInfoAPI extends BaseOriginAPI
{
    private ?string $product_code;
    private ?string $campaign_id;

    const METHODNAME = 'GetProductInfo';

    const MAP_PRODUCT_TYPE = [
        "electricity" => [
            "basic" => [
                "product_code" => "ELE_FLEXI_0002",
                "campaign_id" => "C-00074893"
            ],
            "advantage" => [
                "product_code" => "ELE_LWFZR_0002",
                "campaign_id" => "C-00078334"
            ],
            "solarboost" => [
                "product_code" => "ELE_DSAVE_0002",
                "campaign_id" => "C-00076271"
            ],
            "businessgo" => [
                "product_code" => "ELE_LWFZB_0002",
                "campaign_id" => "C-00074894"
            ],
        ],
        "gas" => [
            "basic" => [
                "product_code" => "GAS_FLEXI_0001",
                "campaign_id" => "C-00074893"
            ],
            "advantage" => [
                "product_code" => "GAS_LWFZR_0001",
                "campaign_id" => "C-00078334"
            ],
            "businessgo" => [
                "product_code" => "GAS_LWFZB_0001",
                "campaign_id" => "C-00074894"
            ],
        ],
    ];

    /**
     * @var string $fuel
     * @var string $product
     */
    public function __construct(private string $fuel, private string $product)
    {
        parent::__construct();
        if(in_array($this->fuel, array_keys(self::MAP_PRODUCT_TYPE)) && in_array($this->product, array_keys(self::MAP_PRODUCT_TYPE[$this->fuel]))){
            $type = self::MAP_PRODUCT_TYPE[$this->fuel][$this->product];
            $this->product_code = $type['product_code'];
            $this->campaign_id = $type['campaign_id'];
        } 
    }

    /**
     * Get product info from origin 
     * 
     * @return array
     * 
     * @throws exception
     */
    public function fetch(){
        if(empty($this->product_code) || empty($this->campaign_id))
            throw new \Exception(sprintf('Origin GET:%s - FAILED (Missing product code/campaign_id)', self::METHODNAME));
        $url = config('origin.baseurl') . config('origin.endpoints.get_product_info');
        $params = [
            '$filter' => sprintf("CampaignID eq '%s' and ProductCode eq '%s'", $this->campaign_id, $this->product_code)
        ];

        $responseData = $this->getApi($url, $params, self::METHODNAME);

        if(empty($responseData))
            throw new \Exception(sprintf('Origin GET:%s - FAILED (Empty response from Origin)', self::METHODNAME));

        $productInfos = [];    
        foreach($responseData['results'] as $info){
            $productInfos[] = [
                'productID' => $info['ProductID'],
                'description' => $info['Description'],
                'divisionID' => $info['DivisionID'],
                'customerTypeID' => $info['CustomerTypeID'], 
            ];
        }

        if(count($productInfos) > 1){
            $formattedData = [
                'productInfo' => $productInfos,
            ];
        }
        else{
            $formattedData = [
                'productInfo' => $productInfos[0],
            ];
        }
        return $formattedData;
    }

}
