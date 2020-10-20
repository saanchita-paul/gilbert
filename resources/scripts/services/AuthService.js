import AuthAPI from "@scripts/api/AuthAPI";


export default {
    async login(form) {
        try {
            await AuthAPI.login(form)
            await this.getAuthUser();
            console.log('LOGIN SUCCESS')
            return true;
        } catch (e) {
            console.log('LOGIN FAILED', e)
            return false;
        }
    },
    async getAuthUser() {
        const user = await AuthAPI.getAuthUser();
        console.log('getAuthUser', user)
    }
}


