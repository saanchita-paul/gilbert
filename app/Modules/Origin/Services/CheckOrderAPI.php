<?php

namespace Origin\Services;

use Carbon\Carbon;

class CheckOrderAPI extends BaseOriginAPI
{
    const METHODNAME = 'CheckOrder';

    const MAP_DIVISION_TYPE = [
        '01' => 'electricity',
        '02' => 'gas',
        '03' => 'hot water'
    ];

    /**
     * @var string $partnerReferenceNumber
     */
    public function __construct(private string $partnerReferenceNumber)
    {
        parent::__construct();
    }

    /**
     * Get order info from origin 
     * 
     * @return array
     * 
     */
    public function fetch(){
        if(empty($this->partnerReferenceNumber))
            throw new \Exception(sprintf('Origin GET:%s - FAILED (Missing partner reference number)', self::METHODNAME));
        $url = config('origin.baseurl') . config('origin.endpoints.check_order');
        $params = [
            '$filter' => sprintf("PartnerReferenceNumber eq '%s'", $this->partnerReferenceNumber)
        ];

        $responseData = $this->getApi($url, $params, self::METHODNAME);

        if(empty($responseData))
            throw new \Exception(sprintf('Origin GET:%s - FAILED (Empty response from Origin)', self::METHODNAME));

        $orderInfo = $responseData['results'][0];

        $formattedData = [
            'orderHeaderID' => $orderInfo['OrderHeaderID'],
            'orderItemID' => $orderInfo['OrderItemID'],
            'orderItemType' => $orderInfo['OrderItemType'],
            'referenceNumber' => $orderInfo['ReferenceNumber'],
            'fuelType' => self::MAP_DIVISION_TYPE[$orderInfo['DivisionID']],
            'orderStatus' => $orderInfo['Status'],
            'statusReason' => $orderInfo['StatusReasonDescription'],
            'submissionDate' => Carbon::createFromTimestampMs($orderInfo['SubmissionDate']),
            'expectedCompletionDate' => Carbon::createFromTimestampMs($orderInfo['ExpectedCompletionDate']),
            'requestedDate' => Carbon::createFromTimestampMs($orderInfo['RequestedCompletionDate']),
        ];
        return $formattedData;
    }

}
