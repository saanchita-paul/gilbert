import Vue from 'vue';
import VueRouter from 'vue-router'
import NewDashboardLayout from "@scripts/layouts/DashboardLayout";
import AgentDashboardLayout from "@scripts/layouts/AgentDashboardLayout";


import  LoginPage from "@scripts/pages/auth/LoginPage";

import AuthService, {checkRouteAuthorization} from "@scripts/services/AuthService";

import CustomerDetails from "@scripts/pages/CustomerDetails";
import CustomerListPage from "@scripts/pages/CustomerListTablePage";
import UtilityAnalyticPage from "@scripts/pages/dashboard/UtilityAnalyticPage";
import CustomerList from "@scripts/pages/HelpdeskPage";
import Test from "@scripts/pages/Test";
import RealStateAgency from "@scripts/pages/RealStateAgencyPage";
import RealStateAgencyPage from "@scripts/pages/RealStateAgencyPage";
import AgentApplicationPage from "@scripts/pages/agent/AgentApplicationPage";
import CrmAgencyDataTable from "@scripts/components/crm/agency/CrmAgencyDataTable";
import CrmOfficeDataTable from "@scripts/components/crm/office/CrmOfficeDataTable";
import CrmUserDatatable from "@scripts/components/crm/user/CrmUserDatatable";

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
                ,
                {
                    path: '/real-state-agency',
                    component: RealStateAgencyPage,
                    name: 'real.state.agency',
                    children: [
                        {
                            path: '',
                            name: 'real.state.agency.home',
                            component: CrmAgencyDataTable,
                            meta: {
                                isProtected: true
                            }
                        },
                        {
                            path: 'office',
                            component: CrmOfficeDataTable,
                            name: 'real.state.agency.office',
                            meta: {
                                isProtected: true
                            }

                        },
                        {
                            path: 'users',
                            component: CrmUserDatatable,
                            name: 'real.state.agency.users',
                            meta: {
                                isProtected: true
                            }

                        }
                    ],
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: '/test',
                    component: Test,
                    name: 'test',
                    meta: {
                        isProtected: true
                    }
                }

            ]
        },

        {
            path: '/auth/login',
            component: LoginPage,
            name: 'login',
            meta: {
                isProtected: false
            }
        },
        {
            path: '/agent',
            component: AgentDashboardLayout,
            children: [
                {
                    path: '',
                    component: AgentApplicationPage,
                    name: 'agent.applications',
                    meta: {
                        isProtected: true
                    }
                },
            ]
        }
    ]
})

router.beforeEach(AuthService.checkRouteAuthorization);

export default router;
