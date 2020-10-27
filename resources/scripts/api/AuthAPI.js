import axios from 'axios'
import User from "@scripts/models/User";

export default {
    login: async form => {
        await  axios.get('/sanctum/csrf-cookie');
        return axios.post('/login', form);
    },
    getAuthUser: async () =>  {
        const res = (await axios.get('/api/user')).data;

        axios.defaults.headers.common['BOT_ACCESS_TOKEN'] = res.bot_access_token;

        return new User({
            id: res?.user.id,
            name: res?.user.name,
            email: res?.user.email,
        });
    }
}
