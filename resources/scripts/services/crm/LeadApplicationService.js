import LeadApplicationAPI from "@scripts/api/crm/LeadApplicationAPI";

export default {
    loadMetrics: () => LeadApplicationAPI.getMetrics()
}
