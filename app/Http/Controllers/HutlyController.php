<?php

namespace App\Http\Controllers;

use App\Http\Resources\HutlySourceFilterResource;
use App\Services\HutlySourceService;

class HutlyController extends Controller
{
    public function index()
    {
        try {
            $service = new HutlySourceService();
            return HutlySourceFilterResource::collection($service->getSource());
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
