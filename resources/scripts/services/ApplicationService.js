import mainNavigation, {defaultRoute} from "@scripts/data/mainNavigation";
import userProfile from "@scripts/data/userProfilePages";

export default {
    getMainNavigationRoutes: () => mainNavigation,
    getUserProfileMenus: () => userProfile.menus,
    getUserProfileComponents: () => userProfile.components,
    getDefaultRoute: () => defaultRoute,
}
