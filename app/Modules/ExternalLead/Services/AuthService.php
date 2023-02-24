<?php

namespace ExternalLead\Services;

use Exception;
use App\Models\ExternalSource;
use Illuminate\Support\Facades\Hash;
use App\Services\AuthService\JwtAuthService;

class AuthService
{
    /**
     * Generate token.
     *
     * @param array $leadInfo
     * @return array
     * @throws Exception
     */
    public function generateAccessToken(array $credentials): array|Exception
    {
        $emailInput = $credentials['email'];
        $passInput = $credentials['password'];

        $externalSource = ExternalSource::where('email', $emailInput)
                            ->where('password', Hash::check($passInput))
                            ->first();

        if (!$externalSource) {
            throw new Exception("Email and Password does not match");
        }

        if (!$externalSource->is_active) {
            throw new Exception("Account inactive. Please contact Hood");
        }

        return $this->getAccessToken($credentials);
    }

    /**
     * Modify/Create access token.
     * docs https://github.com/firebase/php-jwt
     * tutorial https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/
     *
     * @param array $credentials
     * @return array
     */
    private function getAccessToken(array $credentials): array
    {
        return JwtAuthService::getAccessToken($credentials['email']);
    }
}
