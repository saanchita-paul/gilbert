import axios from 'axios';
import ApplicationCafFileMapper from "@scripts/api/mappers/crm/ApplicationCafFileMapper";


export default {

    getApplicationCafFileData: async () => {
        try {
            // const data = await axios.get('/api/------');

            let data =
                [
                    {
                        "id": 29,
                        "first_name": "first_name",
                        "last_name": "ahmed",
                        "abn": null,
                        "nmi": null,
                        "mirn": "5320166372",
                        "business_name": null,
                        "service": []
                    },
                    {
                        "id": 29,
                        "first_name": "first_name",
                        "last_name": "ahmed",
                        "abn": null,
                        "nmi": null,
                        "mirn": "5320166372",
                        "business_name": null,
                        "service": []
                    },
                ];

            return ApplicationCafFileMapper.mapApplicationCafFileList(data);
        } catch (error) {
            return error.data;
        }
    },

}
