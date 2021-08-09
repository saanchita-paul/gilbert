import Agency from "@scripts/models/crm/Agency";
import PaginationMapper from "@scripts/api/mappers/crm/PaginationMapper";
import COMMISSION from "@scripts/data/constants/COMMISSION";

function mapAgency(agency) {
    return new Agency({...agency})
}

export default {
    mapAgencyList: (agencyList)=> {
        const agencies =  agencyList?.data.map(agency=> {
            return mapAgency(agency);
        });

       const pagination =  PaginationMapper.mapPagination(agencyList?.meta);

       return {
           agencies: agencies,
           pagination: pagination,
       };

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
        let agent = null;
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
            agent = {
                first_name: agPro.first_name,
                last_name: agPro.last_name,
                email: agPro.email,
                f_id_12: agPro.f_id_12,
                phone: agPro.phone_number,
                role: 'agency_office_allocator'
            };
            office_commissions = [
                {
                    type: COMMISSION.GAS,
                    rate: commission.gas,
                },
                {
                    type: COMMISSION.INTERNET,
                    rate: commission.internet,
                },
                {
                    type: COMMISSION.POWER,
                    rate: commission.power,
                },
                {
                    type: COMMISSION.WATER,
                    rate: commission.water,
                },
            ];
        }

        if(newAgency.type  === 'Independent Agency')
        {
            return {
                ...newAgency,
                office: office,
                agent: agent,
                office_commissions: office_commissions,
            }
        }

        return {
            ...newAgency
        }


    },

    mapMetaData: (meta) => {
        if(meta.sort_by === 'title') meta.sort_by = 'name';
        if(meta.sort_by === 'last_updated') meta.sort_by = 'updated_at';
        if(meta.sort_by === 'offices') meta.sort_by = 'offices_count';
        if(meta.sort_by === 'total_leads') meta.sort_by = 'applications_count';
        return meta;
    }
}
