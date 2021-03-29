import CustomerOld from "@scripts/models/customer-profile/Customer";
import CustomerProperty from "@scripts/models/customer-profile/CustomerProperty";
import CustomerConnection from "@scripts/models/customer-profile/CustomerConnection";
import CustomerMovingInfo from "@scripts/models/customer-profile/CustomerMovingInfo";
import CustomerOrder from "@scripts/models/customer-profile/CustomerOrder";
import CustomerOtherService from "@scripts/models/customer-profile/CustomerOtherService";
import CustomerDetails from "@scripts/models/CustomerDetails";
import Customer from "@scripts/models/Customer";
import CustomerMessage from "@scripts/models/CustomerMessage";
import Pagination from "@scripts/models/Pagination";
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
        return new CustomerDetails({
            id: customerData.id,
            full_name: customerData.full_name || customerData.first_name +" "+ customerData.last_name || '',
            avatar: customerData.avatar || '',
            last_interaction: customerData.last_interaction || '',
            hood_ui: customerData.hood_ui || '',
            facebook_id: customerData.facebook_id || '',
            email: customerData.email || '',
            phone: customerData.phone || '',

            propertyProfileId: customerData.property_info.id || '',
            propertyAccountType: customerData.property_info.account_type || '',
            propertyTenancyType: customerData.property_info.tenancy_type || '',
            propertyLifeSupport: customerData.property_info.life_support || '',
            propertySolarPowered: customerData.property_info.solar_power || '',
            propertyIdType: customerData.property_info.id_type || '',
            propertyEAResponseTime: customerData.property_info.ea_response_time || '',
            propertyEstimatedMovingPeriod: customerData.property_info.moving_date || '',
            userAgreeTime: customerData.property_info.user_agree_time || '',
            isManualAddress: customerData.property_info.is_manual_address || '',

            connectionId: customerData.connection_info.id || '',
            connectionProvider: customerData.connection_info.provider || '',
            connectionSelectedPlan: customerData.connection_info.selected_plan || '',
            connectionEneryType: customerData.connection_info.energy_type || '',
            connectionAddress: customerData.connection_info.connection_address || '',
            connectionEnergyType: customerData.connection_info.id || '',
            connectionGasProvider: customerData.connection_info.id || '',
            connectionElectricyFee: customerData.connection_info.electricity_connection_fee || '',
            connectionEDestributor: customerData.connection_info.electricity_distributor || '',
            gasMeterCharge: customerData.connection_info.gas_meter_reading_charge || '',
            connectionNmiNo: customerData.connection_info.nmi || '',
            connectionStatus: customerData.connection_info.connection_status || '',
            connectionReason: customerData.connection_info.reason || '-',
            connectionMernNo: customerData.connection_info.mirn || '',


        })
    },


    mapCustomerList: customerDataList => {
        return customerDataList.map(customerDetails => {
            return new Customer({
                id: customerDetails.id,
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
                billing_preference: customerDetails.billing_preference || ''

            });
        });
    },

    mapCustomerMessages: customerMessages => {
        const messages = [];
        customerMessages.data.forEach(function(message, index, customerMessages) {
            if(index !== customerMessages.length - 1) {
                if(customerMessages[index].type === customerMessages[index+1].type) {
                    messages.push(new CustomerMessage(message, false));
                } else {
                    messages.push(new CustomerMessage(message, true));
                }

            } else {
                messages.push(new CustomerMessage(message, true));
            }
        })

        // const messages = customerMessages.data.map(message => {
        //     return new CustomerMessage(message, true);
        // });
        const pagination = new Pagination({
            page: customerMessages.pagination ? customerMessages.pagination.currentPage: 1,
            hasMorePages: customerMessages.pagination ? customerMessages.pagination.hasMorePages : 0,
            pageCount: customerMessages.pagination ? Math.ceil(customerMessages.pagination.total / customerMessages.pagination.perPage): 1,
            perPage: customerMessages.pagination ? customerMessages.pagination.perPage : 0,
            total: customerMessages.pagination ? customerMessages.pagination.total : 0
        })
        return {
            data : messages,
            pagination: pagination
        };
    }
}
