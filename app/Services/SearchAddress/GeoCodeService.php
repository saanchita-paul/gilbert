<?php

namespace App\Services\SearchAddress;

use Illuminate\Support\Facades\Http;
use App\Services\SearchAddress\GeocodeAddress;

class GeoCodeService
{
    public static function getAddressFromGeoCode(string $address)
    {
        try {
            $response = Http::withHeaders([
                "content-type" => "application/json",
                "Accept" => "application/json",
            ])
                ->get(config('geocode.baseUrl'), [
                        "key" => config('geocode.apiKey'),
                        "address" => $address
                    ]
                );
            return $response;
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }
}
