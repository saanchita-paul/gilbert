import Vue from 'vue';
import VueRouter from 'vue-router'
import NewDashboardLayout from "@scripts/layouts/NewDashboardLayout";


import  LoginPage from "@scripts/pages/auth/LoginPage";

import AuthService, {checkRouteAuthorization} from "@scripts/services/AuthService";

import CustomerDetails from "@scripts/pages/CustomerDetails";
import CustomerListPage from "@scripts/pages/CustomerListTablePage";
import UtilityAnalyticPage from "@scripts/pages/dashboard/UtilityAnalyticPage";
import CustomerList from "@scripts/pages/HelpdeskPage";

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
                {
                    path: '/chatbot',
                    component: UtilityAnalyticPage,
                    name: 'chatbot',
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: '/customers',
                    component: CustomerListPage,
                    name: 'customer.list',
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: 'customers/:id',
                    component: CustomerDetails,
                    name: 'customer.details',
                    meta: {
                        isProtected: true
                    },
                    props: true
                },
                {
                    path: '/helpdesk',
                    component: CustomerList,
                    name: 'helpdesk',
                    meta: {
                        isProtected: true
                    }
                },

            ]
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
