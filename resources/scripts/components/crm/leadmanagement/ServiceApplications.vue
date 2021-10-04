<template>
    <v-row>
        <v-col cols="12" class="pb-0">
            <p class="sub-title mb-0">Service Applications</p>
        </v-col>
        <v-col cols="12" class="service-box-area">
            <div v-for="service in services" :key="service">
                <EnergyService @click.native="updateService(service)" :title="service"
                               :lead-summary="leadSummary"></EnergyService>
            </div>
        </v-col>
        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>
        <v-col cols="12">
            <p class="mb-0 sub-title">Which supplier would you like to connect with?</p>
            <div class="d-flex">
                <ServiceProvider v-if="serviceProviderFlag" v-for="provider in serviceProvider"
                                 :key="provider.id" :provider="provider"></ServiceProvider>
            </div>
        </v-col>

        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>

        <v-col cols="12">
            <p class="sub-title" v-if="selectPlanTitle.length > 0">Select a plan for {{selectPlanTitle}}</p>

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

        <v-dialog
            v-model="viewPlanDialog"
            max-width="500"
            v-if="viewPlanDialog && planTypeForDetails"
        >
            <v-card>
                <EnergyPlanDetails
                    :plan="planTypeForDetails"
                    :postcode="leadSummary.postcode"
                    :services="leadSummary.service_interests"
                    :state="leadSummary.state"
                />
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="green darken-1"
                        text
                        @click="viewPlanDialog = false"
                    >
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
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


export default {
    name: "ServiceApplications",
    components: {EnergyPlan, ServiceProvider, EnergyService, EnergyPlanDetails},
    props: {
        leadSummary: {
            require: true
        }
    },

    data() {
        return {
            services: ['Power', 'Gas', 'Water', 'Internet'],
            serviceProviderFlag: false,
            serviceProvider: [],
            plans: [],
            plansFlag: false,
            selectedPlanType: PLAN_TYPE_TOTAL,
            viewPlanDialog: false,
            planTypeForDetails: null
        }
    },
    computed: {
        selectPlanTitle() {
            const services = this.leadSummary.service_interests;
            if (services && services.includes('gas') && services.includes('power')) {
                return 'Power & Gas';
            }
            return services && services.includes('gas')
                ? 'Gas'
                : (services && services.includes('power') ? 'Power' : '')
        }
    },
    watch: {
        'leadSummary.service_interests'() {
            this.loadPlan();
        }
    },
    mounted() {
        this.loadServiceProvider();
        this.loadPlan();
        this.planSelect(EA_PLAN_TYPES.find(p => p.key === PLAN_TYPE_TOTAL))

    },
    methods: {
        reviewPlan() {
            //todo
        },

        planSelect(plan, isManual = false) {
            this.selectedPlanType = plan?.key
            this.$emit('updatePlan', plan, isManual);
        },
        isActive(service) {
            return this.leadSummary.service_types.includes(service.toLowerCase()) ? true : false;

        },
        async loadServiceProvider() {
            this.serviceProvider = await LeadApplicationService.loadServiceProvider({
                service: {
                    'power': this.activePower,
                    'gas': this.activeGas,
                    'internet': this.activeInternet,
                    'water': this.activeWater,
                }
            });
            this.serviceProviderFlag = true;
        },
        async loadPlan() {
            // this.plans = await LeadApplicationService.loadPlan(serviceProvider);
            const services = this.leadSummary.service_interests;
            if (services.includes('gas') || services.includes('power')) {
                this.plans = await EAPlanService.getAllPlans({
                    service_type: this.leadSummary.service_interests,
                    postcode: this.leadSummary.postcode,
                    state: this.leadSummary.state
                });
                this.plansFlag = true;
            } else {
                this.plansFlag = false;
            }
        },

        updateService(service) {
            this.$emit('updateService', service);
        },

        view(plan) {
            this.planTypeForDetails = plan.key;
            this.viewPlanDialog = true;
        }
    },
};
</script>

<style scoped>
</style>
