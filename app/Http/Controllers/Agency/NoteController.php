<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\ApplicationNoteResourse;
use App\Models\ConnectionApplication;
use App\Services\Agency\ApplicationNoteService;
use App\Services\Agency\ApplicationService;
use App\Services\Utility\ExportPlanNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * @param string $application
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function getConnectionNotes(string $application): AnonymousResourceCollection | JsonResponse
    {
        try {
            $service = new ApplicationService();
            return ApplicationNoteResourse::collection($service->getNotes($application));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @param string $id
     * @return ApplicationNoteResourse|JsonResponse
     */
    public function createConnectionNotes(Request $request, string $id): ApplicationNoteResourse|JsonResponse
    {
        try {
            $service = new ApplicationNoteService(Auth::user());
            return ApplicationNoteResourse::make($service->createNotes($request->toArray(), $id));
        } catch (\Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }

    /**
     * @param Request $request
     * @param string $id
     * @return ApplicationNoteResourse|JsonResponse
     */
    public function download(Request $request, string $id)
    {
        try {
            $plansDetails = new ExportPlanNote($id);
            return $plansDetails->run();
        } catch (\Exception $exception) {
            return  $this->sendErrorResponse($exception);
        }
    }


}
