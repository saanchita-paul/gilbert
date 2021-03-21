import axios from 'axios';
import {kickOut} from "@scripts/services/AuthService";
import router from '@scripts/routes/router';

axios.defaults.withCredentials = true;

axios.interceptors.response.use(
    function(response) {
        return response;
    },
    function(error) {
        // Do something with response error
        if (error.response.status === 401 && router.currentRoute?.meta?.isProtected) {
            console.log(error);
            kickOut();
        }
        return Promise.reject(error);
    }
);
