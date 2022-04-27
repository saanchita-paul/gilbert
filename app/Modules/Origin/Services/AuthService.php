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

}
