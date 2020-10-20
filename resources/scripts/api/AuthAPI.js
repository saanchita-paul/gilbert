import axios from 'axios'
import User from "@scripts/models/User";

export default {
    login: async form => {
        await  axios.get('/sanctum/csrf-cookie');
        return axios.post('/login', form);
    },
    getAuthUser: async () =>  {
        const res = (await axios.get('/api/user')).data;
        return new User({
            id: res.id,
            name: res.name,
            email: res.email
        });
    }
}
