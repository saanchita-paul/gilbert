<?php

namespace MRI\Services;

use MRI\Services\GetAgentService;
use MRI\Services\MapAgentService;
use Carbon\Carbon;

class MriServices
{
    public static function handleFetchAgents($officeId, $afterDate)
    {
        $message = '';
        $fetchAgentService = new GetAgentService();

        if (!empty($officeId)) {
            $message .= sprintf('(Office ID = %s) ', $officeId);
            $fetchAgentService->setOfficeId(intval($officeId));
        }

        if (empty($afterDate)) {
            $subDays = !empty(config('mri.sub_days')) ? config('mri.sub_days') : 2;
            $nowDate = Carbon::now()->subDays($subDays)->format('Y-m-d');
            $fetchAgentService->setAfterDate($nowDate);
            $message .= sprintf('Fetching data after date %s', $nowDate);
        } elseif ($afterDate !== 'all') {
            $fetchAgentService->setAfterDate($afterDate);
            $message .= sprintf('Fetching data after date %s', $afterDate);
        } else {
            $message .= 'Fetching all data without after date';
        }
        info($message);

        $fetchAgentService->run();
    }

    public static function handleMapAgents()
    {
        $mapAgentService = new MapAgentService();
        $mapAgentService->run();
    }

    public static function handleFetchTenancies($officeId, $afterDate)
    {
        $tenancyService = new GetTenanciesService();

        $message = '';

        if (!empty($officeId)) {
            $message .= sprintf('(Office ID = %s) ', $officeId);
            $tenancyService->setOfficeId(intval($officeId));
            info($message);
        }

        if (empty($afterDate)) {
            $subDays = !empty(config('mri.sub_days')) ? config('mri.sub_days') : 2;
            $nowDate = Carbon::now()->subDays($subDays)->format('Y-m-d');
            $tenancyService->setAfterDate($nowDate);
            $message .= sprintf('Fetching data after date %s', $nowDate);
        } elseif ($afterDate !== 'all') {
            $tenancyService->setAfterDate($afterDate);
            $message .= sprintf('Fetching data after date %s', $afterDate);
        } else {
            $message .= 'Fetching all data without after date';
        }
        info($message);

        $tenancyService->run();
    }

    public static function handleFetchProperties($officeId)
    {
        $propertyService = new GetPropertyService();
        if (!empty($officeId)) {
            $propertyService->setOfficeId(intval($officeId));
        }
        $propertyService->run();
    }

    public static function handleFetchNotes($officeId, $afterDate)
    {
        $noteService = new GetNotesService();
        if (!empty($officeId)) {
            $noteService->setOfficeId(intval($officeId));
        }
        if (empty($afterDate)) {
            $subDays = !empty(config('mri.sub_days')) ? config('mri.sub_days') : 2;
            $nowDate = Carbon::now()->subDays($subDays)->format('Y-m-d');
            $noteService->setAfterDate($nowDate);
        } elseif ($afterDate !== 'all') {
            $noteService->setAfterDate($afterDate);
        }
        $noteService->run();
    }

    public static function handleMapApplications()
    {
        $testService = new MapApplicationService();
        $testService->run();
    }

    public static function handleMapNotes()
    {
        $mapNoteService = new MapNoteService();
        $mapNoteService->run();
    }

    public static function handleFetchTaggedTenancies($officeId)
    {
        $taggedService = new GetTaggedTenanciesService();
        if (!empty($officeId)) {
            $taggedService->setOfficeId(intval($officeId));
        }
        $taggedService->run();
    }
}
