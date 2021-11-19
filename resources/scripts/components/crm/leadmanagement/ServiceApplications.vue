<template>
    <v-row class="ml-0">
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
                    <p class="sub-title ml-4 pt-5 mb-2" >Service Applications</p>
                    <p class="ml-4 mb-0">Energy</p>
                <v-col cols="12" class="service-box-area">
                    <div v-for="service in services" :key="service">
                        <EnergyService @click.native="updateService(service)" :title="service"
                                       :lead-summary="leadSummary">
                        </EnergyService>
                    </div>
                </v-col>
                <v-col cols="12">
                    <v-divider></v-divider>
                </v-col>
                <v-col cols="12">
                    <p class="mb-0 sub-title">Which supplier would you like to connect with?</p>
                    <div class="d-flex align-content-lg-space-around mt-2">
                        <ServiceProvider @onSelectProvider="onSelectProvider(provider.name)" v-if="serviceProviderFlag" v-for="provider in serviceProvider"
                                         :key="provider.id" :selectedProvider="selectedPowerProvider" :provider="provider"></ServiceProvider>

                        <ServiceProvider @onSelectProvider="onSelectProvider1(provider.name)" v-if="serviceProviderFlag" v-for="provider in providers"
                                         :key="provider.name" :selectedProvider="selectedPowerProvider" :provider="provider"></ServiceProvider>

                    </div>
                </v-col>
                <v-col cols="12">
                    <v-divider></v-divider>
                </v-col>
                <v-col cols="12" ref="provider">
                    <p class="sub-title" v-if="selectPlanTitle.length > 0">Select a plan for {{selectPlanTitle}}</p>

                    <div class="d-flex" v-if="plansFlag && selectedPowerProvider === 'ea'">
                            <EnergyPlan
                                v-for="plan in plans"
                                :key="plan.key"
                                :plan="plan"
                                :selectedPlan="activeEaPlan"
                                @selectPlan="planSelect"
                                @view="view"
                                @click.native="planSelect(plan,true)"
                            ></EnergyPlan>
                    </div>

                    <div class="d-flex" v-if="plansFlag &&  selectedPowerProvider !== 'ea'">
                        <div class="d-flex" v-for="plan in origin2" :key="plan.name">
                            <SumoPlan
                                :sumoPlanDetails="sumoPlanDetails"
                                v-if="plan.name === 'sumo_saver'"
                                :plan="plan" @soleDialog="soleDialog"
                                @click.native="selectPlan({...plan, ...{name: sumoPlanDetails.plan_name}}, 'sumo')" :isActive="activeOriginPlan">
                            </SumoPlan>
                            <SolePlan v-else :plan="plan" @soleDialog="soleDialog" @click.native="selectPlan(plan)" :isActive="activeOriginPlan"></SolePlan>
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
                <InternetService :leadSummary="leadSummary" @updateStatus="updateStatus"></InternetService>
            </v-tab-item>
        </v-tabs>


        <v-dialog
            v-model="solePlanDialog"
            max-width="1200"
        >
            <v-card>
                <SoleDetails
                    @soleDialog="soleDialog"
                    :sumoPlanDetails="sumoPlanDetails"
                />
            </v-card>
        </v-dialog>

    </v-row>
</template>

<script>
import EnergyService from "@scripts/components/crm/leadmanagement/EnergyService";
import ServiceProvider from "@scripts/components/crm/leadmanagement/ServiceProvider";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import EnergyPlan from "@scripts/components/crm/leadmanagement/EnergyPlan";
import InternetPlan from "@scripts/components/crm/leadmanagement/InternetPlan";
import EAPlanService from "@scripts/services/ea/EAPlanService";
import {EA_PLAN_TYPES, PLAN_TYPE_TOTAL} from "@scripts/models/ea/EnergyPlan";
import EnergyPlanDetails from "@scripts/components/ea/EnergyPlanDetails";
import InternetPlanDetails from "@scripts/components/ea/InternetPlanDetails";
import WaterService from "@scripts/components/crm/leadmanagement/WaterService";
import InternetService from "@scripts/components/crm/leadmanagement/InternetService";
import { isNull } from "lodash-es";
import SolePlan from "@scripts/components/crm/leadmanagement/SolePlan";
import SumoPlan from "@scripts/components/crm/leadmanagement/SumoPlan";
import ServiceProvideres from "@scripts/data/ServiceProvideres";
import SumoService from '@scripts/services/crm/SumoService';
import SoleDetails from "@scripts/components/crm/leadmanagement/SoleDetails"
import SumoPlanDetails from "@scripts/modules/sumo/models/SumoPlanDetails";
import Spinner from "@scripts/plugins/Spinner";

export default {
    name: "ServiceApplications",
    components: { SumoPlan, SolePlan, InternetService, WaterService, EnergyPlan , InternetPlan , ServiceProvider, EnergyService, EnergyPlanDetails , InternetPlanDetails, SoleDetails},
    props: {
        leadSummary: {
            require: true
        }
    },

    data() {
        return {
            providerSpinner: null,
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
            origin: [ {text: 'Origin Go', bg: 'red', active: true, type: 'origin' },
                { text: 'Origin Go Variable', bg: 'blue', actSumoPlanDetailsive: false, type: 'origin' },
                {text: 'Origin Basic', bg: 'orange', active: false , type: 'origin'}],
            sumo: [ {text: 'Sumo Saver', bg: 'purple', active: true, type: 'sumo' },
                { text: 'Sumo ASSURE', bg: 'blue', active: false, type: 'sumo'},
                {text: 'Sumo SELECT', bg: 'green', active: false, type: 'sumo'}],
            servicesNew: ['Energy', 'Water', 'NVN'],
            selectedProviderId: 1,
            selectedPowerProvider: '',
            activeOriginPlan: '',
            activeEaPlan: '',
            waterStatus: null,
            origin2: null,
            isActivePlan: null,
            solePlanDialog: false,
            sumoPlanDetails: new SumoPlanDetails({})
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
        },
        providers() {
            return ServiceProvideres.filter((dt)=> {
               return dt.service_type === 'energy';
            });
        }
    },
    watch: {
        'leadSummary.service_interests'() {
            this.loadPlan();
        }
    },
    mounted() {
        this.providerSpinner = new Spinner(this.$refs.provider, {autoStart: true})
        this.loadServiceProvider();
        this.loadPlan();
        this.planSelect(EA_PLAN_TYPES.find(p => p.key === PLAN_TYPE_TOTAL))
        this.loadSelectedPowerProvider();


        this.$eventBus.$on("address_updated", address => {
            console.log("EventBus: ", address)
            this.onSelectProvider1('sumo');
        });

    },
    methods: {
        soleDialog(){
            this.solePlanDialog = !this.solePlanDialog;
        },
        reviewPlan() {
            //todo
        },

        planSelect(plan, isManual = false) {
            this.selectedPlanType = plan?.key;
            this.activeEaPlan = plan?.key;
            this.selectPlan({
                name: plan.key,
            });
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
            //todo add update sumo event emit
            console.log(service)


            if(service == 'Gas' || service == 'Power'){
                this.onSelectProvider1('sumo');
            }

            this.$emit('updateService', service);
            if (this.selectedPowerProvider === 'sumo') {
                this.$eventBus.$emit("validate", this.setSumoDetailsData)
            }
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
                return 'can not connect';

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
                    statustext = 'Can not Connect';
                    break;
                case  10:
                    statustext = 'Needs more info';
                    break;
                default:
                    break;
            }

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
            this.selectedPowerProvider = providerId;
        },
        async setSumoDetailsData(name) {
            this.sumoPlanDetails = new SumoPlanDetails({})
            this.providerSpinner.start()
            try {
                let address = this.leadSummary.street_address + ' ' + this.leadSummary.city + ' ' + this.leadSummary.state + ' ' + this.leadSummary.postcode;
                this.sumoPlanDetails =
                    await SumoService.getPlans(address, this.leadSummary.service_interests, this.leadSummary?.created_by_agent);
                this.actionOnSelectProvider(name)
                this.providerSpinner.stop()
                return 0;
            } catch (error) {
                this.sumoPlanDetails = new SumoPlanDetails();
            }

        },
        async onSelectProvider1(name) {
            this.selectedPowerProvider = name;
            // TODO need to decide if provider is
            if(name == 'sumo'){
                //listening on ApplicationDetailsPage component
                this.$eventBus.$emit("validate", this.setSumoDetailsData)
                // await this.setSumoDetailsData(name);
            }else{
                this.actionOnSelectProvider(name)
            }

        },
        actionOnSelectProvider(name){
            console.log('sumo' ,  name)
            const providerData  = this.providers.find((pl)=>{
                return pl.name === name;
            })
            this.origin2 = providerData.plans;
            this.isActivePlan = providerData.default_plan;
            this.selectedProviderId = name
        },
        updateStatus(text) {
            this.waterStatus = text;
        },

        selectPlan(plan, provider = null) {
            this.isActivePlan = plan.name;
            this.activeOriginPlan = plan.name;
                //todo update provider array for sumo plan
                console.log('plan provider click' , plan);
                let payload = {
                    service_type: this.leadSummary?.service_interests,
                    provider_name: this.selectedPowerProvider,
                    plan_type: plan.name
                }
                LeadApplicationService.updateApplicationProviders(payload , this.leadSummary.id);
        },

        loadSelectedPowerProvider() {
            const connectionService = this.leadSummary.connection_services.find(data => data.service_type === 'power' || data.service_type === 'gas');

            this.selectedPowerProvider = connectionService?.provider_name;
            if(!this.selectedPowerProvider) {
                // this.selectedPowerProvider = 'ea';
                // this.activeEaPlan = 'total_plan';
            }

            if(this.selectedPowerProvider === 'sumo') {
                setTimeout(() => this.$eventBus.$emit("validate", this.setSumoDetailsData), 600)
            }

            if(this.selectedPowerProvider === 'ea') {
                this.activeEaPlan = connectionService?.plan_type;
            }

            if(this.selectedPowerProvider === 'origin') {
                this.activeOriginPlan = connectionService?.plan_type;
                this.actionOnSelectProvider('origin');
            }

            // if(!this.activeEaPlan) {
            //     this.activeEaPlan = 'total_plan';
            // }
            //
            //
            // if( this.activeOriginPlan === 'total_plan') {
            //     this.activeOriginPlan = 'origin_go';
            // }



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
 font-size: 16px;
    font-weight: bold;
}

</style>
