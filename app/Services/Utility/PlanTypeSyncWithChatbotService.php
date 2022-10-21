<?php

namespace App\Services\Utility;


class PlanTypeSyncWithChatbotService
{
    // Gilbert plan types
    const GILBERT_BASIC_PLAN = 'basic_plan';
    const GILBERT_BALANCE_PLAN = 'balance_plan';
    const GILBERT_ORIGIN_HOME_SUPPORT = 'origin_home_support';
    const GILBERT_ORIGIN_BASIC = 'origin_basic';
    const GILBERT_POWERSHOP_100_CARBON_NEUTRAL = 'powershop_100%_carbon_neutral';
    const GILBERT_SWITCH_SAVER = 'switch_saver';
    public static $gilbertPlanTypes = [
        self::GILBERT_BASIC_PLAN,
        self::GILBERT_BALANCE_PLAN,
        self::GILBERT_ORIGIN_HOME_SUPPORT,
        self::GILBERT_ORIGIN_BASIC,
        self::GILBERT_POWERSHOP_100_CARBON_NEUTRAL,
        self::GILBERT_SWITCH_SAVER,
    ];

    // Chatbot plan types
    const CHATBOT_BASIC_PLAN = 'basic - home';
    const CHATBOT_BALANCE_PLAN = 'balance plan (home)';
    const CHATBOT_ORIGIN_HOME_SUPPORT = 'origin home support';
    const CHATBOT_ORIGIN_BASIC = 'origin basic';
    const CHATBOT_POWERSHOP_100_CARBON_NEUTRAL = 'powershop 100% carbon neutral';
    const CHATBOT_SWITCH_SAVER = 'switch saver';
    public static $chatbotPlanTypes = [
        self::CHATBOT_BASIC_PLAN,
        self::CHATBOT_BALANCE_PLAN,
        self::CHATBOT_ORIGIN_HOME_SUPPORT,
        self::CHATBOT_ORIGIN_BASIC,
        self::CHATBOT_POWERSHOP_100_CARBON_NEUTRAL,
        self::CHATBOT_SWITCH_SAVER,
    ];


    // Gilbert to Chatbot plan types mapping
    public static $gilbertToChatbotPlanTypesMapping = [
        self::GILBERT_BASIC_PLAN => self::CHATBOT_BASIC_PLAN,
        self::GILBERT_BALANCE_PLAN => self::CHATBOT_BALANCE_PLAN,
        self::GILBERT_ORIGIN_HOME_SUPPORT => self::CHATBOT_ORIGIN_HOME_SUPPORT,
        self::GILBERT_ORIGIN_BASIC => self::CHATBOT_ORIGIN_BASIC,
        self::GILBERT_POWERSHOP_100_CARBON_NEUTRAL => self::CHATBOT_POWERSHOP_100_CARBON_NEUTRAL,
        self::GILBERT_SWITCH_SAVER => self::CHATBOT_SWITCH_SAVER,
    ];

    // Chatbot to Gilbert plan types mapping
    public static $chatbotToGilbertPlanTypesMapping = [
        self::CHATBOT_BASIC_PLAN => self::GILBERT_BASIC_PLAN,
        self::CHATBOT_BALANCE_PLAN => self::GILBERT_BALANCE_PLAN,
        self::CHATBOT_ORIGIN_HOME_SUPPORT => self::GILBERT_ORIGIN_HOME_SUPPORT,
        self::CHATBOT_ORIGIN_BASIC => self::GILBERT_ORIGIN_BASIC,
        self::CHATBOT_POWERSHOP_100_CARBON_NEUTRAL => self::GILBERT_POWERSHOP_100_CARBON_NEUTRAL,
        self::CHATBOT_SWITCH_SAVER => self::GILBERT_SWITCH_SAVER,
    ];

    // Gilbert to Chatbot plan types mapping
    public function gilbertToChatbotplanTypeMapping($plan_type)
    {
        $plan_type = strtolower($plan_type);
        return self::$gilbertToChatbotPlanTypesMapping[$plan_type];
    }

    // Chatbot to Gilbert plan types mapping
    public function chatbotToGilbertplanTypeMapping($plan_type)
    {
        $plan_type = strtolower($plan_type);
        return self::$chatbotToGilbertPlanTypesMapping[$plan_type];
    }

}
