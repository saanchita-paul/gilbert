<?php
namespace App\Services;

class RolePermission {
    //roles
    const ROLE_HOOD_ADMIN = 'hood_admin';
    const ROLE_HOOD_AGENT = 'hood_agent';
    const ROLE_HOOD_TEAM_LEAD = 'hood_team_lead';
    const ROLE_HOOD_CUSTOMER_REP= 'hood_customer_rep';

    const ROLE_AGENCY_AGENT = 'agency_agent';
    const ROLE_AGENCY_OFFICE_ALLOCATOR = 'agency_office_allocator';
    const ROLE_AGENCY_OFFICE_ADMIN = 'agency_office_admin';
    const ROLE_AGENCY_OFFICE_DIRECTOR = 'agency_office_director';
    const ROLE_AGENCY_OFFICE_PROPERTY_MANAGER = 'agency_office_property_manager';
    const ROLE_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER = 'agency_office_senior_property_manager';
    const ROLE_AGENCY_OFFICE_ASSISTANT_PROPERTY_MANAGER = 'agency_assistant_property_manager';
    const ROLE_AGENCY_OFFICE_REAL_ESTATE_AGENT = 'agency_office_real_estate_agent';
    const ROLE_AGENCY_BUSINESS_DEVELOPMENT_MANAGER = 'agency_office_business_development_manager';
    const ROLE_AGENCY_SALES_PA = 'agency_sales_pa';
    const ROLE_AGENCY_RECEPTIONIST = 'agency_receptionist';
    

    //permissions
    const P_HOOD_ADMIN_CORE = 'hood_admin_core';
    const P_HOOD_AGENT_CORE = 'hood_agent_core';
    const P_HOOD_TEAM_LEAD_CORE = 'hood_team_lead_core';
    const P_HOOD_CUSTOMER_REP_CORE = 'hood_customer_rep_core';

    const P_AGENCY_AGENT_CORE = 'agency_agent_core';
    const P_AGENCY_AGENT_OFFICE_ALLOCATOR_CORE = 'agency_office_allocator_core';
    const P_CAN_CREATE_APPLICATION = 'can_create_application';
    const P_AGENCY_OFFICE_ADMIN = 'agency_office_admin_core';
    const P_AGENCY_OFFICE_DIRECTOR = 'agency_office_director_core';
    const P_AGENCY_OFFICE_PROPERTY_MANAGER = 'agency_office_property_manager_core';
    const P_AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER = 'agency_office_senior_property_manager_core';
    const P_AGENCY_OFFICE_REAL_ESTATE_AGENT = 'agency_office_real_estate_agent_core';

    const P_CAN_MANAGE_APPLICATION = 'can_manage_application';
    const P_CAN_MANAGE_AGENCY = 'can_manage_agency';
}
