import Office from "@scripts/models/crm/Office";
import PaginationMapper from "@scripts/api/mappers/crm/PaginationMapper";
import COMMISSION from "@scripts/data/constants/COMMISSION";

function mapOffice(office) {
    return new Office({...office});
}

function mapOfficeDetails(office){

    return {
        ...office
    }

    }


function mapCommissions (commissions){
    return commissions.map(cmtn => {
        const commission = {
            id: cmtn.id,
            rate: cmtn.rate,
        };

        switch (cmtn.type) {
            case 1:
                commission.text = COMMISSION.GAS.text;
                break;
            case 2:
                commission.text = COMMISSION.INTERNET.text;
                break;
            case 3:
                commission.text = COMMISSION.POWER.text;
                break;
            case 4:
                commission.text = COMMISSION.WATER.text;
                break;
            case 5:
                commission.text = COMMISSION.SPONSORSHIP.text;
                break;
            default:
                break;
        }
        return commission;
    });
}

   function mapAgent (agent) {
    return {
        ...agent,
        // full_name: agent.first_name + ' ' + agent.last_name,
    }
}

function mapHoodProfile(hoodUsers) {

    return hoodUsers.map(userProfile => {
        return {
            ...userProfile,
            name: userProfile.first_name + ' ' + userProfile.last_name
        };
    })
}


export default {
    mapOfficeList: (officeList)=> {

        const offices =  officeList?.data.map(office=> {
            return mapOffice(office);
        });

        const pagination =  PaginationMapper.mapPagination(officeList?.meta);

        return {
            offices: offices,
            pagination: pagination,
        };
    },
    mapOffice: (office) => {
        return mapOffice(office);
    },

    mapOfficeToserver: (officedData, agency) => {
        let office = null;
        let agent = null;
        let office_commissions = null;
        let mri_office = null;

        let ofc = officedData.office;
        let agPro = officedData.allocator;
        let commission = officedData.profile;
        let mriOfc = officedData.mriOffice;

        office = {
                agency_id: agency,
                address: ofc.address,
                name: ofc.title,
                street_address: ofc.street_address,
                city: ofc.city,
                state: ofc.state,
                postcode: ofc.postcode,
                abn: ofc.abn,
                phone: ofc.contact,
                email: ofc.email,
                rent_roll: ofc.rent_roll,
                hood_agent_id: ofc.hood_agent_id,
            };
        agent = {
                first_name: agPro.first_name,
                last_name: agPro.last_name,
                email: agPro.email,
                f_id_12: agPro.last_name,
                phone: agPro.phone_number,
                role: 'agency_office_allocator'
            };
        office_commissions = [
                {
                    type: COMMISSION.GAS.type,
                    rate: commission.gas,
                },
                {
                    type: COMMISSION.INTERNET.type,
                    rate: commission.internet,
                },
                {
                    type: COMMISSION.POWER.type,
                    rate: commission.power,
                },
                // {
                //     type: COMMISSION.WATER.type,
                //     rate: commission.water,
                // },
            {
                type: COMMISSION.SPONSORSHIP.type,
                rate: commission.sponsorship,
            },
            ];

        mri_office = {
            key: mriOfc.key,
            company_name: mriOfc.company_name,
            activation_date: mriOfc.activation_date
        }


        return {
            office: office,
            agent: agent,
            office_commissions: office_commissions,
            mri_office: mri_office
        }
    },

    mapMetaData: (meta) => {
            if(meta.sort_by === 'title') meta.sort_by = 'name';
            if(meta.sort_by === 'last_updated') meta.sort_by = 'updated_at';
            if(meta.sort_by === 'user_account') meta.sort_by = 'agents_count';
            if(meta.sort_by === 'total_leads') meta.sort_by = 'applications_count';
            return meta;
    },




    mapOfficeCommissionAgent: (data)=> {
        let office = mapOfficeDetails(data);
        office.agency_name = office.agency.name;
        office.agency_type = office.agency.type;
        office.agency_id = office.agency.id;
        let commissions = mapCommissions(data.commissions);
        let agent = mapAgent(data.agent);
        let hood_users = mapHoodProfile(data?.hood_users);
        return {
            office: office,
            commissions: commissions,
            agent: agent,
            hood_users: hood_users
        }
    },

    mapHoodProfileData: (data) => {
        return mapHoodProfile(data);
    }



}
