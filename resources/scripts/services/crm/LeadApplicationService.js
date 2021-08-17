import LeadApplicationAPI from "@scripts/api/crm/LeadApplicationAPI";

export default {
    loadMetrics: () => LeadApplicationAPI.getMetrics(),
    loadUserLeadMetrics: () => LeadApplicationAPI.getUserLeadMetrics(),
    loadUserLeads: (types) => LeadApplicationAPI.getUserLeads(types),
    loadUserLead: (id) => LeadApplicationAPI.getUserLead(id),
    loadPlan: (serviceProvider) => LeadApplicationAPI.getPlan(serviceProvider),
    loadNote: (leadUser)=> LeadApplicationAPI.getNote(leadUser),
    loadServiceProvider: (services)=> LeadApplicationAPI.getServiceProvider(services),
    saveNote: (note, leadId) => LeadApplicationAPI.saveNote(note, leadId),
    eacalate: (leadId) => LeadApplicationAPI.eacalate(leadId),
    saveEscalateReason: (reason, leadId) => LeadApplicationAPI.saveEscalateReason(reason,leadId),
    saveLead: (lead, leadId) => LeadApplicationAPI.saveLead(lead, leadId)
}
