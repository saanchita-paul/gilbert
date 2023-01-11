<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\ApplicationResource;
use App\Models\ConnectionApplication;
use App\Models\ConnectionService;
use App\Models\User;
use App\Services\Application\SearchConnectionApplication;
use App\Services\ApplicationCafService\CafApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Storage;
use Powershop\Services\CAFGenerationService;


class ApplicationCafController extends Controller{

    /**
     * Getting Applications list
     *
     * @param Request $request
     *
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function getGilbertApplications(Request $request ): JsonResponse|AnonymousResourceCollection{
        /** @var User $user */
        $user = auth()->user();
        try {
            $data = array_merge($request->toArray());
            $service = new SearchConnectionApplication($data);
            return ApplicationResource::collection($service->get($user));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    public function generateGilbertCaf(Request $request){
        $ids = explode(',', $request->get('ids'));
        try {
            $service = new CafApplication($ids);
            $service->prepareCafFileData();
            $path = $service->downloadedZipFile();
            $path  = public_path("storage/$path");
            return response()->download($path);
        }
        catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

}
