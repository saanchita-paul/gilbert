export default [
    {
        type: 'route',
        title: 'Dashboard',
        icon: 'mdi-view-dashboard',
        route_name: 'dashboard'
    },

    {
        type: 'group',
        title: 'Insight',
        icon: 'mdi-view-list-outline',
        model: false,
        children: [
            {
                type: 'route',
                title: 'Customer',
                icon: 'mdi-chart-areaspline',
                route_name: 'insight.customers'
            },
            {
                type: 'route',
                title: 'Supplier',
                icon: 'mdi-facebook-messenger',
                route_name: 'insight.suppliers'
            }
        ]
    },
    {
        type: 'group',
        title: 'Messenger',
        icon: 'mdi-facebook-messenger',
        model: false,
        children: [
            {
                type: 'route',
                title: 'Customer',
                icon: 'mdi-chart-areaspline',
                route_name: 'messenger.customers'
            },
            {
                type: 'route',
                title: 'Supplier',
                icon: 'mdi-facebook-messenger',
                route_name: 'messenger.suppliers'
            }
        ]
    },
    // {
    //     type: 'route',
    //     title: 'Suppliers',
    //     icon: 'mdi-truck-fast',
    //     route_name: 'account'
    // },
]
