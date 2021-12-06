import LeadApplicationAPI from "@scripts/api/crm/LeadApplicationAPI";
import {
    connectionServicesMapper,
    STATUSES_FOR_ENERGY_SUBMIT,
    STATUSES_FOR_WATER_SUBMIT
} from "@scripts/data/ConnectionApplicationMapper";

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

    /**
     * checking if we can submit energy
     *
     * @param {Object[]} services
     *
     * @return boolean
     */
    canSubmitEnergy: services => services.some(service => STATUSES_FOR_ENERGY_SUBMIT.includes(service.status)),

    /**
     * checking if we can submit water
     *
     * @param {Object[]} services
     *
     * @return boolean
     */
    canSubmitWater: services => {
        let water = services.find(service => service.service_type === 'water');
        return water ? services.some(service => STATUSES_FOR_WATER_SUBMIT.includes(service.status)) : true;
    },
}
