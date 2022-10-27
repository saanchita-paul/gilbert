import axios from "axios";

export default {
    async getAllStatus() {
        try {
            const data = await axios.get('/api/application-service-statuses');
            return data.data;
        } catch (error) {
            throw error;
        }
    },

    async updateStatus(formData) {
        try {
            const data = await axios.post('/api/application-service-statuses/change-status',{...formData});
            return data.data;
        } catch (error) {
            return error.data;
        }
    },
};
