import axios from 'axios';

export default {
    getValidateEmail: async (email) => {

        const isGbgValidateEmail = await  axios.get(`/api/gbg-validate-email?email=${email}`);

        // console.log("isGbgValidateEmail", isGbgValidateEmail);

        return isGbgValidateEmail.data;

    },
}

