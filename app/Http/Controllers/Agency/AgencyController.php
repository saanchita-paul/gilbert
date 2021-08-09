<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\AgencyResource;
use App\Models\User;
use App\Services\Agency\SearchAgencyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AgencyController extends Controller
{
    /**
     * Getting Agency list
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function index(Request $request): AnonymousResourceCollection | JsonResponse
    {
        try {
            $service = new SearchAgencyService($request->toArray());
            return AgencyResource::collection($service->get());

        } catch ( \Exception $exception) {
            return response()->json(['success' => false, 'message' => $exception->getMessage()]);
        }
    }
}
