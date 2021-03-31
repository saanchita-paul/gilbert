import axios from 'axios'
import User from "@scripts/models/User";

export default {
    login: async form => {
        await  axios.get('/sanctum/csrf-cookie');
        return axios.post('/login', form);
    },
    logout: () => {
        axios.get('/api/logout');
    },
    getAuthUser: async () =>  {
        const res = (await axios.get('/api/user')).data;

        axios.defaults.headers.common['Bot-Access-Token'] = res.bot_access_token;

        return new User({
            id: res?.user.id,
            name: res?.user.name,
            email: res?.user.email,
        });
    },

    checkBotAuth: () => {
        axios.get(`${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api/test`)
    }
}
