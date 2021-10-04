import OfficeMapper from "@scripts/api/mappers/crm/OfficeMapper";
import axios from "axios";

export default {
    validateToken: async (token) => {
        try {
            const data = await axios.post('/api/invitation/validation',{token: token});
            return data.data;

        } catch (error) {
            return error.data;
        }
    },
    savePassword: async (userData,id) => {
        try {
            const data = await axios.post('/api/invitation/change-password',{...userData});
            return data.data;

        } catch (error) {
            return error.data;
        }
    },


}
