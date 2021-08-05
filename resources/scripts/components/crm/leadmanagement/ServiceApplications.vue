<template>
    <v-row>
            <p class="sub-title">Service Applications</p>
        <v-col cols="12">
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

            <div class="d-flex">
                <EneryPlan v-for="plan in plans"  :key="plan.id" :plan="plan" :selectedPlan="selectedPlan" @selectPlan="planSelect">
                </EneryPlan>
            </div>
        </v-col>

    </v-row>
</template>

<script>
import EneryService from "@scripts/components/crm/leadmanagement/EneryService";
import ServiceProvider from "@scripts/components/crm/leadmanagement/ServiceProvider";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import EneryPlan from "@scripts/components/crm/leadmanagement/EneryPlan";
export default {
  name: "ServiceApplications",
    components: {EneryPlan, ServiceProvider, EneryService},
    props:{
        leadSummary: {
            require: true
        }
    },

    data() {
        return {
            activePower: false,
            activeGas: false,
            activeWater: false,
            activeInternet: false,
            serviceFlag: false,
            selectedService: [],
            serviceProviderFlag: false,
            serviceProvider: [],
            plans: [],
            selectedPlan: 1
        }
    },

    methods: {
        reviewPlan() {
            //todo
        },
        planSelect(planId) {
           this.selectedPlan = planId;
           console.log(planId);
            this.$emit('updatePlan', planId);
        },
        isActive(service) {
            return this.leadSummary.service_types.includes(service.toLowerCase())?true:false;

        },
        inializingServiceProps() {
            this.activePower = this.isActive('Power');
            this.activeGas = this.isActive('Gas');
            this.activeWater = this.isActive('Water');
            this.activeInternet = this.isActive('Internet');
            this.serviceFlag = true;

        },

        toggleEnegry(service) {
                if(service.toLowerCase() === 'power') {
                    this.activePower =  !this.activePower
                }
                if(service.toLowerCase() === 'gas') {
                    this.activeGas = !this.activeGas
                }
                if(service.toLowerCase() === 'internet') {
                    this.activeInternet = !this.activeInternet
                }
                if(service.toLowerCase() === 'water') {
                    this.activeWater = !this.activeWater
                }
                this.loadServiceProvider();
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
        }
    },

    mounted() {
      this.inializingServiceProps();
      this.loadServiceProvider();
      this.loadPlan(1);

    }
};
</script>

<style scoped>
</style>
