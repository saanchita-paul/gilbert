<?php

namespace App\Services;

use App\Models\Setting;

class SettingService
{
    // Settings key
    public const IS_CHATBOT_OFFICE = 'is_chatbot_office'; // Automatic assign leads to chatbot

    /**
     * @param string $key
     * @param $value
     * @return Setting
     */
    public static function set(string $key = "", $value = null)
    {
        return Setting::updateOrCreate([
            'setting_key' => $key
        ], [
            'setting_value' => $value
        ]);
    }

    /**
     * @param string $key
     * @return null|string
     */
    public static function get(string $key = "")
    {
        $setting = Setting::where('setting_key', $key)->first();
        return $setting?->setting_value;
    }

    /**
     * @param string $key
     * @param null $value
     */
    public static function getOrCreate(string $key = "", $value = null)
    {
        return Setting::firstOrCreate(
            ['setting_key' => $key],
            ['setting_value' => $value]
        );
    }

    /**
     * @param string $key
     */
    public static function forget(string $key = "")
    {
        return Setting::where('setting_key', $key)->delete();
    }

}
