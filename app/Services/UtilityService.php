<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class UtilityService
{


    public function fcAPIAuth(string $url=null, string $header=null)
    {

        $response = Http::withHeaders([
                "content-type" => "application/json",
                "Authorization" => $header]
        )
            ->post($url);

        return json_decode($response->body(), true);
    }

    public function fcAPIReqAddress(string $url=null, string $header=null , $body=null)
    {
        try {
            $response = Http::withHeaders([
                    "content-type" => "application/json",
                    "Accept" => "application/json",
                    "Authorization" => $header]
            )->withBody($body,"application/json")->post($url);
            return json_decode($response->body(),true);
        }catch (\Exception $exception)
        {
            echo $exception->getMessage();
        }

    }

}
