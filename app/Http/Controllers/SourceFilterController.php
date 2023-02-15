<?php

namespace App\Http\Controllers;

use App\Http\Resources\SourceFilterResource;
use App\Services\SourceFilterService;

class SourceFilterController extends Controller
{
    public function index()
    {
        try {
            $service = new SourceFilterService();
             return SourceFilterResource::collection($service->getSource());
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
