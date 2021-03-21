export default [
    {
        type: 'group',
        title: 'Dashboard',
        icon: 'mdi-chart-pie',
        model: false,
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
                route_name: 'dashboard.chatbot',
                disabled: true,
            }
        ]
    },
    {
        type: 'route',
        title: 'Customers',
        icon: 'mdi-account-group',
        route_name: 'account',
    },
    {
        type: 'route',
        title: 'Helpdesk',
        icon: 'mdi-account-tie',
        route_name: 'account'
    },
]
