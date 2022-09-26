<?php

namespace Powershop\Services;

use Exception;

use Illuminate\Support\Facades\Http;

class PingService
{
    public function __construct()
    {
    }

    /**
     * Ping powershop service to determine availability
     * 
     * @throws \Exception
     */
    public function ping()
    {
        try {
            $url = config('powershop.base_url').config('powershop.ping_url');
            $response = Http::withHeaders([
                'content-type' => 'application/json',
                'accept' => 'application/json',
                'Authorization' => config('powershop.secret_token'),
            ])
            ->get($url)
            ->throw();

            return json_decode($response->body(), true);
        } catch (Exception $exception) {
            \Log::error('Powershop::Ping FAIL (see context)', [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);
            throw $exception;
        }
    }
}
