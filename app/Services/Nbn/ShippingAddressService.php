<?php

namespace App\Services\Nbn;

use App\Models\ConnectionApplication;
use App\Services\Agency\HubspotContactService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ShippingAddressService
{
    private array|Collection|ConnectionApplication|Model $application;

    public function __construct(int $id)
    {
        $this->application = ConnectionApplication::findOrFail($id);
    }

    public function handle()
    {
        try {
            $data = $this->mapData();

            if (is_null($this->application->internetServiceInfo)) {
                $this->application->internetServiceInfo()->create($data);
            } else {
                $this->application->internetServiceInfo()->update($data);
            }
            \Log::info('Shipping address created successfully.');
            return $this->application->internetServiceInfo;
        } catch (\Exception $e) {
            \Log::error('Shipping Address Handler Error (view context)', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function mapData()
    {
        $data = $this->application->only(
            'unit_number',
            'street_number',
            'street_name_only',
            'address_text',
            'street_address',
            'street_type',
            'city',
            'postcode',
            'state'
        );
        $data['connection_application_id'] = $this->application->id;
        $data['is_shipping_same'] = true;

        return $data;
    }
}
