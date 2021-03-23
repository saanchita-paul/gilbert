import Customer from "@scripts/models/customer-profile/Customer";
import CustomerProperty from "@scripts/models/customer-profile/CustomerProperty";
import CustomerConnection from "@scripts/models/customer-profile/CustomerConnection";
import CustomerMovingInfo from "@scripts/models/customer-profile/CustomerMovingInfo";
import CustomerOrder from "@scripts/models/customer-profile/CustomerOrder";
import CustomerOtherService from "@scripts/models/customer-profile/CustomerOtherService";
import CustomerDetails from "@scripts/models/CustomerDetails";
import CustomerList from "@scripts/models/CustomerList";

export default {
    toClientDetail: (data) => {
        const {from: fromOriginal = {}, to: toOriginal = {}, ...others} = data || {};
        const {bedrooms, house_type, ...from} = fromOriginal;
        const {rent, people, energy_usage, solar_panel, ...to} = toOriginal;

        return new Customer({...others, bedrooms, house_type, rent, people, energy_usage, solar_panel, from, to});
    },

    /**
     * mapping CustomerProperty from API data
     *
     * @param propertyData
     *
     * @returns {CustomerProperty}
     */
    mapPropertyInfo: propertyData => new CustomerProperty({
        id: propertyData.id,
        property_address_text: propertyData.property_address,
        occupation_type: propertyData.occupation_type,
        activities: propertyData.activities,
        // activities: ['Moving Calculator', 'Energy Connection', 'Reminder'],
        house_type: propertyData.house_type,
        house_size: propertyData.house_size,
    }),
    /**
     * mapping CustomerConnection from API data
     *
     *
     * @returns {CustomerConnection}
     * @param connectionData
     */
    mapConnectionInfo: connectionData => new CustomerConnection({
        id: connectionData.id,
        connection_address_text: connectionData.connection_address,
        provider: connectionData.provider,
        energy_type: connectionData.which_utility,
        selected_plan: connectionData.plan,
        solar: connectionData.solar,
    }),

    /**
     * mapping CustomerMovingInfo from API data
     *
     *
     * @returns {CustomerMovingInfo}
     * @param movingData
     */
    mapMovingInfo: movingData => new CustomerMovingInfo({
        id: movingData.id,
        origin_address_text: movingData.connection_address,
        distance_type: movingData.distance_type,
        service_type: movingData.moving_type,
        house_type: movingData.house_type,
        house_size: movingData.house_size,
        has_order: movingData.order,
    }),

    /**
     * mapping CustomerOrder from API data
     *
     *
     * @returns {CustomerOrder[]}
     * @param orderDetailsArray
     */
    mapOrderDetails: orderDetailsArray => {
        const data = [];
        if (Array.isArray(orderDetailsArray)) {
            orderDetailsArray.map(orderDetails => data.push(new CustomerOrder({
                id: orderDetails.id,
                total_cost: orderDetails.value,
                service_type: orderDetails.service_type,
                equipments: orderDetails.service_details,
                moving_time: orderDetails.moving_time,
                extra_services: orderDetails.extras,
                payment_type: orderDetails.payment_type,
                status: orderDetails.status
            })))
        }
        return data;
    },

    /**
     *
     * @param servicesData
     * @returns {CustomerOtherService[]}
     */
    mapOtherServices: servicesData => {
        const data = [];
        if (Array.isArray(servicesData)) {
            servicesData.map(services => data.push(new CustomerOtherService({
                id: services.id,
                category_name: services.category,
                postcode: services.location,
                business_name: services.business_name,
            })))
        }
        return data
    },

    /**
     *
     * @param customerData
     * @returns {CustomerDetails}
     */
    mapCustomerDetailsServices: customerData => {
       return new CustomerDetails({...customerData})
    },

    mapCustomerList: customerListData => {
       return new CustomerList({...customerListData})
    }
}
