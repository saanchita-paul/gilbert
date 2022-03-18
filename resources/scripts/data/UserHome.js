import {getAllAgentRoles} from "@scripts/data/UserRoles";

export default [
    {
        roles: getAllAgentRoles(),
        route_name: 'agent.application.dashboard'
    },
    {
        roles: ['hood_admin'],
        route_name: 'dashboard.utility'
    },
    {
        roles: ['hood_agent'],
        route_name: ['real.state.agency.home']
    },
    {
        roles: [
            'hood_team_lead',
            'hood_customer_rep',
            'hood_external_team_lead',
            'hood_external_customer_rep',
        ],
        route_name: 'applications'
    },
    {
        roles: [
            'hood_external_admin',
        ],
        route_name: 'sales.energy'
    }
]
