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
    loadNote: (leadUser) => LeadApplicationAPI.getNote(leadUser),
    loadServiceProvider: (services) => LeadApplicationAPI.getServiceProvider(services),
    saveNote: (note, leadId) => LeadApplicationAPI.saveNote(note, leadId),
    eacalate: (leadId) => LeadApplicationAPI.eacalate(leadId),
    saveEscalateReason: (reason, leadId) => LeadApplicationAPI.saveEscalateReason(reason, leadId),
    saveLead: (lead, leadId) => LeadApplicationAPI.saveLead(lead, leadId),
    updateAddress: (address, leadId) => LeadApplicationAPI.updateAddress(address, leadId),
    assignUser: (leadId, agentProfileId) => LeadApplicationAPI.assignUser(leadId, agentProfileId),
    saveSoleField: (field, value, leadId, isDate, identification = false, isService = false) => LeadApplicationAPI.saveSoleField(field, value, leadId, isDate, identification, isService),
    getNmiMern: (id) => LeadApplicationAPI.getNmiMern(id),
    loadAuthorizedPerson: (leadId) => LeadApplicationAPI.loadAuthorizedPerson(leadId),
    saveAuthorizedPerson: (data) => LeadApplicationAPI.saveAuthorizedPerson(data),
    updateApplicationProviders: (payload, application_id) => LeadApplicationAPI.updateApplicationProviders(payload, application_id),
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

    /**
     * checking if we can submit energy
     *
     * @param {Object[]} services
     *
     * @param energyServices
     * @return boolean
     */
    canSubmitEnergy: (services, energyServices = ['power', 'gas']) => services.some(service => (
        STATUSES_FOR_ENERGY_SUBMIT.includes(service.status) && energyServices.includes(service.service_type)
    )),

    /**
     * checking if we can submit energy
     *
     * @param {Object[]} services
     *
     * @param {string} serviceName
     * @return boolean
     */
    canEditService: (services, serviceName) => {
        const service = services.find(service => service.service_type === serviceName)
        return service ? STATUSES_FOR_ENERGY_SUBMIT.includes(service.status) : true
    },

    /**
     * checking if we can submit water
     *
     * @param {Object[]} services
     *
     * @return boolean
     */
    canSubmitWater: services => {
        let water = services.find(service => service.service_type === 'water');
        if (water) {
            return services.some(service => STATUSES_FOR_WATER_SUBMIT.includes(service.status) && service.service_type === water)
        }
        return true;
    },

    /**
     *
     * @param status
     * @return {{color: string, text: string}}
     */
    mapStatus: status => {
        switch (status) {
            case connectionServicesMapper.STATUS_UNASSIGNED:
            case connectionServicesMapper.STATUS_ASSIGNED:
            case connectionServicesMapper.STATUS_ESCALATED:
            case connectionServicesMapper.STATUS_IN_PROGRESS:
                return {text: 'Not Submitted', color: 'black'};
            case connectionServicesMapper.STATUS_ACCEPTED:
                return {text: 'Accepted', color: 'green'};
            case connectionServicesMapper.STATUS_EA_SUBMIT:
                return {text: 'In Progress', color: 'green'};
            case connectionServicesMapper.STATUS_AC_MANUAL_PROCESSING:
                return {text: 'Manual Processing', color: 'orange'};
            case connectionServicesMapper.STATUS_CANT_CONNECT:
            case connectionServicesMapper.STATUS_REJECTED:
                return {text: "Rejected", color: 'red'};
            default:
                return {
                    text: 'Unknown Status',
                    color: 'black'
                };
        }
    }
}
