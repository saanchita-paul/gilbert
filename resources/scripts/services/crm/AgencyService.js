import AgencyAPI from "@scripts/api/crm/AgencyAPI";

export default {
    loadAgencyData: (meta)=> AgencyAPI.getAgencyAllData(meta),
    saveAgency: (agency) => AgencyAPI.saveAgency(agency),
    updateAgency: (agency, id) => AgencyAPI.updateAgency(agency,id),
    saveIndependentAgency: (agency) => AgencyAPI.saveIndependent(agency),

}
