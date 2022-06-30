import axios from 'axios';

export default {
    getValidateEmail: async (email) => {

        const isGbgValidateEmail = await axios.get(`/api/gbg-validate-email?email=${email}`);

        console.log("email", email);
        console.log("isGbgValidateEmail object", isGbgValidateEmail);
        console.log("isGbgValidateEmail data", isGbgValidateEmail.data);
        console.log("isGbgValidateEmail data", isGbgValidateEmail.data.data);

        return isGbgValidateEmail.data.data;

    },
}

