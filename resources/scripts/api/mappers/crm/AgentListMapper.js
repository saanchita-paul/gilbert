import Agent from "@scripts/models/crm/Agent";
import PaginationMapper from "@scripts/api/mappers/crm/PaginationMapper";

function mapAgent(agent) {
    return new Agent({ ...agent });
}

export default {
    mapAgentList: agentList => {
        const agents = agentList?.data.map(agent => {
            return mapAgent(agent);
        });
        const pagination = PaginationMapper.mapPagination(agentList?.meta);
        return {
            agents: agents,
            pagination: pagination
        };
    },

    mapMetaData: meta => {
        if (meta.sort_by === "proerty_manager_name")
            meta.sort_by = "first_name";
        if (meta.sort_by === "submitted_lead") meta.sort_by = "first_name";
        if (meta.sort_by === "role") meta.sort_by = "first_name";
        return meta;
    }
};
