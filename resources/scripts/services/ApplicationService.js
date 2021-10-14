import mainNavigation from "@scripts/data/mainNavigation";
import userProfile from "@scripts/data/userProfilePages";
import UserHome from "@scripts/data/UserHome";
import router from "@scripts/routes/router";

export default {
    getMainNavigationRoutes: () => mainNavigation,
    getUserProfileMenus: () => userProfile.menus,
    getUserProfileComponents: () => userProfile.components,

    getRandomString: () => {
        return '_' + Math.random().toString(36).substr(2, 9);
    },
    /**
     * redirect base on role
     *
     * @param {Array} roles
     *
     * @return {Promise<Route>}
     */
    redirectToUserHome: roles => {
        const res = UserHome.find(item => item.roles.includes(roles[0]))
        if (res) {
            return router.push({name: res.route_name})
        }
        throw new Error(`Unhandled role (${roles[0]}) at [redirectToUserHome]`)
    }
}
