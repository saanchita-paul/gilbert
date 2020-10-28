/////not using this
export const a =  [
    {
        type: 'route',
        title: 'Customer Dashboard',
        icon: 'mdi-chart-pie',
        route_name: 'dashboard',
        disabled: false,
    },
    {
        type: 'route',
        title: 'Customer Conversation',
        icon: 'mdi-facebook-messenger',
        route_name: 'customers.conversation',
        disabled: false,
    },
    {
        type: 'route',
        title: 'Customers',
        icon: 'mdi-account-details',
        route_name: 'customers.list',
        disabled: false,
    },
]



export default [
    {
        type: 'route',
        title: 'Dashboard',
        icon: 'mdi-view-dashboard',
        route_name: 'dashboard'
    },

    {
        type: 'group',
        title: 'Conversation',
        icon: 'mdi-facebook-messenger',
        model: false,
        children: [
            {
                type: 'route',
                title: 'Inbox',
                icon: 'mdi-facebook-messenger',
                route_name: 'customers.conversation',
                disabled: false,
            },
            {
                type: 'route',
                title: 'Open Deals',
                icon: 'mdi-email',
                route_name: 'suppliers.mails',
                disabled: true,
            },
            {
                type: 'route',
                title: 'Close Deals',
                icon: 'mdi-email',
                route_name: 'suppliers.mails',
                disabled: true,
            },
        ]
    },
    {
        type: 'group',
        title: 'User',
        icon: 'mdi-account',
        model: false,
        children: [
            {
                type: 'route',
                title: 'Customer',
                icon: 'mdi-account-details',
                route_name: 'customers.list',
                disabled: false,
            },
            {
                type: 'route',
                title: 'Supplier',
                icon: 'mdi-table-account',
                route_name: 'suppliers.list',
                disabled: true,
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
