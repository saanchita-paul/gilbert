import axios from 'axios';
import ApplicationCafFileMapper from "@scripts/api/mappers/crm/ApplicationCafFileMapper";
import Pagination from "@scripts/models/crm/Pagination";

const BASE_URL = `https://enk2.leninsheikh.com/hood-dashboard/api`;

export default {

    getApplicationCafFileData: async pageIndex => {
        const data =(await axios.get(`${BASE_URL}/application?page=${pageIndex}`)).data;

        return {
            data: ApplicationCafFileMapper.mapApplicationCafFileList(data.data),

            pagination: new Pagination({
                current_page: data.meta ? data.meta.current_page : 1,
                per_page: data.meta ? data.meta.per_page : 0,
                total: data.meta ? data.meta.total : 0
            })
        };
    },

    updateApplicationCafFileData: async (cafId, cafDetail) => {
        console.log('api file caf details', cafDetail);
        const data = await axios.put(`${BASE_URL}/application/${cafId}`, cafDetail);
        console.log(data);
        return 'OK';
    },

}
