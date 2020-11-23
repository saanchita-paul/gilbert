import DashboardAPI from "@scripts/api/DashboardAPI";
import DateRange from "@scripts/models/DateRange";



export default {
    /**
     * @param {DateRange} dateRange
     *
     */
    getDashboardSummary(dateRange) {
        return DashboardAPI.getDashboardSummary(dateRange)
    },

    getUtilitySummary(dateRange) {
        return DashboardAPI.getUtilitySummary(dateRange)
    },

    getTopCitiesByUtilityUsages(dateRange) {
        return DashboardAPI.getTopCitiesByUtilityUsages(dateRange)
    }
}
