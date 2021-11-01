<template>
    <v-row>
        <v-tabs
            v-model="tab"
            height="75px"
            style="min-width: 200px !important;"
        >

            <v-tab class="px-0 py-3 tab-capital-case">
                <v-card class="hood-card" width="100%">
                    <p class="pt-2 pb-1 mb-0 services service-title">
                              <span class="ml-1">
                                  <v-icon color="yellow">mdi-flash</v-icon>Energy
                              </span>
                        </p>
                        <p class="py-0 my-0 pl-4 service-status active-power-subtitle"
                        >
                            {{getenegryServiceStatus}}</p>
                </v-card>
            </v-tab>
            <v-tab  class="px-0 py-3 tab-capital-case" >
                <v-card  class="hood-card" width="100%">
                        <p class="pt-2 pb-1 mb-0 services service-title">
                              <span class="ml-1">
                                  <v-icon  color="blue" >mdi-water</v-icon>Water
                              </span>
                        </p>
                        <p class="py-0 my-0 pl-4 service-status active-power-subtitle">
                            {{getwaterServiceStatus}}
                            <!--                    Connected-->
                        </p>
                </v-card>
            </v-tab>
            <v-tab  class="py-3 px-0 tab-capital-case"  >
                      <v-card  class="hood-card" width="100%">
                      <div>
                    <p class="pt-2 pb-1 mb-0 services service-title">
                          <span class="ml-1">
                               <v-icon  color="red">mdi-wifi</v-icon>Internet
                          </span>
                    </p>
                    <p class="py-0 my-0 pl-4 service-status active-power-subtitle active-power-subtitle"
                    >
                        {{getinternetServiceStatus}}
                    </p>
                      </div>
                      </v-card>
                  </v-tab>


            <v-tab-item>
                <v-card >
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
                    <div class="d-flex align-content-lg-space-around">
                        <ServiceProvider @onSelectProvider="onSelectProvider(provider.id)" v-if="serviceProviderFlag" v-for="provider in serviceProvider"
                                         :key="provider.id" :provider="provider"></ServiceProvider>
                    </div>
                </v-col>
                <v-col cols="12">
                    <v-divider></v-divider>
                </v-col>
                <v-col cols="12">
                    <p class="sub-title" v-if="selectPlanTitle.length > 0">Select a plan for {{selectPlanTitle}}</p>

                    <div class="d-flex" v-if="plansFlag && selectedProviderId === 1">
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
                    <div class="d-flex" v-if="plansFlag">
                        <div class="d-flex" v-for="plan in otherPlans" :key="plan.text">
                            <div class="your-plan active">
                                <p :style="{background: plan.bg}">{{plan.text}}</p>
                                <div class="pa-4">
                                    <v-btn @click="reviewPlan" block outlined class="mb-3">Review Plan Details</v-btn>
                                </div>
                            </div>
                        </div>
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
                </v-card>

            </v-tab-item>
            <v-tab-item>
                <WaterService :leadSummary="leadSummary" @updateStatus="updateStatus"></WaterService>
            </v-tab-item>
            <v-tab-item>
                <InternetService></InternetService>
            </v-tab-item>
        </v-tabs>

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
import WaterService from "@scripts/components/crm/leadmanagement/WaterService";
import InternetService from "@scripts/components/crm/leadmanagement/InternetService";
import {isNull} from "lodash-es";


export default {
    name: "ServiceApplications",
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
            serviceProvider: [],
            plans: [],
            plansFlag: false,
            selectedPlanType: PLAN_TYPE_TOTAL,
            viewPlanDialog: false,
            planTypeForDetails: null,
            activeService: 'energy',
            tab: null,
            origin: [ {text: 'Origin Go', bg: 'red' }, { text: 'Origin Go Variable', bg: 'blue'}, {text: 'Origin Basic', bg: 'orange'}],
            sumo: [ {text: 'Sumo Saver', bg: 'purple' }, { text: 'Sumo ASSURE', bg: 'blue'}, {text: 'Sumo SELECT', bg: 'green'}],
            servicesNew: ['Energy', 'Water', 'NVN'],
            selectedProviderId: 1,
            waterStatus: null,
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
        },
        otherPlans() {
            return this.selectedProviderId === 2
                ? this.origin
                : (this.selectedProviderId === 3 ? this.sumo : [])
        },
        getwaterServiceStatus() {
            if(isNull(this.waterStatus)) {
                this.waterStatus =  this.getServiceStatus('water');
            }
            return this.waterStatus;
        },
        getenegryServiceStatus() {
            return this.getServiceStatus('energy');
        },
        getinternetServiceStatus() {
            return this.getServiceStatus('internet');
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
        },

        getServiceStatus(status) {

            if(status === 'water') {
                const newServices = this.leadSummary?.connection_services?.find(svc=>{
                return svc.service_type === 'water';
                });

                if(newServices)
                {
                    return this.mapStatus(newServices.status);
                }
                return 'cann\'t connect';

            }

            return 'Connected';
        },

        mapStatus(statusCode) {

            let statustext = '';
            switch (statusCode){
                case 4:
                    statustext = 'Submitted';
                    break;
                case  5:
                    statustext = 'Connected';
                    break;
                case  7:
                    statustext = 'In Progress';
                    break;
                case  9:
                    statustext = 'Can’t Connect';
                    break;
                case  10:
                    statustext = 'Needs more info';
                    break;
                default:
                    break;
            }

            console.log('statustext' , statustext);
            return statustext;
        },

        updateService1(service) {
            this.activeService = service;
        },

        isServiceActive(service) {
            if(this.activeService === service) return true;
            return  false;
        },
        onSelectProvider(providerId) {
            this.selectedProviderId = providerId
        },

        updateStatus(text) {
            this.waterStatus = text;
        }
    },
};
</script>

<style scoped>

.active-power-subtitle{
    font-size: 12px !important;
}

.tab-capital-case {
    text-transform: capitalize !important;
    width:180px !important;
}
.service-title {
 font-size: 18px;
    font-weight: bold;
}

</style>
