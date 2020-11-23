import AnalyticsAPI from "@scripts/api/AnalyticsAPI";

export default {
    /**
     * get energy usage analytics
     * @param {Object} filter
     * @return {Object}
     * */
    getEnergyUsageAnalytics: (filter) => AnalyticsAPI.getEnergyUsageAnalyticsData(filter),

    /**
     * get property profile analytics
     * @param {Object} filter
     * @return {Object}
     * */
    getPropertyProfileAnalytics: (filter) => AnalyticsAPI.getPropertyProfileAnalyticsData(filter),

    /**
     * get household profile analytics
     * @param {Object} filter
     * @return {Object}
     * */
    getHouseholdProfileAnalytics: (filter) => AnalyticsAPI.getHouseholdProfileAnalyticsData(filter)
}
