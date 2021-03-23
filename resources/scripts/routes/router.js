import Vue from 'vue';
import VueRouter from 'vue-router'
import NewDashboardLayout from "@scripts/layouts/NewDashboardLayout";

import CustomerAnalyticsPage from "@scripts/pages/customer/CustomerAnalyticsPage";

import LoginPage from "@scripts/pages/auth/LoginPage";

import AuthService, {checkRouteAuthorization} from "@scripts/services/AuthService";

import CustomerDetails from "@scripts/pages/customer/CustomerDetails";
import UtilityAnalyticPage from "@scripts/pages/dashboard/UtilityAnalyticPage";
import CustomerList from "@scripts/pages/customer/customer-profile/CustomerList";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            component: NewDashboardLayout,
            children: [
                {
                    path: '',
                    component: UtilityAnalyticPage,
                    name: 'dashboard.utility',
                    meta: {
                        isProtected: true
                    }
                },
            ]
        },
        {
            path: '/hello',
            component: CustomerDetails,
            name: 'custmerdetail',
            meta: {
                isProtected: false
            }
        },
        {
            path: '/customer/list',
            component: CustomerList,
            name: 'custmerlist',
            meta: {
                isProtected: false
            }
        },
        {
            path: '/auth/login',
            component: LoginPage,
            name: 'login',
            meta: {
                isProtected: false
            }
        }
    ]
})

// router.beforeEach(AuthService.checkRouteAuthorization);

export default router;
