import CrmUserMapper from "@scripts/api/mappers/crm/CrmUserMapper";
import CrmUserAPI from "@scripts/api/crm/CrmUserAPI";

export default {
    loadPlan: () => ApplicationLeadApi.loadPlan(),
    loadNote: (leadUser)=> ApplicationLeadApi.loadPlan(leadUser),
}
