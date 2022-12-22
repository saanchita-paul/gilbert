import axios from 'axios';
import ApplicationCafFileMapper from "@scripts/api/mappers/crm/ApplicationCafFileMapper";
import GilbertApplicationCafFileMapper from "@scripts/api/mappers/crm/GilbertApplicationCafFileMapper";
import Pagination from "@scripts/models/crm/Pagination";


const BASE_URL = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

export default {

    getApplicationCafFileData: async (sort_search_meta, params) => {
        const data = await axios.get(`${BASE_URL}/applications`,{params:{...sort_search_meta, ...params}});

        return {
            data: ApplicationCafFileMapper.mapApplicationCafFileList(data.data),

            pagination: new Pagination({
                current_page: data.data.meta ? data.data.meta.current_page : 1,
                per_page: data.data.meta ? data.data.meta.per_page : 0,
                total: data.data.meta ? data.data.meta.total : 0
            })
        };
    },


    updateApplicationCafFileData: async (cafId, cafDetail) => {
        try{
            const data = await axios.put(`${BASE_URL}/applications/${cafId}`, cafDetail);
            let response = data.data.data
            return ApplicationCafFileMapper.mapSingleData(response)
        } catch (e) {
            console.log('updating fail due to below reason')
            console.log(e);
            return false;
        }

    },

    rejectionReasonCafFileData: async (rejectionReason) => {
        try{
            const data = await axios.put(`${BASE_URL}/rejection-reasons/`, rejectionReason);
            let response = data.data.data
            return ApplicationCafFileMapper.mapSingleData(response)
        } catch (e) {
            console.log('No rejection reason found')
            console.log(e);
            return false;
        }

    },

    getGilbertApplicationData: async (sort_search_meta, params) => {
        const data = await axios.get('/api/powershop/applications',{params:{...sort_search_meta, ...params}});

        return {
            data: GilbertApplicationCafFileMapper.mapGilbertApplicationList(data.data),

            pagination: new Pagination({
                current_page: data.data.meta ? data.data.meta.current_page : 1,
                per_page: data.data.meta ? data.data.meta.per_page : 0,
                total: data.data.meta ? data.data.meta.total : 0
            })
        };
    },

    generateGilbertCafFIle: async (params) => {
        try{
            const url = `/api/powershop/generate-caf?ids=`+ params;

            window.open(
                url,
                '_blank'
            );
        } catch (e) {
            console.log(e);
        }
    }

}
