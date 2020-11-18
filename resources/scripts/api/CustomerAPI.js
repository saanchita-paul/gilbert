import axios from 'axios';

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
     * @param {number} customerId
     * @returns {Object}
     */
    getCustomerProfileData: async (customerId) => {
        try {
            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/customers/${customerId}`);
            console.log(`RECEIVED CUSTOMER ${customerId} DATA `, data);
            return data.data;
        } catch (error) {
            return error.data;
        }
    }
}
