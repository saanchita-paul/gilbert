<?php

namespace MRI\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use MRI\Services\GetAgentService;
use MRI\Services\MapAgentService;
use MRI\Services\GetTenanciesService;
use MRI\Services\GetPropertyService;
use MRI\Services\MapApplicationService;
use MRI\Services\MriServices;

class TestMriController extends Controller
{
    /**
     *
     *
     * @param Request $request
     *
     * @return json
     */
    public function fetchAgents(Request $request)
    {
        $officeId = $request->input('office');
        $afterDate = $request->input('afterDate');
        $exceptions = [];

        try {
            MriServices::handleFetchAgents($officeId, $afterDate);
        } catch (\Exception $exception) {
            $exceptions[] = $exception->getMessage();
        }
        try {
            MriServices::handleMapAgents();
        } catch (\Exception $exception) {
            $exceptions[] = $exception->getMessage();
        }

        return response()->json(['exceptions' => $exceptions], !empty($exceptions) ? 500 : 200);
    }

    /**
     *
     *
     * @param Request $request
     *
     * @return json
     */
    public function fetchTenancies(Request $request)
    {
        $officeId = $request->input('office');
        $afterDate = $request->input('afterDate');
        $exceptions = [];

        try {
            MriServices::handleFetchTenancies($officeId, $afterDate);
        } catch (\Exception $exception) {
            $exceptions[] = $exception->getMessage();
        }
        try {
            MriServices::handleFetchProperties($officeId);
        } catch (\Exception $exception) {
            $exceptions[] = $exception->getMessage();
        }
        try {
            MriServices::handleFetchNotes($officeId, $afterDate);
        } catch (\Exception $exception) {
            $exceptions[] = $exception->getMessage();
        }
        try {
            MriServices::handleMapApplications();
        } catch (\Exception $exception) {
            $exceptions[] = $exception->getMessage();
        }
        try {
            MriServices::handleMapNotes();
        } catch (\Exception $exception) {
            $exceptions[] = $exception->getMessage();
        }

        return response()->json(['exceptions' => $exceptions], !empty($exceptions) ? 500 : 200);
    }
}
