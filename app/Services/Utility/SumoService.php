<?php

namespace App\Services\Utility;

use App\Models\APILog;
use App\Models\ConnectionApplication;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SumoService
{
    private array|Collection|ConnectionApplication|Model $application;

    public function __construct(int $id)
    {
        $this->application = ConnectionApplication::findOrFail($id);
        $this->application->load(['identification', 'connectionServices']);
    }

    /**
     * Store customer data in Sumo
     *
     * @throws \Exception
     */
    public function storeCustomerData()
    {
        $url = config('sumo.base_url').config('sumo.store_customer_data_url');

        $response = Http::put( $url, [
            "customerDataDto" => $this->getCustomerData()
        ]);

        dd(json_decode($response->body(), true));
    }

    private function getCustomerData()
    {
        return [

        ];
    }
}
