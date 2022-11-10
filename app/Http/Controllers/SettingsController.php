<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
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
}
