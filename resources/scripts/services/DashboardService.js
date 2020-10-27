import DashboardAPI from "@scripts/api/DashboardAPI";
import DateRange from "@scripts/models/DateRange";


export default {
    /**
     * @param {DateRange} dateRange
     * @returns {CustomerAnalytics}
     */
    getDashboardSummary(dateRange) {
        return DashboardAPI.getDashboardSummary(dateRange)
    }
}
