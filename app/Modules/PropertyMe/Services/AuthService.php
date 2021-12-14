<?php

namespace PropertyMe\Services;

class AuthService
{
    public static function getOAuthUrl(): string
    {
        $queries = [
            'response_type' => 'code',
            'state' => 'hood',
            'redirect_uri' => url('/home/callback'),
            'client_id' => config('property_me.client_id'),
            'scope' => 'activity:read communication:read contact:read property:read transaction:read offline_access'
        ];

        return config('property_me.o_auth_url')
            . "?"
            . http_build_query($queries);
    }
}
