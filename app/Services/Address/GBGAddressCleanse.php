<?php

namespace App\Services\Address;

use App\Services\Helpers\Terminal;
use Illuminate\Support\Facades\Http;

class GBGAddressCleanse
{
    public function run(array $addresses): array
    {
        $authorization = 'Basic ' . base64_encode(config('gbg.gbgUserId') . ':' . config('gbg.gbgPassword'));
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $authorization
        ])
            ->post(config('gbg.cleanse_url'), $this->getPayload($addresses));
        return json_decode($response->body(), true)['payload'] ?? [];
    }

    private function getPayload(array $addresses): array
    {
        $count = sizeof($addresses);
        Terminal::info("GBG:Cleanse:API: $count");

        return [
            "payload" => $addresses,
            "sourceOfTruth" => "GNAF"
        ];
    }
}
