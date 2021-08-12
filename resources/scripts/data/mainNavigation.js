export default [
    {
        type: 'group',
        title: 'Dashboard',
        icon: '/assets/images/icons/Dashboard.svg',
        model: true,
        children: [
            {
                type: 'route',
                title: 'Utility',
                route_name: 'dashboard.utility',
                disabled: false,
            },
            {
                type: 'route',
                title: 'Chatbot',
                route_name: 'chatbot',
                disabled: true,
            }
        ]
    },
    {
        type: 'route',
        title: 'Customers',
        icon: '/assets/images/icons/Customers.svg',
        route_name: 'customer.list',
    },
    {
        type: 'route',
        title: 'Helpdesk',
        icon: '/assets/images/icons/Helpdesk.svg',
        route_name: 'helpdesk'
    },
    {
        type: 'route',
        title: 'Real Estate Agency',
        icon: '/assets/images/icons/Helpdesk.svg',
        route_name: 'real.state.agency.home'
    },
    // {
    //     type: 'route',
    //     title: 'Test',
    //     icon: '/assets/images/icons/Helpdesk.svg',
    //     route_name: 'test'
    // },
    {
        type: 'route',
        title: 'Application',
        icon: '/assets/images/icons/Helpdesk.svg',
        route_name: 'applications'
    },
]
