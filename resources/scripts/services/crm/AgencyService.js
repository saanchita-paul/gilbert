import AgencyAPI from "@scripts/api/crm/AgencyAPI";

export default {
    loadAgencyData: (meta)=> AgencyAPI.getAgencyAllData(meta),
    saveAgency: (agency) => AgencyAPI.saveAgency(agency),
    saveIndependentAgency: (agency) => AgencyAPI.saveIndependent(agency),
    loadAgencyById: (id) => AgencyAPI.getAgencyData(id)
}
