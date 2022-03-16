import SalesDashboardAPI from "@scripts/modules/sales/api/SalesDashboardAPI";

export default {
    loadDashboardEnergyData: (dateRange, dateType)=> SalesDashboardAPI.loadEnergyData(dateRange, dateType),
    loadDashboardWaterData: (dateRange)=> SalesDashboardAPI.loadWaterData(dateRange),
    getReportAccessToken: async ()=> {
        try {
            const data = await SalesDashboardAPI.getReportAccessToken();
            return data;
        } catch (error) {
            return false;
        }
    }
}
