<?php

namespace App\Http\Controllers;

use App\Http\Resources\SettingResource;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function getIsChatbotOffice()
    {
        $setting = SettingService::getOrCreate('is_chatbot_office', 0);
        return (new SettingResource($setting))->response();
    }

    public function setIsChatbotOffice(Request $request)
    {
        $setting = SettingService::set('is_chatbot_office', $request->get('is_chatbot_office'));
        return (new SettingResource($setting))->response();
    }
}
