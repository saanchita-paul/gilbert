const menus =  [
    {
        title: 'Property Info',
        route: 'property_info'
    },
    {
        title: 'Connection Info',
        route: 'connection_info'
    },
    {
        title: 'Moving Info',
        route: 'moving_info'
    },
    {
        title: 'Order Info',
        route: 'order_info'
    },
    {
        title: 'Other Service',
        route: 'other_service'
    },

]

const components = {
    property_info: 'CustomerPropertyInfo',
    connection_info: 'CustomerConnectionInfo',
    moving_info: 'CustomerMovingInfo',
    order_info: 'CustomerOrderInfo',
    other_service: 'CustomerOtherService',
}

export  default { menus, components }
