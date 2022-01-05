import AgentApplicationAPI from "@scripts/api/crm/AgentApplicationAPI";

export default {
    getApplicationMetrics: () => AgentApplicationAPI.getApplicationMetrics(),
    getApplicationList: (sort_search_meta) => AgentApplicationAPI.getApplicationList(sort_search_meta),
    getApplicationSummary: (id) => AgentApplicationAPI.getApplicationSummary(id),
    loadAgentList: (meta, agencyId, officeId) => AgentApplicationAPI.loadAgentList(meta, agencyId, officeId),
    async createApplication(application) {
        return await AgentApplicationAPI.createApplication(application);
    },
}
