import Office from "@scripts/models/crm/Office";

function mapOffice(office) {
    return new Office({...office});
}

export default {
    mapOfficeList: (officeList)=> {
        return officeList.map(office=> {
            return mapOffice(office);
        })
    },
    mapOffice: (office) => {
        return mapOffice(office.office);
    },

    mapOfficeToserver: (officedData, agency) => {
        let office = null;
        let agent_profile = null;
        let office_commissions = null;

        let ofc = officedData.office;
        let agPro = officedData.allocator;
        let commission = officedData.profile;
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


        return {
            agency: agency,
            office: office,
            agent_profile: agent_profile,
            office_commissions: office_commissions,
        }
    }

}
