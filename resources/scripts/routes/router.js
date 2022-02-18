import Vue from 'vue';
import VueRouter from 'vue-router'
import NewDashboardLayout from "@scripts/layouts/DashboardLayout";


import LoginPage from "@scripts/pages/auth/LoginPage";

import ForgotPasswordPage from "@scripts/pages/auth/ForgotPasswordPage";

import ResetPasswordPage from "@scripts/pages/auth/ResetPasswordPage";

import {checkRouteAuthentication} from "@scripts/services/AuthService";

import CustomerDetails from "@scripts/pages/CustomerDetails";
import CustomerListPage from "@scripts/pages/CustomerListTablePage";
import UtilityAnalyticPage from "@scripts/pages/dashboard/UtilityAnalyticPage";
import CustomerList from "@scripts/pages/HelpdeskPage";
import Test from "@scripts/pages/Test";
import RealStateAgencyPage from "@scripts/pages/RealStateAgencyPage";
import CrmAgencyDataTable from "@scripts/components/crm/agency/CrmAgencyDataTable";
import CrmOfficeDataTable from "@scripts/components/crm/office/CrmOfficeDataTable";
import CrmUserDatatable from "@scripts/components/crm/user/CrmUserDatatable";
import LeadApplications from "@scripts/components/crm/leadmanagement/LeadApplications";
import ApplicationPage from "@scripts/pages/ApplicationPage";
import ApplicationDetailsPage from "@scripts/pages/ApplicationDetailsPage";
import OfficeProfile from "@scripts/components/crm/office/OfficeProfile";
import InviteUser from "@scripts/components/crm/user/InviteUser";
import ApplicationsDashboardPage from "@scripts/modules/sales/pages/ApplicationsDashboardPage";
import SalesEnergyPage from "@scripts/modules/sales/pages/SalesEnergyPage";
import SalesWaterPage from "@scripts/modules/sales/pages/SalesWaterPage";
import ApplicationSearchList from '@scripts/pages/ApplicationSearchList'
import agent_routes from "@scripts/routes/agent_routes";


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
                        isProtected: true,
                        roles: ['hood_admin'],
                    }
                },
                {
                    path: '/chatbot',
                    component: UtilityAnalyticPage,
                    name: 'chatbot',
                    meta: {
                        isProtected: true,
                        roles: ['hood_admin'],

                    }
                },
                {
                    path: '/customers',
                    component: CustomerListPage,
                    name: 'customer.list',
                    meta: {
                        isProtected: true,
                        roles: ['hood_admin'],
                    }
                },
                {
                    path: 'customers/:id',
                    component: CustomerDetails,
                    name: 'customer.details',
                    meta: {
                        isProtected: true,
                        roles: ['hood_admin'],
                    },
                    props: true
                },
                {
                    path: '/helpdesk',
                    component: CustomerList,
                    name: 'helpdesk',
                    meta: {
                        isProtected: true,
                        roles: ['hood_admin'],
                    }
                },
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
                                isProtected: true,
                                breadcrumbType: 'AgencyList',
                                header: 'Real Estate Agencies',
                                roles: ['hood_admin', 'hood_agent'],

                            }
                        },
                        {
                            path: ':id/offices',
                            component: CrmOfficeDataTable,
                            name: 'real.state.agency.office',
                            meta: {
                                isProtected: true,
                                breadcrumbType: 'AgencyOffices',
                                header: 'Real Estate Agencies',
                                roles: ['hood_admin', 'hood_agent'],
                            },
                            props: true

                        },
                        {
                            path: ':id/offices/:officeId',
                            component: CrmUserDatatable,
                            name: 'real.state.agency.users',
                            meta: {
                                isProtected: true,
                                breadcrumbType: 'AgencyUsers',
                                header: 'Real Estate Agencies',
                                roles: ['hood_admin', 'hood_agent'],
                            },
                            props: true

                        },
                        {
                            path: ':id/offices/:officeId/profile',
                            component: OfficeProfile,
                            name: 'real.state.office.profile',
                            meta: {
                                isProtected: true,
                                breadcrumbType: 'OfficeProfile',
                                header: 'Real Estate Agencies',
                                roles: ['hood_admin', 'hood_agent'],
                            },
                            props: true

                        }

                    ],
                    meta: {
                        isProtected: true,
                        roles: ['hood_admin', 'hood_agent'],
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
                    children:[
                        {
                            path: '',
                            name: 'application.list',
                            component: ApplicationSearchList,
                            meta: {
                                isProtected: true,
                                breadcrumbType: 'LeadApplications',
                                roles: [
                                    'hood_admin',
                                    'hood_team_lead',
                                    'hood_customer_rep',
                                    'hood_external_team_lead',
                                    'hood_external_customer_rep'
                                ],
                            },
                            props: true
                        },
                    ],
                    meta: {
                        isProtected: true,
                        breadcrumbType: 'LeadApplications',
                        roles: [
                            'hood_admin',
                            'hood_team_lead',
                            'hood_customer_rep',
                            'hood_external_team_lead',
                            'hood_external_customer_rep'
                        ],
                    }
                },
                {
                    path: '/applications/:id',
                    component: ApplicationDetailsPage,
                    name: 'applications.details',
                    meta: {
                        isProtected: true,
                        breadcrumbType: 'ApplicationsDetails',
                        roles: [
                            'hood_admin',
                            'hood_team_lead',
                            'hood_customer_rep',
                            'hood_external_team_lead',
                            'hood_external_customer_rep'
                        ],
                    }
                },
                {
                    path: 'applications-dashboard',
                    component: ApplicationsDashboardPage,
                    name: 'applications.dashboard',
                    children: [
                        {
                            path: 'energy',
                            component: SalesEnergyPage,
                            name: 'sales.energy',
                            meta: {
                                isProtected: true,
                                breadcrumbType: 'SalesEnergy',
                                roles: [
                                    'hood_admin',
                                    'hood_agent',
                                    'hood_customer_rep',
                                    'hood_team_lead'
                                ],

                            }
                        },
                        {
                            path: 'water',
                            component: SalesWaterPage,
                            name: 'sales.water',
                            meta: {
                                isProtected: true,
                                breadcrumbType: 'SalesWater',
                                roles: [
                                    'hood_admin',
                                    'hood_agent',
                                    'hood_customer_rep',
                                    'hood_team_lead'
                                ],
                            },
                            props: true
                        }
                    ],
                    meta: {
                        isProtected: true,
                        roles: [
                            'hood_admin',
                            'hood_agent',
                            'hood_customer_rep',
                            'hood_team_lead'
                        ],
                    }
                },
            ]
        },
        {...agent_routes},
        {
            path: '/auth/login',
            component: LoginPage,
            name: 'login',
            meta: {
                isProtected: false
            }
        },
        {
            path: '/forgot/password',
            component: ForgotPasswordPage,
            name: 'forgot.password',
            meta: {
                isProtected: false
            }
        },
        {
            path: '/reset/password/:token',
            component: ResetPasswordPage,
            name: 'reset.password',
            meta: {
                isProtected: false
            }
        },
        {
           path: '/confirm-invitation',
           component: InviteUser,
           name:'confirm.user.invite',
           meta: {
               isProtected: false
           }
        }
    ]
})

router.beforeEach(checkRouteAuthentication);

export default router;
