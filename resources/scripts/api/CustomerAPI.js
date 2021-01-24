import axios from 'axios';
import CustomerMapper from "@scripts/api/mappers/CustomerMapper";
import Customer from "@scripts/models/Customer";
import CustomerProperty from "@scripts/models/CustomerProperty";
import CustomerConnection from "@scripts/models/CustomerConnection";
import CustomerMovingInfo from "@scripts/models/CustomerMovingInfo";
import CustomerOrder from "@scripts/models/CustomerOrder";
import root from "lodash-es/_root";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

export default {
    /**
     *  getting all customer data from CB
     *
     * @returns {Object}
     */
    getAllCustomerData: async () => {
        try {
            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/customers/all`);
            console.log("RECEIVED ALL CUSTOMER DATA ", data);
            return data.data;
        } catch (error) {
            return error.data;
        }
    },
    /**
     * getting specified customer data from CB
     * @param customerId
     * @returns {Customer} || {Object}
     */
    getCustomerProfileData: async (customerId) => {
        try {
            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/customers/${customerId}`);
            console.log(`RECEIVED CUSTOMER ${customerId} DATA `, data);
            return CustomerMapper.toClientDetail(data.data.data);
        } catch (error) {
            return error.data;
        }
    },

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerProperty>}
     */
    getPropertyInfo: async customerId => {
        const data =(await axios.get(`${ROOT}/customers/${customerId}/property-info`)).data;
        return CustomerMapper.mapPropertyInfo(data.data);
    },
    /**
     *
     * @param customerId
     * @returns {Promise<CustomerConnection>}
     */
    getConnectionInfo: async customerId => {
        const data =(await axios.get(`${ROOT}/customers/${customerId}/connection-info`)).data;
        return CustomerMapper.mapConnectionInfo(data.data);
    },
    /**
     *
     * @param customerId
     * @returns {Promise<CustomerMovingInfo>}
     */
    getMovingInfo: async customerId => {
        return new CustomerMovingInfo()
    },
    /**
     *
     * @param customerId
     * @returns {Promise<CustomerOrder>}
     */
    getOrderDetails: async customerId => {
        return new CustomerOrder()
    }
}
