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
    },

    mapOfficeToServer: (ofc) => {
        let office = null;
        let attocator = null;
            office = {
                name: ofc.title,
                street_address: ofc.street_address,
                city: ofc.city,
                state: ofc.state,
                postcode: ofc.postcode,
                country: ofc,
                abn: ofc.abn,
                phone: ofc.contact,
                email: ofc.email,
            }
    },

    mapAgencytoServer: (agency) => {
        let office = null;
        let agent_profile = null;
        let office_commissions = null;
        let newAgency = {
            name: agency.title,
            type: agency.type
        };

        if(agency.type === 'Independent Agency') {
            let ofc = agency.office;
            let agPro = agency.allocator;
            let commission = agency.profile;
            office = {
                name: ofc.title,
                street_address: ofc.street_address,
                city: ofc.city,
                state: ofc.state,
                postcode: ofc.postcode,
                country: ofc,
                abn: ofc.abn,
                phone: ofc.contact,
                email: ofc.email,
            };
            agent_profile = {
                first_name: agPro.first_name,
                last_name: agPro.last_name,
                email: agPro.email,
                f_id_12: agPro.last_name,
                phone: agPro.phone_number,
            };
            office_commissions = [
                {
                    type: 'gas',
                    rate: commission.gas,
                },
                {
                    type: 'internet',
                    rate: commission.internet,
                },
                {
                    type: 'power',
                    rate: commission.power,
                },
                {
                    type: 'water',
                    rate: commission.water,
                },
            ];
        }

        return {
            ...newAgency,
            office: office,
            agent_profile: agent_profile,
            office_commissions: office_commissions,
        }
    }
}
