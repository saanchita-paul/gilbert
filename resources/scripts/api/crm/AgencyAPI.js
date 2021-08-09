import axios from "axios";
import AgencyMqpper from "@scripts/api/mappers/crm/AgencyMqpper";

const data = [
    {
        id: 1,
        name: 'Barry Plant',
        total_leads: 500,
        last_updated: '00/00/2021',
        offices: 30,
    },
    {
        id: 2,
        name: 'Raine & Horne',
        total_leads: 100,
        last_updated: '06/00/2021',
        offices: 100,
    },
    {
        id: 3,
        name: '[EA Homes]_(Independent)',
        total_leads: 300,
        last_updated: '07/00/2021',
        offices: 30,
    },
    {
        id: 4,
        name: 'Raine & Horne',
        total_leads: 200,
        last_updated: '08/00/2021',
        offices: 10,
    },
];

export default {
    getAgencyAllData: async () => {
        try {
            console.log(process.env.APP_URL);

            const data = await axios.get('api/agencies');
            console.log('agency data', data.data);
            return AgencyMqpper.mapAgencyList(data.data.data);
        } catch (error) {
            return error.data;
        }
    },

    saveAgency: (agency) => {

        try {
            agency = AgencyMqpper.mapAgencytoServer(agency);
            const p = {
                id: data.length + 1,
                ...AgencyMqpper.mapAgency(agency)
            };
           return p;

        } catch (error) {
            return error.data;
        }
    }
}
