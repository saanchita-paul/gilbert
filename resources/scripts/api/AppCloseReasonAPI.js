import axios from "axios";
import AppCloseReasonMapper from "@scripts/api/mappers/AppCloseReasonMapper";

// const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;
const ROOT = `http://localhost:8002/api`;
// const ROOT = "https://demo.chatbot.hood.ai/hood-dashboard/api"

export default {
    getAppCloseReasonData: async () => {
        try {
            const closeReasons = (await axios.get(`${ROOT}/app-close-reasons`)).data.data;

            return AppCloseReasonMapper.mapAppCloseReasonData(closeReasons);
            
        } catch (error) {
            console.log("Application Closing Reasons Fetch Error", error);
            return error.closeReasons;
        }
    }
};
