<?php

namespace PropertyMe\Services;

use App\Models\Office;
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
        $version = config('property_me.client_version');
        $queries = [
            'response_type' => 'code',
            'state' => json_encode($states),
//            'redirect_uri' => url('/home/callback'),
            'redirect_uri' => config('property_me.o_auth_callback_uri'),
            'client_id' => config("property_me.client_id_${version}"),
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
                "Authorization" => static::getDynamicBasicAuth($refreshToken),
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
        $version = config('property_me.client_version', "v2");
        $id = config("property_me.client_id_${version}");
        $secret = config("property_me.client_secret_${version}");

        return "Basic " . base64_encode($id . ":" . $secret);
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
                'redirect_uri' => config('property_me.o_auth_callback_uri'),
                'code' => $authCode
            ]);
        return json_decode($response->body())?->refresh_token;
    }

    /**
     * Getting dynamic Basic Auth based property_me_client_version from Office
     *
     * @param string $refreshToken
     *
     * @return string
     */
    public static function getDynamicBasicAuth(string $refreshToken): string
    {
        $version = Office::where('property_me_refresh_token', $refreshToken)->first()?->property_me_client_version;
        $id = config("property_me.client_id_${version}");
        $secret = config("property_me.client_secret_${version}");

        return "Basic " . base64_encode($id . ":" . $secret);
    }
}
