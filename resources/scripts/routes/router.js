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
import AuthService, {checkRouteAuthorization} from "@scripts/services/AuthService";
import CustomerProfilePage from "@scripts/pages/customer/customer-profile/CustomerProfilePage";
import CustomerDetails from "@scripts/pages/customer/CustomerDetails";

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
                    name: 'dashboard',
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: 'customers',
                    component: CustomersPage,
                    name: 'customers.list',
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: 'customers/profile/:customerId',
                    component: CustomerProfilePage,
                    name: 'customers.profile',
                    props: true,
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: 'customers/conversation',
                    component: MessengerPage,
                    name: 'customers.conversation',
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: 'suppliers/insight',
                    component: SupplierInsight,
                    name: 'suppliers.insight',
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: 'suppliers/mails',
                    component: SupplierMailPage,
                    name: 'suppliers.mails',
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: 'suppliers',
                    component: SuppliersPage,
                    name: 'suppliers.list',
                    meta: {
                        isProtected: true
                    }
                }
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
