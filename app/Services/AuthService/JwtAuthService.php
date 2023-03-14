<?php

namespace App\Services\AuthService;

use Firebase\JWT\JWT;
use DateTimeImmutable;
use Exception;

class JwtAuthService
{
    /**
     * server name
     *
     * @var string
    */
    public static string $serverName = "hood.ai";
    /**
     * expire time
     *
     * @var int
    */
    public static int $expireMin = 60;
    /**
     * encryption type
     *
     * @var string
    */
    public static string $encryptionType = 'HS512';

    /**
     * Create access token.
     * docs https://github.com/firebase/php-jwt
     * tutorial https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/
     *
     * @param  string $user_name
     * @return array $tokenDetails
     */
    public static function getAccessToken($user_name) : array
    {
        $secretKey  = config('app.key');
        $issuedAt   = new DateTimeImmutable();
        $expire     = $issuedAt->modify('+'.self::$expireMin. 'minutes')->getTimestamp();      // Add 60 seconds
        $serverName = self::$serverName;
        $username   = $user_name;                            // Retrieved from filtered POST data

        $data = [
            'iat'      => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'iss'      => $serverName,                       // Issuer
            'nbf'      => $issuedAt->getTimestamp(),         // Not before
            'exp'      => $expire,                           // Expire
            'userName' => $username,
        ];
        
        $access_token = JWT::encode(
            $data,
            $secretKey,
            self::$encryptionType
        );

        return [
            'access_token' => $access_token,
            'expires_at'   => date('Y/m/d H:i:s', $expire),
            'token_type'   => 'Bearer',
        ];
    }

    /**
     * Verify access token.
     * docs https://github.com/firebase/php-jwt
     * tutorial https://www.sitepoint.com/php-authorization-jwt-json-web-tokens/
     *
     * @param  string $ExistingJwtToken
     * @return string|bool $successOrFail
     */
    public static function checkAccessToken($jwtToken)
    {
        try {
            $now = new DateTimeImmutable();
            $secretKey  = config('app.key');
            $serverName = self::$serverName;
            $token = JWT::decode($jwtToken, $secretKey, [self::$encryptionType]);
            if (
                $token->iss !== $serverName ||
                $token->nbf > $now->getTimestamp() ||
                $token->exp < $now->getTimestamp()
            ) {
                throw new Exception("Invalid jwt signature");
            }
            return $token->userName;
        } catch (\Exception $exception) {
            \Log::error("Invalid token or expired");
            \Log::error($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            return false;
        }
    }
}
