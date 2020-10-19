import AuthAPI from "@scripts/api/AuthAPI";

export default {
    login: form => {
        AuthAPI.login(form)
    }
}
