import CustomerAPI from "@scripts/api/CustomerAPI";

export default {
    getAllCustomerData: () => CustomerAPI.getAllCustomerData(),

    /**
     * @param customerId
     * */
    getCustomerDetail: (customerId) => CustomerAPI.getCustomerProfileData(customerId),

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerProperty>}
     */
    getPropertyInfo: customerId => CustomerAPI.getPropertyInfo(customerId),

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerConnection>}
     */
    getConnectionInfo: customerId => CustomerAPI.getConnectionInfo(customerId),

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerMovingInfo>}
     */
    getMovingInfo: customerId => CustomerAPI.getMovingInfo(customElements),

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerOrder>}
     */
    getOrderDetails: customerId => CustomerAPI.getOrderDetails(customElements)
}
