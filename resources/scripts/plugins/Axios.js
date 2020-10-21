import axios from 'axios';
import router from '@scripts/routes/router';

axios.interceptors.response.use(
    function(response) {
        console.log(response, "AXIOS MIDDLEWARE")
        return response;
    },
    function(error) {
        // Do something with response error
        if (error.response.status === 401 && !(router.currentRoute.name === 'login')) {
            console.log(error);
            router.push({name: 'login'})
        }
        return Promise.reject(error);
    }
);
