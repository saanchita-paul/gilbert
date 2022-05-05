<?php

namespace Origin\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Cookie\CookieJar;

/**
 *
 */
class AuthService
{
    /**
     * @return string
     */
    public static function getBasicAuth(): string
    {
        $username = config("origin.username");
        $password = config("origin.password");

        return "Basic " . base64_encode($username . ":" . $password);
    }

    /**
     * @return array
     */
    public static function getXCSRFToken(): ?array
    {
        $url = config('origin.baseurl') . config('origin.endpoints.get_xcsrf_token');
        $response = Http::withHeaders([
                "Authorization" => static::getBasicAuth(),
                "Accept" => "application/json",
                "X-CSRF-Token" => "Fetch",
            ])
            ->get($url);


        $response = [
            'token' => $response->header('x-csrf-token') ?? null,
            'cookies' => $response->cookies() ?? null,
        ];

        return $response;
    }

}
