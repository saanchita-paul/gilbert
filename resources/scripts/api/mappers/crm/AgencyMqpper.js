import Agency from "@scripts/models/crm/Agency";

function mapAgency(agency) {
    console.log({...agency});
    return new Agency({...agency})
}

export default {
    mapAgencyList: (agencyList)=> {
        return agencyList.map(agency=> {
            return mapAgency(agency);
        })
    },

    mapAgency: (agency) => {
        console.log('Agency', agency);
        return mapAgency(agency);
    }
}
