<?php

namespace PropertyMe\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use JetBrains\PhpStorm\ArrayShape;

class BasePropertyMeAPI
{

    /**
     * @var string|null $accessToken
     */
    private ?string $accessToken = null;



    /**
     * @throws Exception
     */
    protected function getAccessToken(string $refreshToken): string
    {
        if (!$this->accessToken) {
            $this->accessToken = AuthService::getTokenFromRefreshCode($refreshToken);
        }
        return "Bearer " . $this->accessToken;
    }


    /**
     * @throws Exception
     */
    protected function getTimestamp(int $day = null): string
    {
        $noOfDays = $day ?? config('property_me.no_of_days');
        return now()->addDays($noOfDays)->format('U');
    }
}
