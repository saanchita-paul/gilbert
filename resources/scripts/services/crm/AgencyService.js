import AgencyAPI from "@scripts/api/crm/AgencyAPI";

export default {
    loadAgencyData: (meta)=> AgencyAPI.getAgencyAllData(meta),
    saveAgency: (agency) => AgencyAPI.saveAgency(agency),
    updateAgency: (agency, id) => AgencyAPI.updateAgency(agency,id),
    updateUserData: (agency, id) => AgencyAPI.updateUserData(agency,id),
    getAgency: (id) => AgencyAPI.getAgency(id),
    saveIndependentAgency: (agency) => AgencyAPI.saveIndependent(agency),
    loadAgencyById: (id) => AgencyAPI.getAgencyData(id),
    sendMail: (item) => AgencyAPI.sendMail(item),
    emailUpdateValidationRule: (email, userId) => AgencyAPI.emailUpdateValidationRule(email, userId)
}
