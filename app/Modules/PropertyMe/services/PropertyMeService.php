<?php

namespace PropertyMe\services;

use Exception;
use PropertyMe\services\BasePropertyMeAPI;

class PropertyMeService
{
    /**
     * Create new ConnectionApplication, run a loop.
     *
     * @return bool
     * @throws Exception
     */
    public function getContacts() : bool{
        try {
            $service = new BasePropertyMeAPI();
            $token =  $service->refreshToken();
            $contacts =  $service->getContacts($token);
            // $this->verifyData($contacts , $service);
            return true;
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
            \Log::error($exception->getTraceAsString());
            throw $exception;
        }
    }
}