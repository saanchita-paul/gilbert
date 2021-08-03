import AgentApplicationAPI from "@scripts/api/crm/AgentApplicationAPI";

export default {
    getApplicationMetrics: () => AgentApplicationAPI.getApplicationMetrics(),
    getApplicationList: () => AgentApplicationAPI.getApplicationList()
}
