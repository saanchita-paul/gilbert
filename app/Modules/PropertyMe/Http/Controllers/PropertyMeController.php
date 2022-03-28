<?php

namespace PropertyMe\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PropertyMe\Services\ManuallyStoreLead;
use App\Modules\PropertyMe\Services\SaveToConnectionApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PropertyMe\Http\Requests\CreatePropertyMeLeadRequest;
use PropertyMe\PropertyMeLead;
use PropertyMe\Services\SaveContacts;

class PropertyMeController extends Controller
{
    /**
     * @deprecated
     *
     * @return JsonResponse
     */
    private function isConnected(): JsonResponse
    {
        return response()->json(['pm_connected' => \Cache::get('pm_connected')]);
    }

    /**
     * @param CreatePropertyMeLeadRequest $request
     * @return JsonResponse
     */
    public function store(CreatePropertyMeLeadRequest $request): JsonResponse
    {
        try {
            $applications = ManuallyStoreLead::run($request->get('office_id'), $request->get('leads_data'));
            return response()->json(['success' => true, 'applications' => $applications]);
        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
