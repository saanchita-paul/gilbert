<?php

namespace App\Interfaces;

/**
 *
 */
interface TokenManagerInterface
{
    /**
     * @return int
     */
    public function getAccessToken(): int;
    
    /**
     * creating a new instance
     *
     * @param string $token
     *
     * @return bool
     */
    public function verifyAccessToken(string $token): bool;
}