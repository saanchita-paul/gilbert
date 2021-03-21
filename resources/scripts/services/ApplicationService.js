import mainNavigationOld, {defaultRoute} from "@scripts/data/mainNavigation_old";
import mainNavigation from "@scripts/data/mainNavigation";
import userProfile from "@scripts/data/userProfilePages";

export default {
    getMainNavigationRoutes: () => mainNavigation,
    getUserProfileMenus: () => userProfile.menus,
    getUserProfileComponents: () => userProfile.components,
    getDefaultRoute: (routeObj) =>  {
        const matchRoute = mainNavigationOld.find(route => route.route_name === routeObj?.name)
        return matchRoute || defaultRoute;
    },
}
