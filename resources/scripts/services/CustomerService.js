import CustomerAPI from "@scripts/api/CustomerAPI";

export default {
    getAllCustomerData: () => CustomerAPI.getAllCustomerData(),

    /**
     * @param {number} customerId
     * */
    getCustomerDetail: (customerId) => CustomerAPI.getCustomerProfileData(customerId)
}
