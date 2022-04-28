<?php

namespace Origin\Services;

use Exception;
use Illuminate\Support\Facades\Http;

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
     * @return string|null
     */
    public static function getXCSRFToken(): ?string
    {
        $url = config('origin.baseurl') . config('origin.endpoints.get_xcsrf_token');
        $response = Http::withHeaders([
                "Authorization" => static::getBasicAuth(),
                "Accept" => "application/json",
                "X-CSRF-Token" => "Fetch",
            ])
            ->get($url);

        return $response->header('x-csrf-token') ?? null;
    }

}
