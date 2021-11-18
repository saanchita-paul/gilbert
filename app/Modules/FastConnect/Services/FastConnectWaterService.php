<?php

namespace FastConnect\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Models\ConnectionApplication;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use FastConnect\Services\FastConnectProductService;

class FastConnectWaterService
{
    private string $accessToken;
    private int $applicationId;
    private array|Collection|ConnectionApplication|Model $application;

    public function __construct(int $id) {
        $this->applicationId = $id;
        $this->application = ConnectionApplication::findOrFail($id);
        $this->authenticate();
    }

    public function authenticate(): static
    {
        $response = Http::withHeaders([
            'content-type' => 'application/json',
            'authorization' => \config('fastconnect.base64_key'),
        ])
            ->post( \config('fastconnect.root_url') . \config('fastconnect.get_water_token_uri'));

        $this->accessToken = json_decode($response->body(), true)['access_token'];

        return $this;
    }

    public function submitWaterLead()
    {
        $productService = new FastConnectProductService($this->applicationId);
        $product = $productService->getProductGroup()->getProductDetails();
        dd($product);
    }
}
