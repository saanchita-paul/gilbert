<template>
    <v-row>
            <p class="sub-title">Service Applications</p>
        <v-col cols="12">
            <v-row>
                <v-col v-for="service in services" :key="service">
                    <EnergyService @click.native="updateService(service)" :title="service"  :lead-summary="leadSummary"></EnergyService>
                </v-col>
            </v-row>
            <v-divider></v-divider>
        </v-col>

        <v-col cols="12">
            <p class="mb-0 sub-title">Which supplier would you like to connect with?</p>
            <div class="d-flex">
                <ServiceProvider v-if="serviceProviderFlag" v-for="provider in serviceProvider"
                                 :key="provider.id" :provider="provider" ></ServiceProvider>
            </div>
        </v-col>

        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>

        <v-col cols="12">
            <p class="sub-title">Select a plan for [POWER1] and [GAS2]</p>

            <div class="d-flex" v-if="plansFlag">
                <EnergyPlan v-for="plan in plans"  :key="plan.id" :plan="plan" :selectedPlan="selectedPlan" @selectPlan="planSelect">
                </EnergyPlan>
            </div>
        </v-col>

    </v-row>
</template>

<script>
import EnergyService from "@scripts/components/crm/leadmanagement/EnergyService";
import ServiceProvider from "@scripts/components/crm/leadmanagement/ServiceProvider";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import EnergyPlan from "@scripts/components/crm/leadmanagement/EnergyPlan";
export default {
  name: "ServiceApplications",
    components: {EnergyPlan, ServiceProvider, EnergyService},
    props:{
        leadSummary: {
            require: true
        }
    },

    data() {
        return {
            services:['Power', 'Gas', 'Water', 'Internet'],
            serviceProviderFlag: false,
            serviceProvider: [],
            plans: [],
            plansFlag : false,
            selectedPlan: 1
        }
    },

    methods: {
        reviewPlan() {
            //todo
        },
        planSelect(planId) {
           this.selectedPlan = planId;
           // console.log(planId);
            this.$emit('updatePlan', planId);
        },
        isActive(service) {
            return this.leadSummary.service_types.includes(service.toLowerCase())?true:false;

        },
      async  loadServiceProvider()
        {
            this.serviceProvider = await LeadApplicationService.loadServiceProvider({
                service:{
                    'power': this.activePower,
                    'gas': this.activeGas,
                    'internet': this.activeInternet,
                    'water': this.activeWater,
                }
            });
            this.serviceProviderFlag = true;
        },
        async loadPlan(serviceProvider) {
            this.plans = await LeadApplicationService.loadPlan(serviceProvider);
            this.plansFlag = true;
        },

        updateService(service) {
            this.$emit('updateService', service);
            console.log('service', service);
        }
    },

    mounted() {
      this.loadServiceProvider();
      this.loadPlan(1);

    }
};
</script>

<style scoped>
</style>
