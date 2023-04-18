<?php

namespace App\Modules\NBN\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\ApplicationResource;
use App\Models\ConnectionApplication;
use App\Models\User;
use App\Modules\NBN\Services\SearchNbnConnectionApplication;
use App\Services\Application\SearchConnectionApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Modules\NBN\Services\NBNCafGenerationService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NBNController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse|StreamedResponse
     */
    public function generateNbnCaf(Request $request): JsonResponse|StreamedResponse
    {
        $ids = explode(',', $request->get('ids'));
        try {
            $service = new NBNCafGenerationService($ids);
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

    public function getNBNApplications(Request $request): AnonymousResourceCollection|JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        try {
            $service = new SearchConnectionApplication($request->toArray());
            return ApplicationResource::collection($service->getNBN($user));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }


}
