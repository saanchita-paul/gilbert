import Vue from 'vue';
import VueRouter from 'vue-router'
import NewDashboardLayout from "@scripts/layouts/NewDashboardLayout";

import CustomerAnalyticsPage from "@scripts/pages/customer/CustomerAnalyticsPage";

import LoginPage from "@scripts/pages/auth/LoginPage";

import AuthService, {checkRouteAuthorization} from "@scripts/services/AuthService";

import CustomerDetails from "@scripts/pages/customer/CustomerDetails";

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
                    component: CustomerAnalyticsPage,
                    name: 'dashboard',
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
            path: '/auth/login',
            component: LoginPage,
            name: 'login',
            meta: {
                isProtected: false
            }
        }
    ]
})

 router.beforeEach(AuthService.checkRouteAuthorization);

export default router;
