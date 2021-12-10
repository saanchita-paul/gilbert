import LeadApplicationAPI from "@scripts/api/crm/LeadApplicationAPI";

export default {
    loadMetrics: (data) => LeadApplicationAPI.getMetrics(data),
    loadUserLeadMetrics: () => LeadApplicationAPI.getUserLeadMetrics(),
    loadUserLeads: (sort_search_meta, active_lead_type, src = 'hood') => LeadApplicationAPI.getUserLeads(sort_search_meta, active_lead_type, src),
    loadUserLead: (id) => LeadApplicationAPI.getUserLead(id),
    closeApplication: (id) => LeadApplicationAPI.closeApplication(id),
    loadPlan: (serviceProvider) => LeadApplicationAPI.getPlan(serviceProvider),
    loadNote: (leadUser)=> LeadApplicationAPI.getNote(leadUser),
    loadServiceProvider: (services)=> LeadApplicationAPI.getServiceProvider(services),
    saveNote: (note, leadId) => LeadApplicationAPI.saveNote(note, leadId),
    eacalate: (leadId) => LeadApplicationAPI.eacalate(leadId),
    saveEscalateReason: (reason, leadId) => LeadApplicationAPI.saveEscalateReason(reason,leadId),
    saveLead: (lead, leadId) => LeadApplicationAPI.saveLead(lead, leadId),
    updateAddress: (address, leadId) => LeadApplicationAPI.updateAddress(address, leadId),
    assignUser: (leadId, agentProfileId) => LeadApplicationAPI.assignUser(leadId, agentProfileId),
    saveSoleField:(field, value, leadId, isDate,identification=false, isService=false) => LeadApplicationAPI.saveSoleField(field, value, leadId, isDate,identification,isService),
    getNmiMern:(id) => LeadApplicationAPI.getNmiMern(id),
    loadAuthorizedPerson:(leadId) => LeadApplicationAPI.loadAuthorizedPerson(leadId),
    saveAuthorizedPerson:(data) => LeadApplicationAPI.saveAuthorizedPerson(data),
    updateApplicationProviders:(payload, application_id) => LeadApplicationAPI.updateApplicationProviders(payload , application_id),
    closeApplicationWithReason: (id, closing_reason) => LeadApplicationAPI.closeApplicationWithReason(id, closing_reason),
     /**
     * Getting minimum valid Connection date
     *
     * @return {string}
     */
    getMinConnectionDate: () => {
        const date = new Date()
        date.setDate(date.getDate());
        return date.toISOString()
    },
    updateConnecitionEndNullDate: (leadId)=>LeadApplicationAPI.updateConnecitionEndNullDate(leadId),

}
