import AuthAPI from "@scripts/api/AuthAPI";
import Store from '@scripts/store/index'

/**
 * get user auth state
 *
 * @return boolean
 */
const isAuthenticated = () => Store.getters.isAuthenticated;

/**
 * Login
 * @param form
 * @return {Promise<boolean>}
 */
export const login = async form => {
    try {
        await AuthAPI.login(form)
        await authUser();
        console.log('LOGIN SUCCESS')
        return true;
    } catch (e) {
        console.log('LOGIN FAILED', e)
        return false;
    }
}

/**
 * update user in store
 *
 * @return {Promise<void>}
 */
export const authUser = async () => {
    const user = await AuthAPI.getAuthUser();
    Store.commit('setUser', user)
}

/**
 * route authorization middleware
 *
 * @param to
 * @param from
 * @param next
 */
export const checkRouteAuthorization = (to, from, next) => {
    const isLoggedIn = isAuthenticated();
    if (to.meta.isProtected) {
        isLoggedIn ? next() : next({name: 'login'})
    } else {
        isLoggedIn ? next({name: 'dashboard'}) : next()
    }
}

export default {
    login,
    authUser,
    isAuthenticated,
    checkRouteAuthorization
}

export const a =  {
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
        Store.commit('setUser', user)
        console.log('getAuthUser', user)
    },
    isAuthenticated() {
        return Store.getters.isAuthenticated;
    },

    checkAuthorization(to, from, next) {
        const isLoggedIn = isAuthenticated();
        if (to.meta.isProtected) {
            isLoggedIn ? next() : next({name: 'login'})
        } else {
            isLoggedIn ? next({name: 'dashboard'}) : next()
        }
    }
}


