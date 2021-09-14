<?php

namespace App\Services\Logger;

use App\Models\APILog;
use Illuminate\Http\Client\Events\ResponseReceived;
use \Illuminate\Http\Client\Request;

/**
 *
 */
class HTTPLoggerService
{
    /**
     *
     */
    const QUERY_LOGGER_TYPE = 'hd_logger_type';
    /**
     *
     */
    const QUERY_LOGGER_KEY = 'hd_logger_key';

    /**
     * saving log
     *
     * @param Request $request
     */
    public function create(Request $request)
    {
        $key = $this->getQuery($request->url(), self::QUERY_LOGGER_KEY);
        if ($key) {
            $data =  [
                'created_at' => now()->toDateTimeString(),
                'type' => $this->getQuery($request->url(), self::QUERY_LOGGER_TYPE),
                'key' => $key,
                'url' => $request->url(),
                'method' => $request->method(),
                'request_header' => json_encode($request->headers()),
                'request_body' => empty($request->body()) ? null : $request->body(),
            ];
            APILog::query()->insert($data);
        }
    }

    /**
     * updating log
     *
     * @param ResponseReceived $received
     */
    public function update(ResponseReceived $received)
    {
        $key = $this->getQuery($received->request->url(), self::QUERY_LOGGER_KEY);

        if ($key) {
            $body = json_decode($received->response->body(), true);
            $data =  [
                'response_header' => json_encode($received->response->headers()),
                'response_body' => $body ?: json_encode($received->response->body()),
                'response_status' => $received->response->status(),
            ];

            APILog::query()->where('key', $key)->update($data);
        }
    }

    /**
     * getting query from url string
     *
     * @param string $url
     * @param string $key
     *
     * @return string|null
     */
    private function getQuery(string $url, string $key): ?string
    {
        $parts = parse_url($url);
        parse_str(optional($parts)['query'], $query);
        return optional($query)[$key];
    }

}
