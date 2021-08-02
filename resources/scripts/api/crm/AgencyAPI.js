import axios from "axios";
import AgencyMqpper from "@scripts/api/mappers/crm/AgencyMqpper";

const data = [
    {
        id: 1,
        agency_name: 'Barry Plant',
        total_leads: 500,
        last_updated: '00/00/2021',
        offices: 30,
    },
    {
        id: 2,
        agency_name: 'Raine & Horne',
        total_leads: 100,
        last_updated: '06/00/2021',
        offices: 100,
    },
    {
        id: 3,
        agency_name: '[EA Homes]_(Independent)',
        total_leads: 300,
        last_updated: '07/00/2021',
        offices: 30,
    },
    {
        id: 4,
        agency_name: 'Raine & Horne',
        total_leads: 200,
        last_updated: '08/00/2021',
        offices: 10,
    },
];

export default {
    getAgencyAllData:  () => {
        try {
            // const data = await axios.get('/');
            return AgencyMqpper.mapAgencyList(data);

        } catch (error) {
            return error.data;
        }
    },
}
