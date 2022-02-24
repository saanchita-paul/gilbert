import * as roles from "@scripts/data/constants/ROLES";
import {AGENT_ROLES} from "@scripts/data/constants/ROLES";


export default {
    AGENCY: [
        {
            text: 'OFFICE ADMIN',
            value: roles.AGENCY_OFFICE_ADMIN
        },
        {
            text: 'DIRECTOR',
            value: roles.AGENCY_OFFICE_DIRECTOR
        },
        {

            text: 'PROPERTY MANAGER',
            value: roles.AGENCY_OFFICE_PROPERTY_MANAGER
        },
        {

            text: 'SENIOR PROPERTY MANAGER',
            value: roles.AGENCY_OFFICE_SENIOR_PROPERTY_MANAGER
        },
        {
            text: 'ASSISTANT PROPERTY MANAGER',
            value: roles.AGENCY_ASSISTANT_PROPERTY_MANAGER
        },
        {
            text: 'REAL ESTATE AGENT',
            value: roles.AGENCY_OFFICE_REAL_ESTATE_AGENT
        },
        {
            text: 'AGENCY OFFICE ALLOCATOR',
            value: roles.AGENCY_OFFICE_ALLOCATOR
        },
        {
            text: 'AGENCY OFFICE BUSINESS DEVELOPMENT MANAGER',
            value: roles.AGENCY_OFFICE_BUSINESS_DEVELOPMENT_MANAGER
        },
        {
            text: 'AGENCY SALES PA',
            value: roles.AGENCY_SALES_PA
        },
        {
            text: 'AGENCY RECEPTIONIST',
            value: roles.AGENCY_RECEPTIONIST
        },

    ],
    HOOD: [

        //hood
        {

            text: 'HOOD ADMIN',
            value: 'hood_admin'
        },
        {
            text: 'TEAM LEADER',
            value: 'hood_team_lead'
        },
        {
            text: 'Customer Representative',
            value: 'hood_customer_rep'
        },
        {
            text: 'Hood Agent',
            value: 'hood_agent'
        },
        {
            text: 'External Team Leader',
            value: 'hood_external_team_lead'
        },
        {
            text: 'External Customer Representative',
            value: 'hood_external_customer_rep'
        },
    ]
}

/**
 * Getting all agent roles
 *
 * @param excepts
 * @return {(string)[]|[string,string,string,string,string,null,null,null,null,null,null]}
 */
export const getAllAgentRoles = ({excepts} = {}) => {
    if (excepts) {
        return AGENT_ROLES.filter(role => !excepts.includes(role));
    }
    return  AGENT_ROLES
}
