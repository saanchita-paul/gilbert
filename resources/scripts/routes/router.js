import Vue from 'vue';
import VueRouter from 'vue-router'
import DashboardLayout from "@scripts/layouts/DashboardLayout";

import CustomerAnalyticsPage from "@scripts/pages/CustomerAnalyticsPage";
import AccountPage from "@scripts/pages/AccountPage";
import MessengerPage from "@scripts/pages/MessengerPage";
import LoginPage from "@scripts/pages/auth/LoginPage";
import CustomerInsightPage from "@scripts/pages/CustomerInsightPage";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            component: DashboardLayout,
            children: [
                {
                    path: '',
                    component: CustomerAnalyticsPage,
                    name: 'customerAnalytics'
                },
                {
                    path: 'account',
                    component: AccountPage,
                    name: 'account'
                },
                {
                    path: 'customers',
                    component: CustomerInsightPage,
                    name: 'customerInsight'
                },
                {
                    path: 'messenger',
                    component: MessengerPage,
                    name: 'messenger'
                }
            ]
        },
        {
            path: '/auth/login',
            component: LoginPage,
            name: 'login'
        }
    ]
})

export default router;
