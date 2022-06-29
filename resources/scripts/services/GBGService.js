import GBGAPI from "@scripts/api/GBGAPI";

export default {

    validateEmail: (email) => GBGAPI.getValidateEmail(email),
}
