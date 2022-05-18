<?php

namespace Origin\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\APILog;

class BaseOriginAPI
{
    const CODE_REJECT = 4; 

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
            Log::info(sprintf('Origin GET:%s - Attempting with request data:', $methodName), $params);
            $url = APILog::setLoggerQuery($url, $this->toSnakeCase('Origin'.$methodName), false);

            foreach($params as $key => $value){
                $url = $url . '&' . $key . '=' . $value;
            }

            $headers = [
                "Accept" => "application/json",
                "Authorization" => $this->basicAuth,
            ];

            $response = Http::withOptions([
                "headers" => $headers,
                // "query" => $params,
            ])->get($url);

            $response->throw();
            
            $responseData = json_decode($response->body(), true);

            Log::info(sprintf('Origin GET:%s - Success with response data:', $methodName), $responseData);

            return $responseData['d'];
        } catch (\Illuminate\Http\Client\RequestException $exception){
            $statusCode = $exception->response->status();
            $responseJson = $exception->response->json();
            $errorCode = $responseJson['error'] ? $responseJson['error']['code'] : '';
            $errorMessage = $responseJson['error'] ? $responseJson['error']['message']['value'] : $exception->response->body();

            if($statusCode == 400){
                throw new \Exception(sprintf('Origin GET:%s - FAILED [%s](%s)', $methodName, $errorCode, $errorMessage), self::CODE_REJECT);
            }
            else {
                throw new \Exception(sprintf('Origin GET:%s - FAILED (%s)', $methodName, $errorMessage));
            }
        }
        catch (Exception $exception) {
            throw new \Exception(sprintf('Origin GET:%s - FAILED (%s)', $methodName, $exception->getMessage()));
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
            Log::info(sprintf('Origin POST:%s - Attempting with request data:', $methodName), $body);
            $url = APILog::setLoggerQuery($url, $this->toSnakeCase('Origin'.$methodName), false);
            
            $this->getAccessToken();

            $headers = [
                "X-CSRF-Token" => $this->accessToken,
                "Authorization" => $this->basicAuth,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ];

            $response = Http::withOptions([
                'headers' => $headers,
                'cookies' => $this->cookiejar
            ])
            ->withBody(json_encode($body), "application/json")
            ->post($url);

            $response->throw();

            $responseData = json_decode($response->getBody(), true);

            Log::info(sprintf('Origin POST:%s - Success with response data:', $methodName), $responseData);

            return $responseData['d'];
        } catch (\Illuminate\Http\Client\RequestException $exception){
            $statusCode = $exception->response->status();
            $responseJson = $exception->response->json();
            $errorCode = $responseJson['error'] ? $responseJson['error']['code'] : '';
            $errorMessage = $responseJson['error'] ? $responseJson['error']['message']['value'] : $exception->response->body();
            
            if($statusCode == 400){
                throw new \Exception(sprintf('Origin POST:%s - FAILED [%s](%s)', $methodName, $errorCode, $errorMessage), self::CODE_REJECT);
            }
            else {
                throw new \Exception(sprintf('Origin POST:%s - FAILED (%s)', $methodName, $errorMessage));
            }
        } catch (Exception $exception) {
            throw new \Exception(sprintf('Origin POST:%s - FAILED (%s)', $methodName, $exception->getMessage()));
        }
    }

    protected function toSnakeCase($string, $seperator = '_'){
        return strtolower(preg_replace('/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', $seperator, $string));
    }
}
