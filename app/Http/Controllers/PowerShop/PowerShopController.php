<?php

namespace App\Http\Controllers\PowerShop;
use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\ApplicationResource;
use App\Models\ConnectionApplication;
use App\Models\User;
use App\Services\Application\SearchConnectionApplication;
use App\Services\PowerShop\CAFGenerationService;
use App\Services\PowerShop\SameDayConnectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PowerShopController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|StreamedResponse
     */
    public function generatePowerShopCaf(Request $request): JsonResponse|StreamedResponse
    {
     $ids = explode(',', $request->get('ids'));
        try {
            $service = new CAFGenerationService($ids);
            return $service->downloadCAF();
        }
        catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }

    }

    /**
     * Getting Applications list
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection|JsonResponse
     */

    public function getPowerShop(Request $request): AnonymousResourceCollection|JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        try {
            $data = array_merge($request->toArray(), ['provider_name' => ConnectionApplication::PROVIDER_POWER_SHOP]);
            $service = new SearchConnectionApplication($data);
            return ApplicationResource::collection($service->get($user));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    // same day connection
    public function sameDayConnectionValidate($applicationId, $submitType)
    {
        try {
            $service = new SameDayConnectionService($applicationId, $submitType);
            return response()->json(['data' => $service->validateSameDayConnection()]);
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
