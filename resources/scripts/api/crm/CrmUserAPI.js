import AgencyMqpper from "@scripts/api/mappers/crm/AgencyMqpper";
import CrmUserMapper from "@scripts/api/mappers/crm/CrmUserMapper";
import axios from "axios";
import OfficeMapper from "@scripts/api/mappers/crm/OfficeMapper";

const data = [
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

const userData = [
    {
        id: 1,
        first_name: 'Barry Plant',
        profile_img: 500,
    },
    {
        id: 2,
        first_name: 'Barry Ahna',
        profile_img: 500,
    },
    {
        id: 3,
        first_name: 'adfWERa Plant',
        profile_img: 500,
    },
    {
        id: 4,
        first_name: 'AEREW Plant',
        profile_img: 500,
    },
];

export default {
    getUsersData: async (meta, agencyId, officeId)=> {
        try {
            // const data = await axios.get('/');
            // return CrmUserMapper.mapUserList(data);
            meta = CrmUserMapper.mapMetaData(meta);
            const data = await axios.get('/api/offices/'+ officeId + '/users',{params: {...meta}});
            return CrmUserMapper.mapUserList(data.data);

        } catch (error) {
            console.log(error);
            return error.data;
        }
    },


    getUserAllData: async (meta)=> {
        try {
            const data = await axios.get('/api/application-assignees',{params: {...meta}});
            return CrmUserMapper.mapUserList(data.data);
        } catch (error) {
            console.log(error);
            return error.data;
        }
    },

    saveUser: async (crmUserData, officeId)=> {
        try {

           const crmUser = CrmUserMapper.mapuserToServer(crmUserData, officeId);
            const data = await axios.post('/api/offices/'+ officeId+ '/users', {...crmUser});


        } catch (error) {
            console.log('user', error);
            return error.data;
        }
    },

    updateUserProfile: async (profile, id)=> {
        try {
            const data = await axios.post('/api/office-agents/'+ id+ '/update', {...profile});
        } catch (error) {
            console.log(error);
            return error.data;
        }
    },

    updateUserData: async (profile, id) =>{
        try {
            const data = await axios.post('/api/office-agents/'+ id+ '/update-user-data', {...profile});
        } catch (error) {
            console.log(error);
            return error.data;
        }
    },
}
