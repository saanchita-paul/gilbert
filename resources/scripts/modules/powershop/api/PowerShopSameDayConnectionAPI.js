import axios from "axios";

export default {
    async validateSameDayConnection(applicationId, submitType) {
        try {
            const data = await axios.get(`/api/applications/${applicationId}/${submitType}/same-day-connection`);
            return data.data;
        } catch (error) {
            console.log('Error while fetch same day connection API');
            return error.data;
        }
    },
};
