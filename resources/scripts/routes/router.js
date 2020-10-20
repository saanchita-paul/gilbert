import Vue from 'vue';
import VueRouter from 'vue-router'
import DashboardLayout from "@scripts/layouts/DashboardLayout";

import CustomerAnalyticsPage from "@scripts/pages/customer/CustomerAnalyticsPage";
import AccountPage from "@scripts/pages/AccountPage";
import MessengerPage from "@scripts/pages/customer/MessengerPage";
import LoginPage from "@scripts/pages/auth/LoginPage";
import CustomerInsightPage from "@scripts/pages/customer/CustomersPage";
import SupplierInsight from "@scripts/pages/supplier/SupplierInsight";
import CustomersPage from "@scripts/pages/customer/CustomersPage";
import SuppliersPage from "@scripts/pages/supplier/SuppliersPage";
import SupplierMailPage from "@scripts/pages/supplier/SupplierMailPage";

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
                    path: 'customers',
                    component: CustomersPage,
                    name: 'customers.list'
                },
                {
                    path: 'customers/conversation',
                    component: MessengerPage,
                    name: 'customers.conversation'
                },
                {
                    path: 'suppliers/insight',
                    component: SupplierInsight,
                    name: 'suppliers.insight'
                },
                {
                    path: 'suppliers/mails',
                    component: SupplierMailPage,
                    name: 'suppliers.mails'
                },
                {
                    path: 'suppliers',
                    component: SuppliersPage,
                    name: 'suppliers.list'
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
