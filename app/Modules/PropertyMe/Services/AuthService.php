<?php

namespace PropertyMe\Services;

use Exception;
use Illuminate\Support\Facades\Http;

/**
 *
 */
class AuthService
{
    /**
     * @param array $states
     * @return string
     */
    public static function getOAuthUrl(array $states = []): string
    {
        $queries = [
            'response_type' => 'code',
            'state' => json_encode($states),
//            'redirect_uri' => url('/home/callback'),
            'redirect_uri' => config('property_me.o_auth_callback_uri'),
            'client_id' => config('property_me.client_id'),
            'scope' => 'activity:read communication:read contact:read property:read transaction:read offline_access'
        ];

        return config('property_me.o_auth_url')
            . "?"
            . http_build_query($queries);
    }


    /**
     * @throws Exception
     */
    public static function getTokenFromRefreshCode(string $refreshToken)
    {
        $url = config('property_me.token_url');
        $data = ["grant_type" => "refresh_token", "refresh_token" => $refreshToken];

        try {
            $response = Http::asForm()->withHeaders([
                "Authorization" => static::getBasicAuth(),
            ])->post($url, $data);
            return data_get(json_decode($response->body(), true), 'access_token');
        } catch (Exception $exception) {
            throw new Exception("[AuthService:refreshToken] " . $exception->getMessage());
        }
    }

    /**
     * @return string
     */
    public static function getBasicAuth(): string
    {
        return "Basic " . base64_encode(config('property_me.client_id') . ":" . config('property_me.client_secret'));
    }


    /**
     * @param string $authCode
     * @return string|null
     */
    public static function getRefreshTokenFromAuthCode(string $authCode): ?string
    {
        $response = Http::withHeaders(["Authorization" => static::getBasicAuth()])
            ->asForm()
            ->post(config('property_me.token_url'), [
                'grant_type' => 'authorization_code',
                'redirect_uri' => url('/home/callback'),
                'code' => $authCode
            ]);
        return json_decode($response->body())?->refresh_token;
    }
}
