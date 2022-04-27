<?php

namespace Origin\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BaseOriginAPI
{

    /**
     * @var string|null $basicAuth
     * @var string|null $accessToken
     */
    private ?string $basicAuth = null;
    private ?string $accessToken = null;

    protected function __construct()
    {
        $this->basicAuth = AuthService::getBasicAuth();
    }

    /**
     * Run GET Http Client 
     * 
     * @param string $url
     * @param array $params
     * @param string $methodName
     * 
     * @return array
     * 
     * @throws exception
     */
    protected function getApi(string $url, array $params = [], string $methodName = 'getOriginAPI'){
        try {
            Log::info(sprintf('Origin GET:%s - Attempting with request data:', $methodName));
            Log::info($params);

            $response = Http::withHeaders([
                "Accept" => "application/json",
                "Authorization" => $this->basicAuth,
            ])->get($url, $params);

            $response->throw();
            
            $responseData = json_decode($response->body(), true);

            Log::info(sprintf('Origin GET:%s - Success with response data:', $methodName));
            Log::info($responseData);

            return $responseData['d'];
        } catch (Exception $exception) {
            Log::error(sprintf('Origin GET:%s - FAILED (%s)', $methodName, $exception->getMessage()));
            Log::error($exception->getTraceAsString());
        }

        return [];
    }

    /**
     *
     */
    protected function getTimestampTicks(int $day = null): string
    {
        $noOfDays = $day ?? (int) config('property_me.no_of_days');
        return (now()->addDays(- $noOfDays)->timestamp * 10000000) + 621355968000000000;
    }
}
