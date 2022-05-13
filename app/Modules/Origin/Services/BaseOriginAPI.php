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
    private $cookiejar = null;

    protected function __construct()
    {
        $this->basicAuth = AuthService::getBasicAuth();
    }

    protected function getAccessToken(){
        if(empty($this->accessToken)){
            $array = AuthService::getXCSRFToken();
            $this->accessToken = $array['token'];
            $this->cookiejar = $array['cookies'];
        }
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
     * Run POST Http Client 
     * 
     * @param string $url
     * @param array $body
     * @param string $methodName
     * 
     * @return array
     * 
     * @throws exception
     */
    protected function postApi(string $url, array $body = [], string $methodName = 'postOriginAPI'){
        try {
            Log::info(sprintf('Origin POST:%s - Attempting with request data:', $methodName));
            Log::info($body);
            
            $this->getAccessToken();

            $headers = [
                "X-CSRF-Token" => $this->accessToken,
                "Authorization" => $this->basicAuth,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ];

            $response = Http::withOptions([
                'headers' => $headers,
                'cookies' => $this->cookiejar,
            ])
            ->withBody(json_encode($body), "application/json")
            ->post($url);

            $response->throw();

            $responseData = json_decode($response->getBody(), true);

            Log::info(sprintf('Origin POST:%s - Success with response data:', $methodName));
            Log::info($responseData);

            return $responseData['d'];
        } catch (\Illuminate\Http\Client\RequestException $e){
            $responseJson = $e->response->json();
            $errorMessage = $responseJson['error'] ? $responseJson['error']['message']['value'] : $e->response->body();
            Log::error(sprintf('Origin POST:%s - FAILED (%s)', $methodName, $errorMessage));
        } catch (Exception $e) {
            Log::error(sprintf('Origin POST:%s - FAILED (%s)', $methodName, $e->getMessage()));
        }
    }
}
