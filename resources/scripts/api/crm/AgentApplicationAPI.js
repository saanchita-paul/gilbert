import ApplicationMapper from "@scripts/api/mappers/crm/ApplicationMapper";
const applications = [
    {
        id: 1,
        applicant_name: 'Staedtler Marker',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_types: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
    {
        id: 2,
        applicant_name: 'Applicant Name goes here',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_types: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
    {
        id: 3,
        applicant_name: 'Applicant Name goes here',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_types: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
    {
        id: 4,
        applicant_name: 'Applicant Name goes here',
        moving_date: '06/08/2021',
        phone: '0410758782',
        service_types: ['power', 'gas', 'internet'],
        status: 'Not Confirmed Connection',
    },
];
const application = {
        id: 1,
        applicant_name: 'Staedtler Marker',
        date_of_birth: '06/08/2021',
        phone: '0410758782',
        email: 'staedtler.marker@gmail.com',
        moving_date: '02/22/2022',
        email_billing: 'Email/Paper',
        tenancy_type: 'Renter or Home Owner',
        service_address: '398 Bourke Road, Camberwell 3124 VIC',
        service_types: ['power', 'gas', 'internet'],
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
}
