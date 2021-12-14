<?php


namespace App\Services\Logger;


use App\Models\APILog;
use Illuminate\Support\Str;

class LogSalesService
{
    /*
     * @return APILog
     */
    public function createSalesLog(string $url, string $type, string $method, string $requestBody, string $requestHeader):APILog
    {
        $apiLog = new APILog();
        $apiLog->key = Str::uuid()->toString();
        $apiLog->type = $type;
        $apiLog->url = $url;
        $apiLog->method = $method;
        $apiLog->request_body = $requestBody;
        $apiLog->request_header = $requestHeader;
        $apiLog->save();
        return $apiLog;
    }

    public function updateSalesLog(string $key, string $responseBody, string $responseHeader, int $responseStatus)
    {
        $apiLog = APILog::query()->where('key', $key)->first();
        $apiLog->response_body = $responseBody;
        $apiLog->response_header = $responseHeader;
        $apiLog->response_status = $responseStatus;
        $apiLog->save();
        return $apiLog;
    }
}
