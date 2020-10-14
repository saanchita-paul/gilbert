import Vue from 'vue';
import VueRouter from 'vue-router'
import DashboardLayout from "@scripts/layouts/DashboardLayout";

import CustomerAnalyticsPage from "@scripts/pages/CustomerAnalyticsPage";
import AccountPage from "@scripts/pages/AccountPage";
import MessengerPage from "@scripts/pages/MessengerPage";

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
                    path: 'messenger',
                    component: MessengerPage,
                    name: 'messenger'
                }
            ]
        }
    ]
})

export default router;
