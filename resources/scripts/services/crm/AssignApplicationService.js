import AssignApplicationAPI from "@scripts/api/crm/AssignApplicationAPI";

export default {
    getOffices: search => AssignApplicationAPI.getOffices(search),
    getAgents: (search, office_id) => AssignApplicationAPI.getAgents(search, office_id),
    saveSelectedApplications: (note, leadId) => AssignApplicationAPI.saveSelectedApplications(note, leadId),
};
