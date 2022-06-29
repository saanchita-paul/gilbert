import GBGAPI from "../api/GBGAPI";


export default {

    validateEmail: (email) => GBGAPI.getValidateEmail($email),

}
