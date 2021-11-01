<template>
    <v-card>
                <v-col cols="12">
                    <v-divider></v-divider>
                </v-col>
                <v-col cols="12">
                    <p class="mb-0 sub-title">Which supplier would you like to connect with?</p>

                    <div class="d-flex">
                        <!-- <ServiceProvider  v-for="provider in serviceProvider"
                                         :key="provider.id" :provider="provider" @click="setPlanTitle(provider)"></ServiceProvider> -->
                        
                        <img class="my-3" v-for="provider in serviceProvider"
                                         :key="provider.id" v-bind:src="provider.logo" @click="()=>setPlanTitle(provider)" width="150px">

                    </div>
                </v-col>

                <v-col cols="12">
                    <v-divider></v-divider>
                </v-col>
                <v-col cols="12">
                    <p class="sub-title" >Select a plan for {{selectedPlanTitle}}</p>

                    <div class="d-flex" v-if="plansFlag">
                        <EnergyPlan
                            v-for="plan in plans"
                            :key="plan.key"
                            :plan="plan"
                            :selectedPlan="selectedPlanType"
                            @selectPlan="planSelect"
                            @view="view"
                            @click.native="planSelect(plan,true)"
                        ></EnergyPlan>
                    </div>
                </v-col>

    </v-card>
</template>

<script>

import EnergyService from "@scripts/components/crm/leadmanagement/EnergyService";
import ServiceProvider from "@scripts/components/crm/leadmanagement/ServiceProvider";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import EnergyPlan from "@scripts/components/crm/leadmanagement/EnergyPlan";
import EnergyApi from "@scripts/api/ea/EnergyApi";
import EAPlanService from "@scripts/services/ea/EAPlanService";
import {EA_PLAN_TYPES, PLAN_TYPE_TOTAL} from "@scripts/models/ea/EnergyPlan";
import EnergyPlanDetails from "@scripts/components/ea/EnergyPlanDetails";
import WaterService from "@scripts/components/crm/leadmanagement/WaterService";
import InternetService from "@scripts/components/crm/leadmanagement/InternetService";

export default {
name: "InternetService.",
components: {InternetService, WaterService, EnergyPlan, ServiceProvider, EnergyService, EnergyPlanDetails},
props: {
    leadSummary: {
        require: true
    }
},
    data() {
        return {
            services: ['Power', 'Gas'],
            serviceProviderFlag: false,
            serviceProvider : [
    {
        id: 1,
        logo: '/assets/images/SupplierLogo.png',
        title: 'EA1'
    },
    {
        id: 2,
        logo: '/assets/images/SupplierLogo.png',
        title: 'EA2'
    },
    {
        id: 3,
        logo: '/assets/images/SupplierLogo.png',
        title: 'EA3'
    },

],
            plans: [],
            plansFlag: false,
            selectedPlanType: PLAN_TYPE_TOTAL,
            viewPlanDialog: false,
            planTypeForDetails: null,
            activeService: 'energy',
            tab: null,
            servicesNew: ['Energy', 'Water', 'NVN'],
            selectedPlanTitle: '',
        }
    },
        methods: {
        setPlanTitle(item) {
            this.selectedPlanTitle = item.title;
        }
    },

}
</script>

<style scoped>

</style>
