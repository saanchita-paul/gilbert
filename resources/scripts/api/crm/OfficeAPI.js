import axios from "axios";
import OfficeMapper from "@scripts/api/mappers/crm/OfficeMapper";

const data = [
    {
        id: 1,
        title: 'Barry Plant',
        total_leads: 500,
        last_updated: '00/00/2021',
        user_account: 30,
    },
    {
        id: 2,
        title: 'Raine & Horne',
        total_leads: 100,
        last_updated: '06/00/2021',
        user_account: 100,
    },
    {
        id: 3,
        title: '[EA Homes]_(Independent)',
        total_leads: 300,
        last_updated: '07/00/2021',
        offices: 30,
    },
    {
        id: 4,
        title: 'Raine & Horne',
        total_leads: 200,
        last_updated: '08/00/2021',
        user_account: 10,
    },
];

export default {
    getOfficeAllData: async (meta, agencyId) => {
        try {

            meta = OfficeMapper.mapMetaData(meta);
            const data = await axios.get('/api/agencies/'+ agencyId + '/offices',{params: {...meta}});
            return OfficeMapper.mapOfficeList(data.data);

        } catch (error) {
            return error.data;
        }
    },

    saveOfficeData: async (officeData, agencyId) => {
        try {
            officeData = OfficeMapper.mapOfficeToserver(officeData , agencyId);
            const data = await axios.post('/api/offices',{...officeData});
            return OfficeMapper.mapOffice(data.data.data);

        } catch (error) {
            return error.data;
        }
    },

    getOfficeData: async (id) => {
        try {
            const data = await axios.get('/api/offices/'+id);
            return OfficeMapper.mapOfficeCommissionAgent(data.data.data);

        } catch (error) {

            console.log('error', error);
            return error.data;
        }
    },

    getOnlyOfficeData: async (id) => {
        try {
            const data = await axios.get('/api/offices/office/'+id);
            return data.data.data;

        } catch (error) {
            return error.data;
        }
    },

    updateOffice: async (office, id) => {

        try {
            const data = await axios.post('/api/offices/'+id+'/update',{...office});

        } catch (error) {
            console.log(error)
            return error.data;
        }
    }
}
