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
            const data = await axios.post('/api/application-service-statuses/change-status', {...formData});
            return data.data;
        } catch (error) {
            return error.data;
        }
    },

    async updateBulkStatus(formData) {
        try {
            const data = await axios.post('/api/application-service-statuses/change-bulk-status', formData);
            return data.data;
        } catch (error) {
            return error.data;
        }
    },

    async getAllLogs(applicationId) {
        try {
            const data = await axios.get(`/api/applications/${applicationId}/manual-status-change-logs`);
            return data.data;
        } catch (error) {
            throw error;
        }
    },

    async getServiceStatusDD(form_data) {
        try {
            const data = await axios.post(`/api/application-service-statuses/get-service-status-dd`, form_data);
            return data.data;
        } catch (error) {
            throw error;
        }
    },

    async getWaterServiceStatusDD() {
        try {
            const data = await axios.get(`/api/application-service-statuses/get-water-service-status-dd`);
            return data.data;
        } catch (error) {
            throw error;
        }
    },
};
