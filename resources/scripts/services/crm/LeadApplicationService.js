import LeadApplicationAPI from "@scripts/api/crm/LeadApplicationAPI";
import {
    providerNameMapper,
    planTypeNameMapper, planTypeKeyMapper
} from "@scripts/data/ProviderAndPlanNameMapper";
import {
    connectionServicesMapper,
    STATUSES_FOR_ENERGY_SUBMIT,
    STATUSES_FOR_WATER_SUBMIT
} from "@scripts/data/ConnectionApplicationMapper";
import Store from "@scripts/store";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";

export default {
    loadMetrics: data => LeadApplicationAPI.getMetrics(data),
    loadAgencyMetrics: data => LeadApplicationAPI.loadAgencyMetrics(data),
    loadAgencyMetricsByApplication: data =>
        LeadApplicationAPI.loadAgencyMetricsByApplication(data),
    loadUserLeadMetrics: () => LeadApplicationAPI.getUserLeadMetrics(),
    loadUserLeads: (sort_search_meta, active_lead_type, src = "hood", params) =>
        LeadApplicationAPI.getUserLeads(
            sort_search_meta,
            active_lead_type,
            src,
            params
        ),
    loadUserLeadsForAgents: (
        sort_search_meta,
        active_lead_type,
        src = "hood",
        params
    ) =>
        LeadApplicationAPI.loadUserLeadsForAgents(
            sort_search_meta,
            active_lead_type,
            src,
            params
        ),
    loadUserLead: id => LeadApplicationAPI.getUserLead(id),
    closeApplication: id => LeadApplicationAPI.closeApplication(id),
    loadPlan: serviceProvider => LeadApplicationAPI.getPlan(serviceProvider),
    loadNote: leadUser => LeadApplicationAPI.getNote(leadUser),
    loadServiceProvider: services =>
        LeadApplicationAPI.getServiceProvider(services),
    saveNote: (note, leadId) => LeadApplicationAPI.saveNote(note, leadId),
    eacalate: leadId => LeadApplicationAPI.eacalate(leadId),
    saveEscalateReason: (reason, leadId) =>
        LeadApplicationAPI.saveEscalateReason(reason, leadId),
    confirmSubmitLead: (lead, leadId) =>
        LeadApplicationAPI.confirmSubmitLead(lead, leadId),
    updateAddress: (address, leadId) =>
        LeadApplicationAPI.updateAddress(address, leadId),
    assignUser: (leadId, agentProfileId) =>
        LeadApplicationAPI.assignUser(leadId, agentProfileId),
    saveSoleField: (
        field,
        value,
        leadId,
        isDate,
        identification = false,
        isService = false
    ) =>
        LeadApplicationAPI.saveSoleField(
            field,
            value,
            leadId,
            isDate,
            identification,
            isService
        ),
    getNmiMern: id => LeadApplicationAPI.getNmiMern(id),
    loadAuthorizedPerson: leadId =>
        LeadApplicationAPI.loadAuthorizedPerson(leadId),
    saveAuthorizedPerson: data => LeadApplicationAPI.saveAuthorizedPerson(data),
    updateApplicationProviders: (payload, application_id) =>
        LeadApplicationAPI.updateApplicationProviders(payload, application_id),
    closeApplicationWithReason: (id, closing_reason) =>
        LeadApplicationAPI.closeApplicationWithReason(id, closing_reason),
    getAssignedHoodUser: id => LeadApplicationAPI.getAssignedHoodUser(id),
    loadHoodUser: () => LeadApplicationAPI.loadHoodUser(),
    loadAgencies: search => LeadApplicationAPI.loadAgencies(search),
    loadOffices: (agencyId, search) =>
        LeadApplicationAPI.loadOffices(agencyId, search),

    /**
     * Getting minimum valid Connection date
     *
     * @return {string}
     */
    getMinConnectionDate: () => {
        const date = new Date();
        date.setDate(date.getDate());
        return date.toISOString();
    },
    updateConnecitionEndNullDate: leadId =>
        LeadApplicationAPI.updateConnecitionEndNullDate(leadId),

    /**
     * checking if we can submit energy
     *
     * @param {Object[]} services
     *
     * @param energyServices
     * @return boolean
     */
    canSubmitAnyEnergy: (services, energyServices = ["power", "gas"]) =>
        services.some(
            service =>
                STATUSES_FOR_ENERGY_SUBMIT.includes(service.status) &&
                energyServices.includes(service.service_type)
        ),

    canSubmitEnergy: type => {
        let status =
            type === "power"
                ? UtilityStoreService.getPowerStatus()
                : UtilityStoreService.getGasStatus();
        return STATUSES_FOR_ENERGY_SUBMIT.includes(status);
    },

    /**
     * checking if we can submit energy
     *
     * @param {Object[]} services
     *
     * @param {string} serviceName
     * @return boolean
     */
    canEditService: status => {
        return status ? STATUSES_FOR_ENERGY_SUBMIT.includes(status) : true;
    },

    /**
     * checking if we can submit both Power and Gas
     *
     * @param {Object[]} services
     *
     * @return boolean
     */
    canShowBothEnergySubmitCheckbox: services => {
        const energyServices = services.filter(
            service =>
                service.service_type === "power" ||
                service.service_type === "gas"
        );
        return energyServices.every(service =>
            STATUSES_FOR_ENERGY_SUBMIT.includes(service.status)
        );
    },

    /**
     * checking if we can submit water
     *
     * @param {Object[]} services
     *
     * @return boolean
     */
    canSubmitWater: services => {
        let water = services.find(service => service.service_type === "water");
        if (water) {
            return services.some(
                service =>
                    STATUSES_FOR_WATER_SUBMIT.includes(service.status) &&
                    service.service_type === "water"
            );
        }
        return true;
    },

    getServiceObj: (services, type) =>
        services.find(svc => svc.service_type === type?.toLowerCase()),

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
                return {text: "Not Submitted", color: "black"};
            case connectionServicesMapper.STATUS_ACCEPTED:
                return {text: "Accepted", color: "green"};
            case connectionServicesMapper.STATUS_SUBMITTED:
            case connectionServicesMapper.STATUS_EA_SUBMIT:
                return {text: "In Progress", color: "green"};
            case connectionServicesMapper.STATUS_AC_MANUAL_PROCESSING:
                return {text: "Manual Processing", color: "orange"};
            case connectionServicesMapper.STATUS_CANT_CONNECT:
            case connectionServicesMapper.STATUS_REJECTED:
                return {text: "Rejected", color: "red"};
            case connectionServicesMapper.STATUS_FAILED:
                return {text: "Failed", color: "red"};
            default:
                return {
                    text: "Not Selected",
                    color: "black"
                };
        }
    },

    /**
     *
     * @param provider
     * @return {{name: string}}
     */
    mapProvider: provider => {
        switch (provider) {
            case providerNameMapper.PROVIDER_EA:
                return "Energy Australia";
            case providerNameMapper.PROVIDER_SUMO:
                return "Sumo";
            case providerNameMapper.PROVIDER_ORIGIN:
                return "Origin";
            default:
                return null;
        }
    },

    /**
     *
     * @param {string | null}  plan
     * @return {string}
     */
    mapPlan: plan => {
        return planTypeKeyMapper[plan?.toLowerCase()] || "";
    },

    clearConcessionDetails: id => LeadApplicationAPI.clearConcessionDetails(id),

    getActiveServiceTab: () => Store.getters["application/activeServiceTab"],
    setActiveServiceTab: currentTab =>
        Store.commit("application/setActiveServiceTab", currentTab),

    validateCutOff: id => LeadApplicationAPI.validateCutOff(id),
};
