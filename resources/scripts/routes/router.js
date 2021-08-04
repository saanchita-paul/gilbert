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
import RealStateAgencyPage from "@scripts/pages/RealStateAgencyPage";
import AgentApplicationPage from "@scripts/pages/agent/AgentApplicationPage";
import AgentCreateNewApplication from "@scripts/components/crm/agent/AgentCreateNewApplication";
import CrmAgencyDataTable from "@scripts/components/crm/agency/CrmAgencyDataTable";
import CrmOfficeDataTable from "@scripts/components/crm/office/CrmOfficeDataTable";
import CrmUserDatatable from "@scripts/components/crm/user/CrmUserDatatable";
import LeadApplications from "@scripts/components/crm/leadmanagement/LeadApplications";
import ApplicationPage from "@scripts/pages/ApplicationPage";
import ApplicationDetailScreen from "@scripts/components/crm/leadmanagement/ApplicationDetailScreen";
import ApplicationDetailsPage from "@scripts/pages/ApplicationDetailsPage";

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
                    path: '/agencies',
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
                            path: ':id/offices',
                            component: CrmOfficeDataTable,
                            name: 'real.state.agency.office',
                            meta: {
                                isProtected: true
                            },
                            props: true

                        },
                        {
                            path: ':id/offices/:officeId/users',
                            component: CrmUserDatatable,
                            name: 'real.state.agency.users',
                            meta: {
                                isProtected: true
                            },
                            props: true

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
                },
                {
                    path: '/applications',
                    component: ApplicationPage,
                    name: 'applications',
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: '/applications/:id',
                    component: ApplicationDetailsPage,
                    name: 'applications.details',
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
                    name: 'agent.application.dashboard',
                    meta: {
                        isProtected: true
                    }
                },
                {
                    path: '/create-application',
                    component: AgentCreateNewApplication,
                    name: 'agent.create.application',
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
