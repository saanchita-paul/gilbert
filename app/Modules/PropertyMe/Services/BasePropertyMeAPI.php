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
}
