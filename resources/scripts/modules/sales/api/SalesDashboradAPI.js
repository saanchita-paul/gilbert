import axios from "axios";
import SalesDashboradEnergyMapper from "@scripts/modules/sales/api/mapper/SalesDashboradEnergyMapper";
import SalesDashboradWaterMapper from "@scripts/modules/sales/api/mapper/SalesDashboradWaterMapper";
export default {
    loadEnergyData: async (dateRange) => {
        try {
            const data = await axios.get('/api/sales-dashboard/home', {params:{...dateRange}});
            return SalesDashboradEnergyMapper.getEnergyDashboardData(data.data.data.energy)


        } catch (error) {
            return error.data;
        }
    },
    loadWaterData: async (dateRange) => {
        try {
            const data = await axios.get('/api/sales-dashboard/home', {params:{...dateRange}});
            return SalesDashboradWaterMapper.getWaterDashboardData(data.data.data.water)


        } catch (error) {
            return error.data;
        }
    },
    getReportAccessToken: async () => {
        try {
            const data = await axios.get('/api/get-report-access-token');
            console.log('printing data in the getReportAccessTokenService', data.data);
            return data.data;
        } catch (error) {
            return error.data;
        }
    }
}
