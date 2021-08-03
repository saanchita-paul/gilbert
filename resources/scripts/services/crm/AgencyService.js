import AgencyAPI from "@scripts/api/crm/AgencyAPI";

export default {
    loadAgencyData: ()=> AgencyAPI.getAgencyAllData(),
    saveAgency: (agency) => AgencyAPI.saveAgency(agency),

}
