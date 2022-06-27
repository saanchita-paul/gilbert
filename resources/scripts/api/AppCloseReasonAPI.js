import axios from "axios";
import AppCloseReasonMapper from "@scripts/api/mappers/AppCloseReasonMapper";


export default {
    getAppCloseReasonData: async () => {
        try {                    
            const closeReasons = (await axios.get('/api/app-close-reasons')).data.data;

            return AppCloseReasonMapper.mapAppCloseReasonData(closeReasons);

        } catch (error) {
            console.log("Application Closing Reasons Fetch Error", error);
            return error.closeReasons;
        }
    }
};
