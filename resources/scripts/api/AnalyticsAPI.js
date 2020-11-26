import axios from 'axios';
import AnalyticsMapper from "@scripts/api/mappers/AnalyticsMapper";

export default {
    /**
     *  get energy usage analytics data
     *  @param {Object} filter
     * @returns {Object}
     */
    getEnergyUsageAnalyticsData: async (filter= {}) => {
        try {
            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/analytics/energy-usage`, {
                params: { ...filter }
            });
            console.log("RECEIVED ENERGY USAGE ANALYTICS DATA ", data);
            return AnalyticsMapper.energyUsageDetail(data.data);
        } catch (error) {
            return error.data;
        }
    },
    /**
     *  get property profile analytics data
     *  @param {Object} filter
     * @returns {Object}
     */
    getPropertyProfileAnalyticsData: async (filter = {}) => {
        try {
            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/analytics/property-profile`, {
                params: { ...filter }
            });
            console.log("RECEIVED PROPERTY PROFILE ANALYTICS DATA ", data);
            return AnalyticsMapper.propertyProfileDetail(data.data);
        } catch (error) {
            return error.data;
        }
    },
    /**
     *  get household profile analytics data
     *  @param {Object} filter
     * @returns {Object}
     */
    getHouseholdProfileAnalyticsData: async (filter = {}) => {
        try {
            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/analytics/household-profile`, {
                params: { ...filter }
            });
            console.log("RECEIVED HOUSEHOLD PROFILE ANALYTICS DATA ", data);
            return AnalyticsMapper.householdProfileDetail(data.data);
        } catch (error) {
            return error.data;
        }
    },
    /**
     *  get household profile analytics data
     *  @param {Object} filter
     * @returns {Object}
     */
    getTopDestinationAnalyticsData: async (filter = {}) => {
        try {
            const data = await axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/analytics/top-destination`, {
                params: { ...filter }
            });
            console.log("RECEIVED TOP DESTINATION ANALYTICS DATA ", data);
            return AnalyticsMapper.topDestinationDetail(data.data.data);
        } catch (error) {
            return error.data;
        }
    },
}
