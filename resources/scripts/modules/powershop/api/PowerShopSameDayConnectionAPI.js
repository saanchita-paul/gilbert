import axios from "axios";

export default {
    async validateSameDayConnection(applicationDetails) {
        try {
            console.log('Before API call: ', applicationDetails);
            const response = await axios.get('/api/applications/same-day-connection', { data: { applicationDetails: applicationDetails } });
            console.log('Response ', response);
            return response.data;
        } catch (error) {
            return error.data;
        }
    },
};
