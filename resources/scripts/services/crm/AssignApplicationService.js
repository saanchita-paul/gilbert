import AssignApplicationAPI from "@scripts/api/crm/AssignApplicationAPI";

export default {
    loadOffices: data => AssignApplicationAPI.getOffices(data),
};
