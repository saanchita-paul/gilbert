import ApplicationMapper from "@scripts/api/mappers/crm/ApplicationMapper";
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
        address_unit: '398',
        address_apartment: 'Bourke Road',
        city: 'Camberwell',
        state: 'VIC',
        country: 'Australia',
        postcode: 3124,
        additional_instruction: 'Additional Instructions goes here. Maybe it’s extra long so I have ' +
            'to write something down to show how it will look like when a property manager has soooo ' +
            'much things to say on his lead.'
}

export default {
    getApplicationMetrics() {
        return null;
    },
    getApplicationList:  () => {
        try {
            return ApplicationMapper.mapApplicationList(applications);
        } catch (error) {
            return error.data;
        }
    },
    getApplicationSummary:  (id) => {
        try {
            // get application summary by application id
            return ApplicationMapper.mapApplicationSummary(application);
        } catch (error) {
            return error.data;
        }
    },
    async createApplication(application) {
        // return axios.post(`url`, application);
        return true;
    },
}
