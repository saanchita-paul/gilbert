import AgentApplicationAPI from "@scripts/api/crm/AgentApplicationAPI";

export default {
    getApplicationMetrics: () => AgentApplicationAPI.getApplicationMetrics(),
    getApplicationList: (sort_search_meta) => AgentApplicationAPI.getApplicationList(sort_search_meta),
    getApplicationSummary: (id) => AgentApplicationAPI.getApplicationSummary(id),
    loadAgentList: (meta, agencyId, officeId) => AgentApplicationAPI.loadAgentList(meta, agencyId, officeId),
    async createApplication(application) {
        return await AgentApplicationAPI.createApplication(application);
    },
    loadHoodAgentList: (meta) => AgentApplicationAPI.loadHoodAgentList(meta),


    mapStatus: status => {
        switch (status)
        {
            case 'unassigned':
            case 'assigned':
            case 'escalated':
            case 'processing':
                return 'In Progress';
                break;
            case 'submitted':
                return 'Submitted';
                break;
            case 'accepted':
                return 'Accepted';
                break;
            case 'rejected':
                return 'Rejected';
                break;
            case 'failed':
                return 'Manual Processing';
                break;
            default:
                return 'Not Selected';
                break;
        }

    },
}
