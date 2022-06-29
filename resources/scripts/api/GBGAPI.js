import axios from 'axios';

export default {

    getValidateEmail: async  => {
        return (await  axios.get(`/api/gbg-validate-email?email=${email}`));
    },
}

