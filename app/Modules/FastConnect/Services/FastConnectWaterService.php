<?php

namespace FastConnect\Services;

use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FastConnectService
{
    private string $accessToken;

    public function authenticate(): static
    {
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'authorization' => \config('fastconnect.base64_key'),
        ])
            ->post( \config('fastconnect.root_url') . \config('fastconnect.get_token_uri'));

        $this->accessToken = json_decode($response->body(), true)['access_token'];

        return $this;
    }
}
