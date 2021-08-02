import Agency from "@scripts/models/crm/Agency";

function mapAgency(agency) {
    return new Agency({...agency})
}

export default {
    mapAgencyList: (agencyList)=> {
        return agencyList.map(agency=> {
            return mapAgency(agency);
        })
    },

    mapAgency: (agency) => {
        return mapAgency(agency);
    }
}
