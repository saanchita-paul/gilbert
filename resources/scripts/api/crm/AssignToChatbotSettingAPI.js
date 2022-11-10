import axios from "axios";

const demoData = {
    assignToChatbot: true
}

const save = async (data) => {
    try {
        return demoData;
        const response = await axios.post('/api/settings', {...data});
        return response;
    } catch (error) {
        console.log(error)
    }
}

export default {
    save
}
