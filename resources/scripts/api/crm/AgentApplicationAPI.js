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
}
