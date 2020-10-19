import CustomerAnalytics from "@scripts/models/CustomerAnalytics";
import DateRange from "@scripts/models/DateRange";

export default {
    /**
     *  getting customer analytic from CB
     *
     * @param { DateRange } dateRange
     *
     * @returns {CustomerAnalytics}
     */
    getCustomerAnalytics: (dateRange) => {
        return new CustomerAnalytics({
            new_user_count: 20,
            messages_sent: '1,237',
            messages_received: '2,472',
            male_percent: 72,
            female_percent: 28
        })
    }
}
