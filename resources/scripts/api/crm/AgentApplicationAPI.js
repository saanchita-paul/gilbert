import ApplicationMapper from "@scripts/api/mappers/crm/ApplicationMapper";
import axios from "axios";
import AgentListMapper from "@scripts/api/mappers/crm/AgentListMapper";

const applications = [
    {
        id: 1,
        first_name: 'Staedtler',
        last_name: 'Marker',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_interests: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
    {
        id: 2,
        first_name: 'Applicant Name',
        last_name: 'goes here',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_interests: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
    {
        id: 3,
        first_name: 'Applicant Name',
        last_name: 'goes here',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_interests: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
    {
        id: 4,
        first_name: 'Applicant Name',
        last_name: 'goes here',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_interests: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
];
const application = {
        id: 1,
        first_name: 'Staedtler',
        last_name: 'Marker',
        date_of_birth: '06/08/2021',
        phone: '0410758782',
        email: 'staedtler.marker@gmail.com',
        moving_date: '02/22/2022',
        email_billing: 'Email',
        tenancy_type: 'Home Owner',
        service_interests: ['power', 'gas', 'internet'],
        address_unit: '56/2',
        street_address: 'Bourke Road',
        city: 'Camberwell',
        state: 'VIC',
        country: 'Australia',
        postcode: 3124,
        address_text: '2/56 Bradman Dr, Sunbury VIC 3429, Australia',
        additional_instruction: 'Additional Instructions goes here. Maybe it’s extra long so I have ' +
            'to write something down to show how it will look like when a property manager has soooo ' +
            'much things to say on his lead.'
}

export default {
    getApplicationMetrics() {
        return null;
    },
    getApplicationList:  async (sort_search_meta) => {
        try {
            let data = await axios.get('/api/applications',{params:{...sort_search_meta}});
            return ApplicationMapper.mapApplicationList(data.data);
        } catch (error) {
            return error.data;
        }
    },
    getApplicationSummary: async (id) => {
        try {
            // get application summary by application id
            let response = (await axios.get(`/api/applications/${id}`)).data;
            return ApplicationMapper.mapApplicationSummary(response.data);
        } catch (error) {
            return error.data;
        }
    },
    async createApplication(application) {

        application = ApplicationMapper.mapToServer(application);
        return await axios.post(`/api/applications`, {...application});

    },
    loadAgentList: async (meta, agencyId, officeId)=> {
        try {
            meta = AgentListMapper.mapMetaData(meta);
            const data = await axios.get('/api/offices/'+ officeId + '/agents', {params: {...meta}});
            return AgentListMapper.mapAgentList(data.data);
        } catch (error) {
            console.log('Error', error);
            return error.data;
        }
    },
}
