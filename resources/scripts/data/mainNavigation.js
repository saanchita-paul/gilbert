export default [
    // {
    //     type: 'route',
    //     title: 'Dashboard',
    //     icon: 'mdi-view-dashboard',
    //     route_name: 'dashboard'
    // },

    {
        type: 'group',
        title: 'Insight',
        icon: 'mdi-view-list-outline',
        model: false,
        children: [
            {
                type: 'route',
                title: 'Customer',
                icon: 'mdi-chart-pie',
                route_name: 'dashboard'
            },
            {
                type: 'route',
                title: 'Supplier',
                icon: 'mdi-chart-areaspline',
                route_name: 'suppliers.insight'
            }
        ]
    },
    {
        type: 'group',
        title: 'Conversation',
        icon: 'mdi-facebook-messenger',
        model: false,
        children: [
            {
                type: 'route',
                title: 'Customer',
                icon: 'mdi-facebook-messenger',
                route_name: 'customers.conversation'
            },
            {
                type: 'route',
                title: 'Supplier',
                icon: 'mdi-email',
                route_name: 'suppliers.mails'
            }
        ]
    },
    {
        type: 'group',
        title: 'Users',
        icon: 'mdi-account',
        model: false,
        children: [
            {
                type: 'route',
                title: 'Customer',
                icon: 'mdi-account-details',
                route_name: 'customers.list'
            },
            {
                type: 'route',
                title: 'Supplier',
                icon: 'mdi-table-account',
                route_name: 'suppliers.list'
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
