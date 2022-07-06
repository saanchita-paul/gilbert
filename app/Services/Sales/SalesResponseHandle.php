<?php

namespace App\Services\Sales;

use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\RejectionReason;
use Exception;
use Illuminate\Database\Eloquent\Builder;

trait SalesResponseHandle
{
    /**
     * Handling response
     *
     * @param $quotes
     * @param $leadId
     *
     * @throws Exception
     */
    private function handleResponse($quotes, $leadId, $conncetionServiceData = [])
    {
        foreach ($quotes as $quote) {

            $updateData = array_merge([
                'status' => SalesStatusMapper::EAToGilbert($quote->status)
            ], $conncetionServiceData);
            $reasons = data_get($quote, 'rejectionReasons') ?? [];

            // if (sizeof($reasons) > 0) {
            //     $this->resetIsRunningSubmission($leadId);
            // }


            if ($quote->fuel === 'GAS') {
                $this->updateService($leadId, 'gas', $updateData);
                $this->updateExtraDetails($leadId, 'gas', $updateData['status']);
                $this->saveRejectionReasons($reasons, $leadId, 'gas');
                $this->updateQuoteReference($leadId, 'gas', $quote->id);
            }
            if ($quote->fuel === 'ELE') {
                $this->updateService($leadId, 'power', $updateData);
                $this->updateExtraDetails($leadId, 'power', $updateData['status']);
                $this->saveRejectionReasons($reasons, $leadId, 'power');
                $this->updateQuoteReference($leadId, 'power', $quote->id);
            }
//            if (sizeof($reasons) > 0) {
                $this->resetIsRunningSubmission($leadId);
//            }

        }
    }

    /**
     * Saving Rejection reasons
     *
     * @param array $data
     * @param int $leadId
     * @param string $serviceType
     */
    private function saveRejectionReasons(array $data, int $leadId, string $serviceType)
    {
        $reasons = [];

        /** @var ConnectionService $service */
        $service = $this->getServiceBuilder($leadId, $serviceType)->first();

        if ($service) {
            $service->reasons()->delete();
        }

        foreach ($data as $reason) {
            $reasons[] = [
                'connection_application_id' => $leadId,
                'connection_service_id' => $service?->id,
                'service_type' => $serviceType,
                'reason_code' => data_get($reason, 'code'),
                'reason_text' => data_get($reason, 'detail'),
            ];
        }
        RejectionReason::query()->insert($reasons);

        //set lead referece null
        if(!empty($data)) {
            $this->updateService($leadId, $serviceType, ['lead_reference' => null]);
        }
    }


    /**
     * get ConnectionService builder
     *
     * @param int $leadId
     * @param $serviceType
     *
     * @return Builder
     */
    private function getServiceBuilder(int $leadId, $serviceType): Builder
    {
        return ConnectionService::query()
            ->where('connection_application_id', $leadId)
            ->where('service_type', $serviceType);
    }

    /**
     * Updating ConnectionService data
     *
     * @param $leadId
     * @param $serviceType
     * @param $updateData
     */
    public function updateService($leadId, $serviceType, $updateData)
    {
        $this->getServiceBuilder($leadId, $serviceType)->update($updateData);
    }

    public function updateExtraDetails($leadId, $serviceType, $status)
    {
        if($status == ConnectionService::STATUS_ACCEPTED) {
            $this->getServiceBuilder($leadId, $serviceType)->update(['accepted_at' => now()]);
        } elseif ($status == ConnectionService::STATUS_REJECTED) {
            $this->getServiceBuilder($leadId, $serviceType)->update(['rejected_at' => now()]);
        }
    }

    /**
     * Updating Quote Reference
     *
     * @param $leadId
     * @param $serviceType
     * @param $quoteReference
     */
    private function updateQuoteReference($leadId, $serviceType, $quoteReference)
    {
        /** @var ConnectionService $service */
        $service = $this->getServiceBuilder($leadId, $serviceType)->first();
        if($service){
            $service->quote_reference = $quoteReference;
            $service->save();
        }
    }

    /**
     * Updating is_running_submission
     *
     * @param $leadId
     */
    private function resetIsRunningSubmission($leadId)
    {
        $application = ConnectionApplication::where('id', $leadId)->first();
        $application->update(['is_running_submission' => 0]);
    }
}
