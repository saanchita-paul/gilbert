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

    async getAgents(search, office_id) {
        try {
            const data = await axios.get(`/api/offices/${office_id}/all-agents-for-assign-applications`, {params: {search}});
            return data.data;
        } catch (error) {
            throw error;
        }
    },

    async saveSelectedApplications(applications) {
        try {
            const data = await axios.post('/api/offices/assign-applications',{...applications});
            return data.data;
        } catch (error) {
            return error.data;
        }
    },
};
