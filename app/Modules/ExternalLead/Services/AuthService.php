<?php

namespace ExternalLead\Services;

use Exception;
use App\Models\ExternalSource;
use Illuminate\Support\Facades\Hash;
use App\Services\AuthService\JwtAuthService;

class AuthService
{
    /**
     * credential email
     *
     * @var string
     */
    private string $email;

    /**
     * credential password
     *
     * @var string
     */
    private string $password;

    /**
     * Generate token.
     *
     * @param array $leadInfo
     * @return array
     * @throws Exception
     */
    public function generateAccessToken(array $credentials): array|Exception
    {
        $externalSources = ExternalSource::where('is_active', true)->get();

        foreach ($externalSources as $externalSource) {
            $this->email = $externalSource['email'];
            $this->password = $externalSource['password'];
            $isEmailValid = $this->email == $credentials['email'];
            $isPassValid = Hash::check($credentials['password'], $this->password);
            if ($isEmailValid && $isPassValid) {
                return $this->getAccessToken($credentials);
            }
        }

        throw new Exception("Email and Password does not match");
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
