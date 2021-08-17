import LeadApplicationAPI from "@scripts/api/crm/LeadApplicationAPI";

export default {
    loadMetrics: () => LeadApplicationAPI.getMetrics(),
    loadUserLeadMetrics: () => LeadApplicationAPI.getUserLeadMetrics(),
    loadUserLeads: (sort_search_meta) => LeadApplicationAPI.getUserLeads(sort_search_meta),
    loadUserLead: (id) => LeadApplicationAPI.getUserLead(id),
    loadPlan: (serviceProvider) => LeadApplicationAPI.getPlan(serviceProvider),
    loadNote: (leadUser)=> LeadApplicationAPI.getNote(leadUser),
    loadServiceProvider: (services)=> LeadApplicationAPI.getServiceProvider(services),
    saveNote: (note, leadId) => LeadApplicationAPI.saveNote(note, leadId),
    eacalate: (leadId) => LeadApplicationAPI.eacalate(leadId),
    saveLead: (lead, leadId) => LeadApplicationAPI.saveLead(lead, leadId),
    assignUser: (leadId, agentProfileId) => LeadApplicationAPI.assignUser(leadId, agentProfileId),
}
