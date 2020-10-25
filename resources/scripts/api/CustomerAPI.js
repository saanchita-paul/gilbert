import CustomerSummary from "@scripts/models/CustomerSummary";
import DateRange from "@scripts/models/DateRange";

export default {
    /**
     *  getting customer analytic from CB
     *
     * @param { DateRange } dateRange
     *
     * @returns {CustomerSummary}
     */
    getCustomerSummary: (dateRange) => {
        return new CustomerSummary({
            total_customer_count: '4,433',
            new_customer_count: 70,
            active_customer_count: '1,237',
            engaged_customer_count: 472,
        })
    }
}
