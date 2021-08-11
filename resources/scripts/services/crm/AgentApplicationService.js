import AgentApplicationAPI from "@scripts/api/crm/AgentApplicationAPI";

export default {
    getApplicationMetrics: () => AgentApplicationAPI.getApplicationMetrics(),
    getApplicationList: () => AgentApplicationAPI.getApplicationList(),
    getApplicationSummary: (id) => AgentApplicationAPI.getApplicationSummary(id),
    async createApplication(application) {
        return await AgentApplicationAPI.createApplication(application);
    },
}
