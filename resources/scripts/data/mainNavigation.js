import permissions from '@scripts/data/UserPermissions'

export default [
    {
        type: 'group',
        title: 'Dashboard',
        icon: '/assets/images/icons/Dashboard.svg',
        model: true,
        permissions: permissions.P_HOOD_ADMIN_CORE,
        children: [
            {
                type: 'route',
                title: 'Utility',
                route_name: 'dashboard.utility',
                disabled: false,
                permissions: permissions.P_HOOD_ADMIN_CORE
            },
            {
                type: 'route',
                title: 'Chatbot',
                route_name: 'chatbot',
                disabled: true,
                permissions: permissions.P_HOOD_ADMIN_CORE
            }
        ]
    },
    {
        type: 'route',
        title: 'Customers',
        icon: '/assets/images/icons/Customers.svg',
        route_name: 'customer.list',
        permissions: permissions.P_HOOD_ADMIN_CORE
    },
    {
        type: 'route',
        title: 'Helpdesk',
        icon: '/assets/images/icons/Helpdesk.svg',
        route_name: 'helpdesk',
        permissions: permissions.P_HOOD_ADMIN_CORE
    },
    {
        type: 'route',
        title: 'Real Estate Agency',
        icon: '/assets/images/icons/Helpdesk.svg',
        route_name: 'real.state.agency.home',
        permissions: permissions.P_CAN_MANAGE_AGENCY
    },
    {
        type: 'route',
        title: 'Application',
        icon: '/assets/images/icons/map_home.svg',
        route_name: 'applications',
        permissions: permissions.P_CAN_MANAGE_APPLICATION
    },
    {
        type: 'route',
        title: 'Applications Dashboard',
        icon: '/assets/images/icons/Analytics.svg',
        route_name: 'sales.energy',
        permissions: permissions.P_ACCESS_SALES_DASHBOARD
    },
]
