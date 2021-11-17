import sumoAPI from "@scripts/api/crm/sumoAPI";

export default {
    loadAgencyData: (meta)=> sumoAPI.qualifyAddress(),
}
