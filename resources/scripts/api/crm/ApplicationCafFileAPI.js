import axios from 'axios';
import ApplicationCafFileMapper from "@scripts/api/mappers/crm/ApplicationCafFileMapper";


export default {

    getApplicationCafFileData: async () => {
        try {
            // const data = await axios.get('/api/------');

            let data =
                [
                    {
                        "id": 1,
                        "title": "Mr",
                        "first_name": "Shakil",
                        "middle_name": "",
                        "last_name": "Hossain",
                        "abn": null,
                        "nmi": null,
                        "mirn": "5320166372",
                        "business_name": null,
                        "service": []
                    },
                    {
                        "id": 2,
                        "title": "Mrs",
                        "first_name": "First",
                        "middle_name": "No",
                        "last_name": "Last",
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
