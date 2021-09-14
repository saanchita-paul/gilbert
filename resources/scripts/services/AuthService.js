import AuthAPI from "@scripts/api/AuthAPI";
import Store from '@scripts/store/index'
import User from "@scripts/models/User";
import router from '@scripts/routes/router';
import BreadcrumbService from "@scripts/services/BreadcrumbService";
import Application from "@scripts/models/crm/Application";
import ApplicationService from "@scripts/services/ApplicationService";

/**
 * get user auth state
 *
 * @return boolean
 */
const isAuthenticated = () => Store.getters.isAuthenticated;

/**
 * Login
 * @param form
 * @return {Promise<Route>}
 */
export const login = async form => {
    try {
        await AuthAPI.login(form)
        await authUser();
        console.log('LOGIN SUCCESS');

        const user = getAuthUser();

        return ApplicationService.redirectToUserHome(user.roles[0])
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
        to.meta.breadcrumbType ? setBreadcrumbs(to.meta.breadcrumbType, to.params) : setBreadcrumbs('empty', 'empty');
        console.log('logged_in', isLoggedIn)
        isLoggedIn ? next() : next({name: 'login'})
    } else {
        next()
    }
}

/**
 * get authenticated user
 *
 * @returns {User}
 */
export const getAuthUser = () => Store.getters.user;

export const getBreadcrumbs = () => Store.getters.breadcrumbs;

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

const setBreadcrumbs = (breadcrumbType, params) => {
    if(breadcrumbType === 'empty') {
        Store.commit('removeBreadcrumb')
    } else {
        BreadcrumbService.setBreadcrumb(breadcrumbType, params);
    }
}

const hasUserRoles = allowedRoles => {
    let hasRoles = false;
    Store.getters.userRoles.map(p => {
        hasRoles = hasRoles || allowedRoles.includes(p);
    });
    return hasRoles;
}

const isUniqueEmail = email =>  AuthAPI.checkUniqueEmail(email)

export default {
    getAuthUser,
    login,
    authUser,
    isAuthenticated,
    kickOut,
    checkRouteAuthorization,
    logout,
    hasUserRoles,
    hasUserPermissions,
    getBreadcrumbs,
    setBreadcrumbs,
    isUniqueEmail
}



