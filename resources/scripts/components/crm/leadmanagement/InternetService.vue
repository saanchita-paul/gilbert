<template>
  <v-card>
                <!-- <v-col cols="12" class="service-box-area">
                    <div v-for="service in services" :key="service">
                        <EnergyService @click.native="updateService(service)" :title="service"
                                       :lead-summary="leadSummary">
                        </EnergyService>
                    </div>
                </v-col> -->
                <v-col cols="12">
                    <v-divider></v-divider>
                </v-col>
                <v-col cols="12">
                    <p class="mb-0 sub-title">Which supplier would you like to connect with?</p>
                    <div class="d-flex align-content-lg-space-around">
                        <ServiceProvider @onSelectProvider="onSelectProvider(provider.name)"  v-for="provider in providers"
                                         :key="provider.name" :provider="provider"></ServiceProvider>
                    </div>
                </v-col>
                <v-col cols="12">
                    <v-divider></v-divider>
                </v-col>
                <v-col cols="12">
                    <p class="sub-title" v-if="selectPlanTitle.length > 0">Select a plan for {{selectPlanTitle}}</p>

                    <div class="d-flex" >
                        <div class="d-flex" v-for="plan in otherPlans1" :key="plan.text">
                            <SolePlan :isActive="isActivePlan" :plan="plan" @click.native="selectPlan(plan)"></SolePlan>
                        </div>
                    </div>
                    <!-- <div class="d-flex" v-if="plansFlag">
                        <div class="d-flex" v-for="plan in otherPlans" :key="plan.text">
                            <div class="your-plan active">
                                <p :style="{background: plan.bg}">{{plan.text}}</p>
                                <div class="pa-4">
                                    <v-btn @click="reviewPlan" block outlined class="mb-3">Review Plan Details</v-btn>
                                </div>
                            </div>
                        </div>
                    </div> -->
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
import SolePlan from "@scripts/components/crm/leadmanagement/SolePlan";
import ServiceProvideres from "@scripts/data/ServiceProvideres"
export default {
name: "InternetService.",
components: {SolePlan, InternetService, WaterService, EnergyPlan, ServiceProvider, EnergyService, EnergyPlanDetails},
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
                    title: 'EA1',
                    active: true,
                },
                {
                    id: 2,
                    logo: '/assets/images/SupplierLogo.png',
                    title: 'EA2',
                    active: false,
                },
                {
                    id: 3,
                    logo: '/assets/images/SupplierLogo.png',
                    title: 'EA3',

                },

            ],
            plans: [],
            plansFlag: false,
            selectedPlanType: PLAN_TYPE_TOTAL,
            viewPlanDialog: false,
            planTypeForDetails: null,
            activeService: 'energy',
            tab: null,
            origin: [ {text: 'Origin Go', bg: 'red', active: true, type: 'origin' },
             { text: 'Origin Go Variable', bg: 'blue'  , active: false, type: 'origin' },
              {text: 'Origin Basic', bg: 'orange', active: false, type: 'origin' }],
            sumo: [ {text: 'Sumo Saver', bg: 'purple' , active: false, type: 'sumo' }, { text: 'Sumo ASSURE', bg: 'blue' , active: false, type: 'sumo' }, {text: 'Sumo SELECT', bg: 'green' , active: false, type: 'sumo'}],
            servicesNew: ['Energy', 'Water', 'NVN'],
            selectedPlanTitle: '',
            selectedProviderId: 1,
            otherPlans1: null,
            isActivePlan: null
        }
    },
        methods: {
        setPlanTitle(item) {
            this.selectedPlanTitle = item.title;
        },

        updateApplicationProviders(data) {
            let payload = {
                service_type: ['internet'],
                provider_name: '',
                plan_type: ''
            }
        LeadApplicationService.updateApplicationProviders(payload , this.leadSummary.id);
        },

        onSelectProvider(providerId) {
            this.selectedProviderId = providerId

            const selectedProvider = this.providers.find(dt=> {
                return dt.name === this.selectedProviderId
            })

            this.otherPlans1 = selectedProvider.plans;
            this.isActivePlan = this.providers.default_plan;
        },
        planSelect(plan, isManual = false) {
            this.selectedPlanType = plan?.key
            this.$emit('updatePlan', plan, isManual);
        },
        selectPlan(plan){

            if(plan.type === 'origin') {

                this.origin.forEach(dt=>{
                    dt.active = false;
                })

            }

            if(plan.type === 'sumo')
            {
                this.sumo.forEach(dt=>{
                    dt.active = false;
                })
            }
            plan.active = true;
        }
    },
    computed:{
        providers() {
            return ServiceProvideres.filter((dt)=> {
                return dt.service_type === 'internet';
        });
        },
        otherPlans() {
            return this.selectedProviderId === 2
                ? this.origin
                : (this.selectedProviderId === 3 ? this.sumo : [])
            // return this.origin;
        },
        selectPlanTitle() {
            const services = this.leadSummary.service_interests;
            if (services && services.includes('gas') && services.includes('power')) {
                return 'Power & Gas';
            }
            return services && services.includes('gas')
                ? 'Gas'
                : (services && services.includes('power') ? 'Power' : '')
        },
    },
    mounted() {
        console.log('providers', this.providers);
    }

}
</script>

<style scoped>

</style>
