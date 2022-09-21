import axios from "axios";
import AppCloseReasonMapper from "@scripts/api/mappers/AppCloseReasonMapper";


export default {
    getAppCloseReasonData: async () => {
        try {                  
            const params = {
                active: { toJSON: () => true},
                ordered: { toJSON: () => true}
            };  
            const closeReasons = (await axios.get('/api/app-close-reasons', { params })).data.data;

            return AppCloseReasonMapper.mapAppCloseReasonData(closeReasons);

        } catch (error) {
            console.log("Application Closing Reasons Fetch Error", error);
            return error.closeReasons;
        }
    }
};
