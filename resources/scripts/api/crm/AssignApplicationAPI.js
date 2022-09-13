import axios from "axios";

export default {

    async getOffices(param) {
        try {
            console.log('getOffices API called - ', param);
        } catch (error) {
            throw error;
        }
    },
};
