import axios from "axios";

const ROOT = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

export default {
    saveNote: async note => {
        try {
            return (await axios.post(`${ROOT}/application-note`, note)).data;
        } catch (error) {
            console.log('error', error);
            return null;
        }
    }
}
