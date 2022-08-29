import axios from "axios";

export default {
    async validateSameDayConnection(applicationId) {
        try {
            const data = await axios.get(`/api/applications/${applicationId}/same-day-connection`);
            return data.data;
        } catch (error) {
            console.log('Error while fetch same day connection API');
            return error.data;
        }
    },
};
