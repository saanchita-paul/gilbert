import Vue from 'vue';
import VueRouter from 'vue-router'
import DashboardLayout from "@scripts/layouts/DashboardLayout";

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: [
        {
            path: '/',
            component: DashboardLayout
        }
    ]
})

export default router;
