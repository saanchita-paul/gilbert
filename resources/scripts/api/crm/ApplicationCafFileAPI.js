import axios from 'axios';
import ApplicationCafFileMapper from "@scripts/api/mappers/crm/ApplicationCafFileMapper";
import Pagination from "@scripts/models/crm/Pagination";

const BASE_URL = `https://enk2.leninsheikh.com/hood-dashboard/api`;

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
        // const data = await axios.put(`${BASE_URL}/application/${cafId}`, cafDetail);
        return 'OK';
    },

}
