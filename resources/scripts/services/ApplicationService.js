import mainNavigation from "@scripts/data/mainNavigation";
import userProfile from "@scripts/data/userProfilePages";

export default {
    getMainNavigationRoutes: () => mainNavigation,
    getUserProfileMenus: () => userProfile.menus,
    getUserProfileComponents: () => userProfile.components,

    getRandomString: () => {
        return  '_' + Math.random().toString(36).substr(2, 9);
    }
}
