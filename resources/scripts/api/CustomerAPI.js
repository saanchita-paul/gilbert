import axios from 'axios';
import CustomerMapper from "@scripts/api/mappers/CustomerMapper";
import Customer from "@scripts/models/customer-profile/Customer";
import CustomerProperty from "@scripts/models/customer-profile/CustomerProperty";
import CustomerConnection from "@scripts/models/customer-profile/CustomerConnection";
import CustomerMovingInfo from "@scripts/models/customer-profile/CustomerMovingInfo";
import CustomerOrder from "@scripts/models/customer-profile/CustomerOrder";
import root from "lodash-es/_root";
import Pagination from "@scripts/models/Pagination";

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
            return data.data.data;
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
     * getting all messages of a Customer
     * @param customerId
     */
     getCustomerMessages: async (customerId, pageIndex) => {
        // Todo API End point will be replaced later
        try {
            const data = await axios.get(`${'https://devbot.hood.ai/hood-dashboard/api/customers/'}${customerId}/chat-histories?page=${pageIndex}`);
            return CustomerMapper.mapCustomerMessages(data.data);
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
        const data =(await axios.get(`${ROOT}/customers/${customerId}/moving-info`)).data;
        return CustomerMapper.mapMovingInfo(data.data);
    },
    /**
     *
     * @param customerId
     * @returns {Promise<CustomerOrder[]>}
     */
    getOrderDetails: async customerId => {
        const data =(await axios.get(`${ROOT}/customers/${customerId}/order-details`)).data;
        return CustomerMapper.mapOrderDetails(data.data);
    },

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerOtherService[]>}
     */
    getLocalSearch: async customerId => {
        const data =(await axios.get(`${ROOT}/customers/${customerId}/local-business-search`)).data;
        return CustomerMapper.mapOtherServices(data.data);
    },

    getCustomerDetails: async customerId => {
        const data =(await axios.get(`${BOT_API}/customers/${customerId}`)).data;
        return CustomerMapper.mapCustomerDetailsServices(data.data);
    },

    /**
     *
     * @param pageIndex
     * @returns {Promise<{pagination: Pagination, data}>}
     */
    getCustomerList: async pageIndex=> {
        const data =(await axios.get(`${BOT_API}customers?page=${pageIndex}`)).data;

        return {
            data: CustomerMapper.mapCustomerList(data.data),
            pagination: new Pagination({
                currentPage: data.pagination ? data.pagination.currentPage: 1,
                hasMorePages: data.pagination ? data.pagination.hasMorePages : 0,
                pageCount: data.pagination ? Math.ceil(data.pagination.total / data.pagination.perPage): 1,
                perPage: data.pagination ? data.pagination.perPage : 0,
                total: data.pagination ? data.pagination.total : 0
            })
        };
    },

    /**
     *
     * @param customerId
     * @param manualInterventionStatus
     */
     toggleManualIntervention: async (customerId, manualInterventionStatus) => {
        const response = (await axios.get(`${ROOT}/utility/${customerId}/manual-intervention`, {
            params: {
                manualInterventionStatus: manualInterventionStatus
            }
        }));
        return response;
    },

}
