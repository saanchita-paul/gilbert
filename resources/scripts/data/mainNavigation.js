export default [
    {
        type: 'group',
        title: 'Dashboard',
        icon: 'mdi-chart-pie',
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
        icon: 'mdi-account-group',
        route_name: 'customer.list',
    },
    {
        type: 'route',
        title: 'Helpdesk',
        icon: 'mdi-account-tie',
        route_name: 'helpdesk'
    },
]
