import AuthAPI from "@scripts/api/AuthAPI";
import Store from '@scripts/store/index'
import User from "@scripts/models/User";

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
    AuthAPI.checkBotAuth();
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

/**
 * get authenticated user
 *
 * @returns {User}
 */
export const getAuthUser = () => Store.getters.user;

export default {
    getAuthUser,
    login,
    authUser,
    isAuthenticated,
    checkRouteAuthorization
}



