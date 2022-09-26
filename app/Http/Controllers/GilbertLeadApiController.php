<?php

namespace App\Http\Controllers;
use App\Models\ConnectionApplication;
use App\Services\GilbertToCB\createApplicationService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class GilbertLeadApiController extends Controller
{
//    public function createApplication(Request $request){
//        try {
//            $createApp = new createApplicationService(1);
////            return JsonResponse::create($createApp->createAppServices($request->toArray()));
//            return $createApp->create($request->toArray());
//
//        }
//        catch (Exception $exception) {
//            return $this->sendErrorResponse($exception);
//        }
//    }


    private $application;

    public function createApplication( )

    {
        $createApp = new createApplicationService($this->application->id);
//        dd($createApp);
        $createApp->create();
    }


}
