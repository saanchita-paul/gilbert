import LeadApplicationAPI from "@scripts/api/crm/LeadApplicationAPI";

export default {
    loadMetrics: () => LeadApplicationAPI.getMetrics(),
    loadUserLeadMetrics: () => LeadApplicationAPI.getUserLeadMetrics(),
    loadUserLeads: (types) => LeadApplicationAPI.getUserLeads(types),
    loadUserLead: (id) => LeadApplicationAPI.getUserLead(id),
    loadPlan: () => LeadApplicationAPI.getPlan(),
    loadNote: (leadUser)=> LeadApplicationAPI.getNote(leadUser),
}
