<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Jobs\NMICheckerJob;
use App\Models\ConnectionApplication;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function getIsChatbotOffice()
    {
        $setting = SettingService::getOrCreate('auto_assign_to_chatbot', 0);
        return (new SettingResource($setting))->response();
    }

    public function setIsChatbotOffice(Request $request)
    {
        $setting = SettingService::set('auto_assign_to_chatbot', $request->get('auto_assign_to_chatbot'));
        return (new SettingResource($setting))->response();
    }

    /**
     * @warning
     * This is only for  testing,
     * it may remove in future
     *
     * @param Request $request
     *
     * @return string
     */
    public function generateAddressReport(Request $request): string
    {
        $order = $request->get('order') === 'desc' ? 'desc' : 'asc';
        $count = $request->get('count') ?? 500;
        $skip = $request->get('skip') ?? 0;

        $builder1 = ConnectionApplication::query()
            ->select('id')
            ->whereNotNull('street_number')
            ->whereNotNull('street_name_only')
            ->whereNotNull('street_type')
            ->whereNotNull('city')
            ->whereNotNull('state')
            ->whereNotNull('postcode')
            ->orderBy('id', $order)
            ->skip($skip)
            ->take($count);

        $builder2 = clone $builder1;

        $sample1 = $builder1->get();
        $sample2 = $builder2->whereNotNull('unit_number')->get();


        foreach ($sample1 as $ap1) {
            NMICheckerJob::dispatch($ap1->id);
        }


        return 'ok';
    }
}
