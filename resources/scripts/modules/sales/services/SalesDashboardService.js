import AgencyAPI from "@scripts/api/crm/AgencyAPI";
import SalesDashboradAPI from "@scripts/modules/sales/api/SalesDashboradAPI";

export default {
    loadDashboardEnergyData: (dateRange)=> SalesDashboradAPI.loadEnergyData(dateRange),
    loadDashboardWaterData: (dateRange)=> SalesDashboradAPI.loadWaterData(dateRange),
    getReportAccessToken: async ()=> {
        try {
            const data = await SalesDashboradAPI.getReportAccessToken();
            return data;
        } catch (error) {
            return false;
        }
    }
}
