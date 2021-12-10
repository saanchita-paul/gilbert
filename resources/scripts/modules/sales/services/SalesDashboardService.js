import UtilityAPI from "@scripts/api/UtilityAPI";


export default {
    getUtilityDashboardData: () => UtilityAPI.getUtilityDashboardData(),
    getConnectionSummary: () => UtilityAPI.getConnectionSummary()

}
