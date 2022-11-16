<?php

namespace App\Services;

class RolePermission
{
    //roles
    public const ROLE_HOOD_ADMIN = 'hood_admin';
    public const ROLE_HOOD_AGENT = 'hood_agent';
    public const ROLE_HOOD_TEAM_LEAD = 'hood_team_lead';
    public const ROLE_HOOD_CUSTOMER_REP = 'hood_customer_rep';

    public const ROLE_EXTERNAL_HOOD_TEAM_LEAD = 'hood_external_team_lead';
    public const ROLE_EXTERNAL_HOOD_CUSTOMER_REP = 'hood_external_customer_rep';
    public const ROLE_EXTERNAL_ADMIN = 'hood_external_admin';

    public const ROLE_AGENCY_AGENT = 'agency_agent';
    public const ROLE_AGENCY_OFFICE_ALLOCATOR = 'agency_office_allocator';
    public const ROLE_AGENCY_OFFICE_ADMIN = 'agency_office_admin';
    public const ROLE_AGENCY_OFFICE_DIRECTOR = 'agency_office_director';
    public const ROLE_AGENCY_OFFICE_PROPERTY_MANAGER = 'agency_office_property_manager';
    public const ROLE_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER = 'agency_office_senior_property_manager';
    public const ROLE_AGENCY_OFFICE_ASSISTANT_PROPERTY_MANAGER = 'agency_assistant_property_manager';
    public const ROLE_AGENCY_OFFICE_REAL_ESTATE_AGENT = 'agency_office_real_estate_agent';
    public const ROLE_AGENCY_BUSINESS_DEVELOPMENT_MANAGER = 'agency_office_business_development_manager';
    public const ROLE_AGENCY_SALES_PA = 'agency_sales_pa';
    public const ROLE_AGENCY_RECEPTIONIST = 'agency_receptionist';

    public const ROLE_HOOD_CHATBOT_USER = 'hood_chatbot_user';


    //permissions
    public const P_HOOD_ADMIN_CORE = 'hood_admin_core';
    public const P_HOOD_AGENT_CORE = 'hood_agent_core';
    public const P_HOOD_TEAM_LEAD_CORE = 'hood_team_lead_core';
    public const P_HOOD_CUSTOMER_REP_CORE = 'hood_customer_rep_core';

    public const P_HOOD_EXTERNAL_TEAM_LEAD_CORE = 'hood_external_team_lead_core';
    public const P_HOOD_EXTERNAL_CUSTOMER_REP_CORE = 'hood_external_customer_rep_core';
    public const P_HOOD_EXTERNAL_ADMIN_CORE = 'hood_external_admin_core';

    public const P_AGENCY_AGENT_CORE = 'agency_agent_core';
    public const P_AGENCY_AGENT_OFFICE_ALLOCATOR_CORE = 'agency_office_allocator_core';
    public const P_CAN_CREATE_APPLICATION = 'can_create_application';
    public const P_AGENCY_OFFICE_ADMIN = 'agency_office_admin_core';
    public const P_AGENCY_OFFICE_DIRECTOR = 'agency_office_director_core';
    public const P_AGENCY_OFFICE_PROPERTY_MANAGER = 'agency_office_property_manager_core';
    public const P_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER = 'agency_office_senior_property_manager_core';
    public const P_AGENCY_OFFICE_REAL_ESTATE_AGENT = 'agency_office_real_estate_agent_core';

    public const P_CAN_MANAGE_APPLICATION = 'can_manage_application';
    public const P_CAN_MANAGE_AGENCY = 'can_manage_agency';

    public const P_ACCESS_SALES_DASHBOARD = 'can_access_sales_dashboard';

    public const P_HOOD_CHATBOT_USER_CORE = 'hood_chatbot_user_core';

    // Auto assign switch on/off permission (global)
    public const P_CAN_SWITCH_AUTO_CHATBOT_ASSIGN = 'can_switch_auto_chatbot_assign';
}
