<?php

namespace App\Services\Agency;

use App\Interfaces\TokenManagerInterface;
class SimpleTokenService{

    private TokenManagerInterface $tokenManager;

    public function __construct()
    {
        $this->tokenManager = new CacheTokenService();
    }

    public function getAccessToken() : int
    {
        return $this->tokenManager->getAccessToken();
    }

    public function verifyAccessToken(string $token) : bool
    {
        return $this->tokenManager->verifyAccessToken($token);
    }

}
