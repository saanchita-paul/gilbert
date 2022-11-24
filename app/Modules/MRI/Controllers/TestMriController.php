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
            $fetchAgentService = new GetAgentService();

            $message = '';

            if (!empty($officeId)) {
                $message .= sprintf('(Office ID = %s) ', $officeId);
                $fetchAgentService->setOfficeId(intval($officeId));
            }

            if (empty($afterDate)) {
                $subDays = !empty(config('mri.sub_days')) ? config('mri.sub_days') : 2;
                $nowDate = Carbon::now()->subDays($subDays)->format('Y-m-d');
                $fetchAgentService->setAfterDate($nowDate);
                $message .= sprintf('Fetching data after date %s', $nowDate);
            }
            else if ($afterDate !== 'all') {
                $fetchAgentService->setAfterDate($afterDate);
                $message .= sprintf('Fetching data after date %s', $afterDate);
            }
            else {
                $message .= 'Fetching all data without after date';
            } 
            info($message);

            $fetchAgentService->run();
        } catch (\Exception $exception) {
            $exceptions['fetch_agent'] = $exception->getMessage();
        }
        
        try {
            $mapAgentService = new MapAgentService();
            $mapAgentService->run();
        } catch (\Exception $exception) {
            $exceptions['map_agent'] = $exception->getMessage();
        }

        return response()->json(['exceptions'=>$exceptions], !empty($exceptions) ? 500 : 200);
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
        $exceptions= [];

        try {
            $tenancyService = new GetTenanciesService();

            $message = '';

            if (!empty($officeId)) {
                $message .= sprintf('(Office ID = %s) ', $officeId);
                $tenancyService->setOfficeId(intval($officeId));
            }

            if (empty($afterDate)){
                $subDays = !empty(config('mri.sub_days')) ? config('mri.sub_days') : 2;
                $nowDate = Carbon::now()->subDays($subDays)->format('Y-m-d');
                $tenancyService->setAfterDate($nowDate);
                $message .= sprintf('Fetching data after date %s', $nowDate);
            }
            else if ($afterDate !== 'all') {
                $tenancyService->setAfterDate($afterDate);
                $message .= sprintf('Fetching data after date %s', $afterDate);
            }
            else {
                $message .= 'Fetching all data without after date';
            }

            info($message);
             
            $tenancyService->run();
        } catch (\Exception $exception) {
            $exceptions['fetch_tenancies'] = $exception->getMessage();
        }

        try {
            $propertyService = new GetPropertyService();
            if (!empty($officeId)) {
                $propertyService->setOfficeId(intval($officeId));
            }
            $propertyService->run();
        } catch (\Exception $exception) {
            $exceptions['fetch_properties'] = $exception->getMessage();
        }

        try {
            $testService = new MapApplicationService();
            $testService->run();
        } catch (\Exception $exception) {
            $exceptions['map_applications'] = $exception->getMessage();
        }

        return response()->json(['exceptions'=>$exceptions], !empty($exceptions) ? 500 : 200);
    }
}