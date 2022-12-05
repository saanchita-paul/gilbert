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

        // ? todo mapping array key with adding prefix shipping

        return [
            'connection_application_id' => $this->application->id,
            'is_shipping_same' => true,
            'shipping_unit_number' => $data['unit_number'],
            'shipping_street_number' => $data['street_number'],
            'shipping_street_name_only' => $data['street_name_only'],
            'shipping_address_text' => $data['address_text'],
            'shipping_street_address' => $data['street_address'],
            'shipping_street_type' => $data['street_type'],
            'shipping_city' => $data['city'],
            'shipping_postcode' => $data['postcode'],
            'shipping_state' => $data['state'],
        ];
    }
}
