import axios from "axios";


export const get = async () => {
    try {
        return (await axios.get('/api/settings/is-chatbot-office')).data;
    } catch (error) {
        console.log(error)
    }
}

export const save = async (data) => {
    try {
        return (await axios.post('/api/settings/is-chatbot-office', {...data})).data;
    } catch (error) {
        console.log(error)
    }
}

export default {
    get,
    save
}
