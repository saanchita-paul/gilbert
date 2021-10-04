export default [
    {
        roles: ['agency_office_admin', 'agency_office_director', 'agency_office_property_manager', 'agency_office_senior_property_manager', 'agency_office_real_estate_agent', 'agency_office_allocator'],
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
        roles: ['hood_team_lead', 'hood_customer_rep'],
        route_name: 'applications'
    }
]
