import axios from "axios";
import AgencyMqpper from "@scripts/api/mappers/crm/AgencyMqpper";
import PaginationMapper from "@scripts/api/mappers/crm/PaginationMapper";

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
    getAgencyAllData: async (meta) => {
        try {
            meta = AgencyMqpper.mapMetaData(meta);
            const data = await axios.get('/api/agencies',{params: {...meta}});
            return AgencyMqpper.mapAgencyList(data.data);
        } catch (error) {
            return error.data;
        }
    },

    getAgencyData: async (id) => {
        try {
            const data = await axios.get('/api/agencies/'+id);
            return AgencyMqpper.mapAgency(data.data.data);

        } catch (error) {
            return error.data;
        }
    },

     saveAgency: async (agency) => {
        try {
            agency = AgencyMqpper.mapAgencytoServer(agency);
            const data = await axios.post('/api/agencies',{...agency});
            return AgencyMqpper.mapAgency( data.data.data);

        } catch (error) {
            return error.data;
        }
    },

    updateUserData: async (agency,id) => {
        try {

            const data = await axios.post('/api/office-agents/' + id +'/update-user-data',{...agency});
            return AgencyMqpper.mapAgency( data.data.data);

        } catch (error) {
            return error.data;
        }
    },

    updateAgency: async (agency,id) => {
        try {

            const data = await axios.post('/api/agencies/'+ id +'/update',{...agency});
            return AgencyMqpper.mapAgency( data.data.data);

        } catch (error) {
            return error.data;
        }
    },

    saveIndependent: async (agency) => {
        try {
            agency = AgencyMqpper.mapAgencytoServer(agency);
            const data = await axios.post('/api/independent-agency',{...agency});
            return AgencyMqpper.mapAgency( data.data.data);

        } catch (error) {
            return error.data;
        }
    },

    getAgency: async (id) => {
        try {
            const agency = await axios.get('/api/agencies/'+ id);
            return AgencyMqpper.mapAgency( agency.data.data);
        } catch (error) {
            return error.data;
        }
    },

    sendMail: async (item) => {
        try {
            const response = await axios.post('/api/office-agents/' + item.id + '/sendConfirmMail') ;

            return response.status === 200?true : false;
        } catch (error) {
            console.log(error)
            return error.data;
        }
    },


}
