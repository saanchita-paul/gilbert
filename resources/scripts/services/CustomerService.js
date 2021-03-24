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
    getMovingInfo: customerId => CustomerAPI.getMovingInfo(customerId),

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerOtherService>}
     */
    getOrderDetails: customerId => CustomerAPI.getOrderDetails(customerId),

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerOtherService>}
     */
    getLocalSearch: customerId => CustomerAPI.getLocalSearch(customerId),

    /**
     *
     * @param customerId
     * @returns {Promise<CustomerOtherService>}
     */
    getCustomerDetails: customerId => CustomerAPI.getCustomerDetails(customerId),

    /**
     *
     * @param pageIndex
     * @returns {Promise<CustomerListInfo>}
     */
    getCustomerList: pageIndex => CustomerAPI.getCustomerList(pageIndex),

    /**
     *
     * @param customerId
     * @param manualInterventionStatus
     */
    toggleManualIntervention: (customerId, manualInterventionStatus) => CustomerAPI.toggleManualIntervention(customerId, manualInterventionStatus),
    
    /**
    * @param pageIndex
     * @returns {Promise<CustomerListInfo>}
     */
    getCustomerTableData: pageIndex => CustomerAPI.getCustomerList(pageIndex),
}
