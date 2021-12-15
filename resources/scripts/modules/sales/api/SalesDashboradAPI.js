import axios from "axios";
import SalesDashboradEnergyMapper from "@scripts/modules/sales/api/mapper/SalesDashboradEnergyMapper";
export default {
    loadEnergyData: async (dateRange) => {
        try {
            const data = await axios.get('/api/sales-dashboard/home', {params:{...dateRange}});
            return SalesDashboradEnergyMapper.getEnergyDashboardData(data.data.data.energy)


        } catch (error) {
            return error.data;
        }
    },




}
