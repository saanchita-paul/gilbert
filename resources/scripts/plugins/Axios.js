import axios from 'axios';

axios.interceptors.response.use(
    function(response) {
        console.log(response, "AXIOS MIDDLEWARE")
        return response;
    },
    function(error) {
        // Do something with response error
        if (error.response.status === 401) {
            console.log(error)
        }
        return Promise.reject(error);
    }
);
