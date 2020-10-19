import Vue from 'vue';
import VueRouter from 'vue-router'
import DashboardLayout from "@scripts/layouts/DashboardLayout";

import CustomerAnalyticsPage from "@scripts/pages/CustomerAnalyticsPage";
import AccountPage from "@scripts/pages/AccountPage";
import MessengerPage from "@scripts/pages/MessengerPage";
import LoginPage from "@scripts/pages/auth/LoginPage";
import CustomerInsightPage from "@scripts/pages/CustomerInsightPage";
import SupplierInsight from "@scripts/pages/SupplierInsight";

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
                    name: 'dashboard'
                },
                {
                    path: 'insight/customers',
                    component: CustomerInsightPage,
                    name: 'insight.customers'
                },                {
                    path: 'insight/suppliers',
                    component: SupplierInsight,
                    name: 'insight.suppliers'
                },
                {
                    path: 'messenger/customers',
                    component: MessengerPage,
                    name: 'messenger.customers'
                },
                {
                    path: 'messenger/suppliers',
                    component: MessengerPage,
                    name: 'messenger.suppliers'
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
