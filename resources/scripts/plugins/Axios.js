import axios from 'axios';
import {kickOut} from "@scripts/services/AuthService";
import router from '@scripts/routes/router';

axios.defaults.withCredentials = true;

window.BOT_API = `${process.env.MIX_BOT_ROOT_URL}/hood-dashboard/api`;

axios.interceptors.response.use(
    function(response) {
        return response;
    },
    function(error) {
        // Do something with response error
        if (error.response.status === 401 && router.currentRoute?.meta?.isProtected) {
            kickOut();
        }
        return Promise.reject(error);
    }
);
