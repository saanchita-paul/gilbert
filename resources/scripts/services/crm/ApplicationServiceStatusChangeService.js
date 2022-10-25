import ApplicationServiceStatusChangeAPI from "@scripts/api/crm/ApplicationServiceStatusChangeAPI";

export const getAllStatus = async (type = 'application') => {
    let data = await ApplicationServiceStatusChangeAPI.getAllStatus();
    data = data.data.filter(status => status.type === type);
    return data;
};

export default {
    getAllStatus,
    getAgents: (search, office_id) => ApplicationServiceStatusChangeAPI.getAgents(search, office_id),
    saveSelectedApplications: (note, leadId) => ApplicationServiceStatusChangeAPI.saveSelectedApplications(note, leadId),
};
