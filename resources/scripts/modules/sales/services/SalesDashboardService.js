import AgencyAPI from "@scripts/api/crm/AgencyAPI";
import SalesDashboradAPI from "@scripts/modules/sales/api/SalesDashboradAPI";

export default {
    loadDashboardEnergyData: (dateRange)=> SalesDashboradAPI.loadEnergyData(dateRange),
}
