import CustomerOld from "@scripts/models/customer-profile/Customer";
import CustomerProperty from "@scripts/models/customer-profile/CustomerProperty";
import CustomerConnection from "@scripts/models/customer-profile/CustomerConnection";
import CustomerMovingInfo from "@scripts/models/customer-profile/CustomerMovingInfo";
import CustomerOrder from "@scripts/models/customer-profile/CustomerOrder";
import CustomerOtherService from "@scripts/models/customer-profile/CustomerOtherService";
import CustomerDetails from "@scripts/models/CustomerDetails";
import Customer from "@scripts/models/Customer";
import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import {mapSentiment} from "@scripts/data/SentimentColor";

export default {
    toClientDetail: (data) => {
        const {from: fromOriginal = {}, to: toOriginal = {}, ...others} = data || {};
        const {bedrooms, house_type, ...from} = fromOriginal;
        const {rent, people, energy_usage, solar_panel, ...to} = toOriginal;

        return new CustomerOld({...others, bedrooms, house_type, rent, people, energy_usage, solar_panel, from, to});
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


    mapCustomerList: customerDataList => {
        return customerDataList.map(customerDetails => {
            return new Customer({
                id: CustomerDetails.id,
                full_name: customerDetails.full_name || customerDetails.first_name +" "+ customerDetails.last_name || '',
                avatar: customerDetails.avatar || '',
                last_interaction: customerDetails.last_interaction || '',
                hood_ui: customerDetails.hood_ui || '',
                facebook_id: customerDetails.facebook_id || '',
                email: customerDetails.email || '',
                phone: customerDetails.phone || '',
                manual_intervention_status: customerDetails.manual_intervention_status || '',
                connection_status: customerDetails.connection_status || '',
                sentiment: customerDetails.sentiment? mapSentiment(customerDetails.sentiment): '',
                state: customerDetails.state || '',
                connection_date: customerDetails.connection_date
                    ? new DayJs(customerDetails.connection_date).format(DATE_FORMAT.DATE_STRING)
                    : null,
                connection_time: customerDetails.connection_date
                    ? new DayJs(customerDetails.connection_date).format(DATE_FORMAT.TIME_STRING_12)
                    : null,

            });
        });
    }
}
