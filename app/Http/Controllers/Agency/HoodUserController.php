<?php
namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Requests\Agency\CreateHoodUserRequest;
use App\Http\Resources\Agency\HoodProfileResource;
use App\Services\Agency\CreateHoodUserService;
use App\Services\Agency\SearchHoodUser;
use App\Services\RolePermission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HoodUserController extends Controller
{
    /**
     * Getting assignable hood users
     *
     * @param Request $request
     *
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        try {
            $service = new SearchHoodUser($request->toArray());
            return HoodProfileResource::collection($service->get());

        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * Getting assignable hood users
     *
     * @param Request $request
     *
     * @return JsonResponse|AnonymousResourceCollection
     */
    public function getAssignee(Request $request): JsonResponse|AnonymousResourceCollection
    {
        try {
            $service = new SearchHoodUser($request->toArray());
            return HoodProfileResource::collection($service->get());

        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * Creating new Hood User
     *
     * @param CreateHoodUserRequest $request
     *
     * @return JsonResponse
     */
    public function store(CreateHoodUserRequest $request)
    {
        try {
            $service = new CreateHoodUserService($request->toArray());
            $user = $service->run()->toArray();

            return response()->json(['data' => $user], 201);

        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
