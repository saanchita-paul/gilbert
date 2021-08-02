import AgencyMqpper from "@scripts/api/mappers/crm/AgencyMqpper";
import CrmUserMapper from "@scripts/api/mappers/crm/CrmUserMapper";

const crmUser = [
    {
        id: 1,
        proerty_manager_name: 'Barry Plant',
        submitted_lead: 500,
        role: 'AAAA',
        mobile: '424352345',
        Offices: 30,
        email: 'sazzadahmed41@gamil.com'
    },
    {
        id: 2,
        proerty_manager_name: 'Raine & Horne',
        submitted_lead: 100,
        role: 'AAAA',
        mobile: '424352345',
        Offices: 100,
        email: 'sazzadahmed41@gamil.com'
    },
    {
        id: 3,
        proerty_manager_name: '[EA Homes]_(Independent)',
        submitted_lead: 300,
        role: 'AAAA',
        mobile: '424352345',
        Offices: 30,email: 'sazzadahmed41@gamil.com'
    },
    {
        id: 4,
        proerty_manager_name: 'Raine & Horne',
        submitted_lead: 200,
        role: 'AAAA',
        mobile: '424352345',
        email: 'sazzadahmed41@gamil.com'
    },
];

export default {
    getUsersData: ()=> {
        try {
            // const data = await axios.get('/');
            console.log(data);
            return CrmUserMapper.mapUserList(data);

        } catch (error) {
            return error.data;
        }
    }
}
