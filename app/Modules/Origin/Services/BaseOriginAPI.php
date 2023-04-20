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
    protected function getApi(string $url, array $params = [], string $methodName = 'getOriginAPI', $isSkipLog = false) {
        try {
            Log::info(sprintf('Origin GET:%s - Attempting with request data:', $methodName), $params);

            $options = [
                "headers" => [
                    "Accept" => "application/json",
                    "Authorization" => $this->basicAuth,
                ],
                "query" => $params,
            ];

            if (!$isSkipLog) {
                $url = APILog::setLoggerQuery($url, $this->toSnakeCase('Origin' . $methodName), false);

                foreach ($params as $key => $value) {
                    $url = $url . '&' . $key . '=' . $value;
                }

                unset($options['query']);
            }

            $response = Http::withOptions($options)
                        ->get($url)
                        ->throw();

            $responseData = json_decode($response->body(), true);

            Log::info(sprintf('Origin GET:%s - Success with response data:', $methodName), $responseData);

            return $responseData['d'];
        } catch (\Illuminate\Http\Client\RequestException $exception) {
            $statusCode = $exception->response->status();
            $responseJson = $exception->response->json();
            $errorCode = $responseJson['error']['code'] ?? 'ORIGIN_ERROR';
            $errorMessage = $responseJson['error']['message']['value'] ?? $exception->response->body();

            if ($statusCode == 400) {
                throw new \Exception(sprintf('Origin GET:%s - FAILED WITH STATUS CODE %s [%s](%s)', $methodName, $statusCode, $errorCode, $errorMessage), self::CODE_REJECT);
            } else {
                throw new \Exception(sprintf('Origin GET:%s - FAILED STATUS CODE %s (%s)', $methodName, $statusCode, $errorMessage));
            }
        } catch (Exception $exception) {
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
    protected function postApi(string $url, array $body = [], string $methodName = 'postOriginAPI', $isSkipLog = false) 
    {
        Log::info(sprintf('Origin POST:%s - Attempting with request data:', $methodName), $body);

        $options = [
            'headers' => [
                "Authorization" => $this->basicAuth,
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ]
        ];

        if (!$isSkipLog) {
            $url = APILog::setLoggerQuery($url, $this->toSnakeCase('Origin' . $methodName), false);
        }

        $response = Http::withOptions($options)
                    ->withBody(json_encode($body), "application/json")
                    ->post($url)
                    ->throw();

        $responseData = json_decode($response->getBody(), true);

        Log::info(sprintf('Origin POST:%s - Success with response data:', $methodName), $responseData);

        return $responseData['d'];
    }

    protected function toSnakeCase($string, $seperator = '_')
    {
        return strtolower(preg_replace('/(?<=\d)(?=[A-Za-z])|(?<=[A-Za-z])(?=\d)|(?<=[a-z])(?=[A-Z])/', $seperator, $string));
    }
}
