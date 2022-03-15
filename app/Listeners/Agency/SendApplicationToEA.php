<?php

namespace App\Listeners\Agency;

use App\Events\Agency\SubmitApplicationEvent;
use App\Models\ConnectionApplication;
use App\Services\Address\AddressModel;
use App\Services\Address\GBGServices;
use App\Services\Agency\HubspotContactService;
use App\Services\Sales\PostSalesService;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendApplicationToEA implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    private function validateAddress($applicationId) : bool 
    {
        $addressModel = new AddressModel(connection_application_id: $applicationId);
        $gbgService = new GBGServices($addressModel);
        $address = $gbgService->findAddressByText();
        if ($address->getIsAddressComplete()) 
        {
            return true;
        }else
        {
            return false;
        }
    }

    /**
     * Handle the event.
     *
     * @param SubmitApplicationEvent $event
     * @return void
     */
    public function handle(SubmitApplicationEvent $event)
    {
        if (!$this->validateAddress($event->applicationId)) 
        {
            info("Send Application To EA: Address is not complete");
            return;
        }

        $submitType = $event->submitType;
        $application = ConnectionApplication::with('connectionServices')->where('id', $event->applicationId)->firstOrFail();

        $saleApiOn = config('ea.is_sales_api_on');
        if ($saleApiOn === "1" && $submitType === 'energy' && $this->isValidForSalesApi($application) ) {
            $postEaService = new PostSalesService($event->applicationId);
            $postEaService->postToEa();
            ConnectionApplication::where('id' , $event->applicationId)->update(['status' => ConnectionApplication::STATUS_SUBMITTED]);
            $hubspotService = new HubspotContactService($event->applicationId);
            $hubspotService->update();
        } else {
            info("Skipping EA Submit", [
                'EA_SALES_API_ON' => $saleApiOn,
                'submit_type' => $submitType,
                'is_services_valid' => $this->isValidForSalesApi($application)
            ]);
        }


    }

    /**
     * @param $application
     * @return bool
     */
    private function isValidForSalesApi($application): bool
    {
        foreach ($application->connectionServices as $service) {
            if ($service->provider_name === 'ea' && is_null($service->lead_reference)) {
                return true;
            }
        }

        return  false;
    }
}
