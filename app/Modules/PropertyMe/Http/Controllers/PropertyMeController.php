<?php

namespace PropertyMe\Http\Controllers;

use App\Http\Controllers\Controller;
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

    public function store(CreatePropertyMeLeadRequest $request): JsonResponse
    {
        try {
            $service = new SaveContacts($request->get('refresh_token'));

            $leads = $service->setLeads([$request->get('lead_data')])
                ->loadTenancies()
                ->createLead()
                ->getSavedLeads();

            $saveService = new SaveToConnectionApplication($office, $tenancies);

            foreach ($leads as $lead) {
                $saveService->run($lead);
            }

            return response()->json(['success' => true]);
        } catch ( \Exception $exception) {
            return $this->sendErrorResponse($exception);
        }
    }
}
