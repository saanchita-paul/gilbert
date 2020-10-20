import axios from 'axios'

export default {
    login: async form => {
        await  axios.get('/sanctum/csrf-cookie');
        return axios.post('/login', form);
    },
    getAuthUser: async () => (await axios.get('/api/user')).data
}
