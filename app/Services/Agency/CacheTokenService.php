<?php

namespace App\Services\Agency;

use App\Interfaces\TokenManagerInterface;
use Illuminate\Support\Facades\Cache;
class CacheTokenService implements TokenManagerInterface {

    public function getAccessToken(): int
    {
        return $this->generateToken();
    }

    public function verifyAccessToken(string $token): bool
    {
        $savedToken = Cache::get($this->getUserId(), false);
        if($savedToken == $token){
            return true;
        } else {
            return false;
        }        
    }

    private function getUserId() : int 
    {
        return auth()->user()->id;
    }

    private function getRandomToken() : int 
    {
        return random_int(100000, 999999);
    }

    private function generateToken() : string
    {
        $token = $this->getRandomToken();
        Cache::put( $this->getUserId() , $token , 120);
        return $token;
    }

}
