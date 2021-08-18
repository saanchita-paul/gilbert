import AuthAPI from "@scripts/api/AuthAPI";
import Store from '@scripts/store/index'
import User from "@scripts/models/User";
import router from '@scripts/routes/router';

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
        console.log('LOGIN SUCCESS');

        const user = await AuthAPI.getAuthUser();
        if(user.roles.includes('agency_office_real_estate_agent')) {
            await router.push({name: 'agent.application.dashboard'})
        } else {
            return true;
        }
        // return true;

    } catch (e) {
        console.log('LOGIN FAILED', e)
        return false;
    }
}

export const logout = () => {
    kickOut();
    AuthAPI.logout();
}

/**
 * update user in store
 *
 * @return {Promise<void>}
 */
export const authUser = async () => {
    const user = await AuthAPI.getAuthUser();
    // AuthAPI.checkBotAuth(); //for testing purpose
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
        isLoggedIn ? next({name: 'dashboard.utility'}) : next()
    }
}

/**
 * get authenticated user
 *
 * @returns {User}
 */
export const getAuthUser = () => Store.getters.user;

export const kickOut = () => {
    Store.commit('setUser', null);
    router.push({name: 'login'})
}


const hasUserPermissions = allowedPermissions => {
    let hasPermission = false;
    Store.getters.userPermissions.map(p => {
        hasPermission = hasPermission || allowedPermissions.includes(p);
    });
    return hasPermission;
}

const hasUserRoles = allowedRoles => {
    let hasRoles = false;
    Store.getters.userRoles.map(p => {
        hasRoles = hasRoles || allowedRoles.includes(p);
    });
    return hasRoles;
}

export default {
    getAuthUser,
    login,
    authUser,
    isAuthenticated,
    kickOut,
    checkRouteAuthorization,
    logout,
    hasUserRoles,
    hasUserPermissions
}



