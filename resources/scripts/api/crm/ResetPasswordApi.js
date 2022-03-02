import AgencyMqpper from "@scripts/api/mappers/crm/AgencyMqpper";
import axios from "axios";

export default {
    checkIsValidToken : async (data) => {
        try {
            const response = await axios.post('/api/check-is-valid-token', {...data});
            return response.data.is_valid_token;
          return false;
        } catch (error) {
            console.log('api error', error);

            return error.data;
        }
    },
    savePassword : async (data) => {
        try {
            const response = await axios.post('/api/reset-password', {...data});
            return response.data;
        } catch (error) {
            console.log('api error', error);
            return error.data;
        }
    },

}
