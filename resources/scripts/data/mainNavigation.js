export default [
    {
        type: 'group',
        title: 'Customer',
        icon: 'mdi-account-cog',
        model: false,
        children: [
            {
                type: 'route',
                title: 'Analytics',
                icon: 'mdi-chart-areaspline',
                route_name: 'customerAnalytics'
            },
            {
                type: 'route',
                title: 'Messenger',
                icon: 'mdi-facebook-messenger',
                route_name: 'messenger'
            }
        ]
    },
    {
        type: 'route',
        title: 'Account',
        icon: 'mdi-account-settings',
        route_name: 'account'
    },
]
