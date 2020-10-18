export default [
    {
        type: 'group',
        title: 'Customer',
        icon: 'mdi-account-cog',
        model: false,
        children: [
            {
                type: 'route',
                title: 'Dashboard',
                icon: 'mdi-chart-areaspline',
                route_name: 'customerAnalytics'
            },
            {
                type: 'route',
                title: 'Insight',
                icon: 'mdi-chart-areaspline',
                route_name: 'customerInsight'
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
        title: 'Suppliers',
        icon: 'mdi-truck-fast',
        route_name: 'account'
    },
]
