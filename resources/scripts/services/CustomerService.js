import CustomerAPI from "@scripts/api/CustomerAPI";
import CustomerAnalytics from "@scripts/models/CustomerAnalytics";
import DateRange from "@scripts/models/DateRange";


export default {
    /**
     * @param {DateRange} dateRange
     * @returns {CustomerAnalytics}
     */
    getCustomerAnalytics: dateRange => CustomerAPI.getCustomerAnalytics(dateRange)
}
