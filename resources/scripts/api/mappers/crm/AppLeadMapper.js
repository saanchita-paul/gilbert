function mapLead(lead) {

}

export default {
    mapLeads: (leads) => {
        return leads.map(lead=> {
            return mapLead(lead);
        })
    },
    mapLead: (leads) => {
            return mapLead(lead);
    }
};
