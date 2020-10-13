import Vue from 'vue';
import VueRouter from 'vue-router'
import DashboardLayout from "@scripts/layouts/DashboardLayout";
import CustomerAnalyticsPage from "@scripts/pages/CustomerAnalyticsPage";
import AccountPage from "@scripts/pages/AccountPage";

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
                    name: 'customers'
                },
                {
                    path: '/account',
                    component: AccountPage,
                    name: 'account'
                }
            ]
        }
    ]
})

export default router;
