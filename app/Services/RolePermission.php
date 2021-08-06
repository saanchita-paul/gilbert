<?php
namespace App\Services;

class RolePermission {
    //roles
    const ROLE_HOOD_ADMIN = 'hood_admin';

    const ROLE_AGENCY_AGENT = 'agency_agent';
    const ROLE_AGENCY_TEAM_LEAD = 'agency_team_lead';

    //permissions
    const P_HOOD_ADMIN_CORE = 'hood_admin_core';
    const P_AGENCY_AGENT_CORE = 'agency_agent_core';
    const P_AGENCY_TEAM_LEAD_CORE = 'agency_team_lead_core';
    const P_CAN_CREATE_APPLICATION = 'can_create_application';
}
