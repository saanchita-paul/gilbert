<?php

namespace App\Modules\PropertyMe\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PropertyMe\Services\BasePropertyMeAPI;

class FetchContactAPI extends BasePropertyMeAPI
{
    private array $lots = [];

    private array $contacts = [];

    private array $tenancies = [];

    private array $lotMembers = [];


    public function __construct(private string $refreshToken)
    {
    }


    /**
     * @return array
     */
    public function getLots(): array
    {
        return $this->lots;
    }

    /**
     * @throws Exception
     */
    public function fetchLots(): static
    {
        $url = config('property_me.api_root_url') . config('property_me.get_lots_url');
        $query = "?Timestamp=" . $this->getTimestampTicks(-100);

        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => $this->getAccessToken($this->refreshToken),
            ])->get($url . $query);

            $this->lots = json_decode($response->body(), true);
            Log::info('PropertyMe: Fetch Lots: ', [$this->lots]);
        } catch (\Exception $exception) {
            \Log::error("[FetchContactAPI:fetchLots] " . $exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }

        return $this;
    }

    public function fetchTenancies(): static
    {
        $url = config('property_me.api_root_url') . config('property_me.get_tenancies_url');
        $query = "?Timestamp=" . $this->getTimestampTicks(-100);

        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => $this->getAccessToken($this->refreshToken),
            ])->get($url . $query);

            $this->tenancies = json_decode($response->body(), true);
//            Log::info('PropertyMe: Fetch Tenancies: ', [$this->tenancies]);
        } catch (\Exception $exception) {
            \Log::error("[FetchContactAPI:fetchTenancies] " . $exception->getMessage());
            \Log::error($exception->getTraceAsString());
        }

        return $this;
    }

    public function fetchTLotMembers(string $lotId): ?array
    {
        $url = config('property_me.api_root_url')
            . config('property_me.get_lots_url')
            . "/"
            . $lotId
            . "/"
            . "members";

        $query = "?Timestamp=" . $this->getTimestampTicks(-100);

        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => $this->getAccessToken($this->refreshToken),
            ])->get($url . $query);

            return json_decode($response->body(), true);
//            Log::info('PropertyMe: Fetch Tenancies: ', [$this->tenancies]);
        } catch (\Exception $exception) {
            \Log::error("[FetchContactAPI:fetchTLotMembers] " . $exception->getMessage());
            \Log::error($exception->getTraceAsString());

            return  null;
        }

    }


    /**F
     * @throws Exception
     */
    public function fetchContacts(): static
    {
        $url = config('property_me.api_root_url') . config('property_me.get_contact_url');
        $query = "?Timestamp=" . $this->getTimestampTicks();
        dump( $query);

        try {
            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => $this->getAccessToken($this->refreshToken),
            ])->get($url . $query);

            $this->contacts = json_decode($response->body(), true);
//            Log::info('PropertyMe: Fetch Contacts: ', [$this->contacts]);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getContacts(): array
    {
        return $this->contacts;
    }

    /**
     * @return array
     */
    public function getTenancies(): array
    {
        return $this->tenancies;
    }

    /**
     * @return array
     */
    public function getLotMembers(): array
    {
        return $this->lotMembers;
    }

}
