<?php

namespace Origin\Services;

use Carbon\Carbon;
use App\Models\OriginPlan;

class StoreProductInfoAPI extends BaseOriginAPI
{
    const METHODNAME = 'StoreProductInfo';

    /**
     * @var string $fuel
     * @var string $product
     */
    public function __construct(private string $campaign_id, private string $product_code)
    {
        parent::__construct();
    }

    /**
     * Get product info from origin and store to database
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

        if(empty($responseData) || empty($responseData['results'])){
            $originPlan = OriginPlan::where([
                ['product_code', $this->product_code],
                ['campaign_id', $this->campaign_id] 
            ])->first();

            if(!$originPlan){
                //
            }
            else{
                $originPlan->status = 'INACTIVE';
            }

            return false;
        }

        $productInfo = $responseData['results'][0];

        $originPlan = OriginPlan::firstOrNew(
            ['product_code' => $productInfo['ProductCode']],
            ['campaign_id' => $productInfo['CampaignID']],
        );

        $originPlan->product_id = $productInfo['ProductID'];
        $originPlan->description = $productInfo['Description'];
        $originPlan->division_id = $productInfo['DivisionID'];
        $originPlan->customer_type_id = $productInfo['CustomerTypeID'];
        $originPlan->status = 'ACTIVE';
        $originPlan->save();

        return $originPlan->toArray();
    }

}
