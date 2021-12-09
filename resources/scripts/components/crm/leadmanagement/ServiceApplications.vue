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
                <v-col cols="12" v-if="leadSummary.is_temporary_connection">
                    <b>There is a request for temporary connection for this property.</b>
                </v-col>
                <v-col cols="12" v-if="leadSummary.is_temporary_connection">
                    <v-row>
                        <v-col cols="3" class="pt-5"> <b>Temp Connection</b> </v-col>
                        <v-col cols="4" >  
                            
            <v-menu
              v-model="connection_date_menu"
              :close-on-content-click="false"
              :nudge-right="40"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <ValidationProvider
                  name="Connection Date"
                  rules="required|valid-date|not-holiday:@h_state"
                  v-slot="{ errors }"
                >
                  <v-text-field
                    placeholder="DD/MM/YYYY"
                    outlined
                    dense
                    v-bind="attrs"
                    append-icon="mdi-calendar"
                    v-model="modified_moving_date"
                    :error-messages="errors[0]"
                    hide-details="auto"
                  >
                    <template slot="append">
                      <v-icon v-on="on">mdi-calendar</v-icon>
                    </template>
                  </v-text-field>
                </ValidationProvider>
              </template>
              <v-date-picker
                v-model="moving_date"
                @input="updateMovingDate"
              ></v-date-picker>
            </v-menu>    
                           
            </v-col>
            <v-col cols="1" class="pt-5">  To  </v-col>
            <v-col cols="4" > 

            <v-menu
              v-model="connection_end_date_menu"
              :close-on-content-click="false"
              :nudge-right="40"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <ValidationProvider
                  name="Connection Date"
                  rules="required|valid-date"
                  v-slot="{ errors }"
                >
                  <v-text-field
                    placeholder="DD/MM/YYYY"
                    outlined
                    dense
                    append-icon="mdi-calendar"
                    :value="modified_connection_end_date"
                    v-bind="attrs"
                    :error-messages="errors[0]"
                    hide-details="auto"
                  >
                    <template slot="append">
                      <v-icon v-on="on">mdi-calendar</v-icon>
                    </template>
                  </v-text-field>
                </ValidationProvider>
              </template>
              <v-date-picker
                v-model="connection_end_date"
                :min="moving_date"
                @input="updateConnectionEndDate"
              ></v-date-picker>
            </v-menu>  
            <ValidationProvider name="h_state">
                <v-text-field v-model="leadSummary.state" v-show="false" />
            </ValidationProvider>
                        </v-col>
                    </v-row>
                </v-col>

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
                        <div v-if='isSumoLoading' class="sumo-loading-container">
                            <v-progress-circular
                                indeterminate
                                color="primary"
                            ></v-progress-circular>
                        </div>
                        <div v-else-if="sumoOptions.isError" class="d-flex justify-center" style="width: 100%">
                            <div class="text-center font-weight-bold red--text"> Something went wrong, Please retry. </div>
                            <!-- <v-btn color="error">Retry</v-btn> -->
                        </div>
                        <div v-else class="d-flex" v-for="plan in origin2" :key="plan.name">
                            <SumoPlan
                                :sumoPlanDetails="sumoPlanDetails"
                                v-if="plan.name === 'sumo_saver'"
                                :plan="plan" @soleDialog="soleDialog"
                                @click.native="selectPlan({...plan, ...{name: sumoPlanName}}, 'sumo')" :isActive="activeOriginPlan">
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
        <div class="d-flex justify-end py-4 px-4" style="width: 100%; background-color: white;">

            <v-btn :disabled="is_submit_disabled" color="#542E89" @click="submit" class="white--text">
                    Submit for connection
            </v-btn>

        </div>

        <v-dialog
            v-model="solePlanDialog"
            max-width="1200"
        >
            <v-card>
                <SoleDetails
                    @soleDialog="soleDialog"
                    :sumoPlanDetails="sumoPlanDetails"
                    :selectedPowerProvider="selectedPowerProvider"
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
import dayJs from "dayjs";
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
            sumoPlanDetails: new SumoPlanDetails({}),
            isSumoLoading: false,
            sumoOptions: {
                isError: false,
                errorMsg: "",
            },


            connection_date_menu: false,
            connection_end_date_menu: false,
            connection_end_date: null,
            moving_date: null,
            minConnectionDate:  null,
            modified_moving_date: null,
        }
    },
    computed: {
        sumoPlanName(){
            if(this.sumoPlanDetails){
                return this.sumoPlanDetails?.plan_name ?? "";
            } else{
                return '';
            }
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
        },
        is_submit_disabled(){
            if( this.tab === this.tabMapper.Water && !['In Progress', 'Not Selected'].includes(this.getwaterServiceStatus)){
                return true;
            }else{
                return false;
            }
        },
        tabMapper(){
            return {
                'Energy'   : 0,
                'Water'    : 1,
                'Internet' : 2,
            }
        },
        // modified_moving_date(){
        //     const date         =  dayJs(this.moving_date, 'YYYY-MM-DD');
        //     return date.isValid() ? date.format('DD/MM/YYYY'): null;
            
        // },
        modified_connection_end_date(){
             const date         =  dayJs(this.connection_end_date, 'YYYY-MM-DD');
             return date.isValid() ? date.format('DD/MM/YYYY'): null;
            // return dayJs(this.connection_end_date).format("DD/MM/YYYY");
        }
    },
    watch: {
        'leadSummary.service_interests'() {
            this.loadPlan();
        }
    },
    mounted() {
        console.log('root' , this.$root);
        console.log('parent' , this.$parent);

        // const birthdate         =  dayjs(this.dob, 'YYYY-MM-DD');
        // this.person_details.dob =  birthdate.isValid() ? birthdate.format('DD/MM/YYYY'): null;

        console.log("conn end date" , this.leadSummary.connection_end_date);
        this.modified_moving_date = this.leadSummary.moving_date;
        this.moving_date = this.leadSummary.moving_date;
        this.connection_end_date = this.leadSummary.connection_end_date;

        this.providerSpinner = new Spinner(this.$refs.provider, {autoStart: true})
        this.loadServiceProvider();
        this.loadPlan();
         // this.planSelect(EA_PLAN_TYPES.find(p => p.key === PLAN_TYPE_TOTAL))
        this.loadSelectedPowerProvider();

        const updateAddress = address => {
            if (this.selectedPowerProvider === 'sumo') {
                this.$eventBus.$emit("validate", this.setSumoDetailsData)
            }
        }

        this.$eventBus.$on("address_updated", updateAddress );

        this.$once("hook:beforeDestroy", () => {
            this.$eventBus.$off("address_updated", updateAddress );
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
            let newPlan = {
                name: plan.key,
                service_area: 'energy'
            }
            this.selectedProviderId = 'ea';
            this.selectPlan(newPlan);
            this.selectedPlanType = plan?.key
            this.$emit('updatePlan', {...plan,  provider: this.selectedProviderId, service_area:'energy' }, isManual);
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
                return 'Not Selected';

            }

            return 'Connected';
        },

        mapStatus(statusCode) {

            let statustext = '';
            switch (statusCode){
                case  4:
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
            this.isSumoLoading = true;
            this.sumoPlanDetails = new SumoPlanDetails({})
            this.sumoOptions.isError = false;
            // this.sumoOptions.isError = true;
            this.sumoOptions.errorMsg = "";
            this.providerSpinner.start()
            try {
                // this.isSumoLoading = true;
                let address = this.leadSummary.street_address + ' ' + this.leadSummary.city + ' ' + this.leadSummary.state + ' ' + this.leadSummary.postcode;
                this.sumoPlanDetails =
                    await SumoService.getPlans(address, this.leadSummary.service_interests, this.leadSummary?.created_by_agent, this.leadSummary);
                this.actionOnSelectProvider(name)
                this.isSumoLoading = false;
                // this.isSumoLoading = false;
                this.providerSpinner.stop()
                return 0;
            } catch (error) {
                this.sumoOptions.isError = true;
                console.log("sumo sth went wrong")
                this.sumoOptions.errorMsg = "Something weng wrong, retry";
                this.sumoPlanDetails = new SumoPlanDetails();
            } finally {
                this.isSumoLoading = false;
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
                this.isSumoLoading = false;
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

            console.log('this.selectedProviderId', this.selectedProviderId);

            if(this.selectedProviderId === 'sumo') {
                if( !(this.isActivePlan === 'sumo_saver' || this.isActivePlan === 'sumo_assure'|| this.isActivePlan === 'sumo_select')) {
                    // this.isActivePlan = "sumo_saver";

                }
            }

            if(this.selectedProviderId === 'origin') {
                if( !(this.isActivePlan === 'origin_go' || this.isActivePlan === 'origin_go_variable'|| this.isActivePlan === 'origin_basic')) {
                    // this.isActivePlan = "origin_go";

                }
            }

        },
        updateStatus(text) {
            this.waterStatus = text;
        },

        selectPlan(plan, provider = null) {

          console.log(this.selectedPowerProvider)
          if( this.selectedPowerProvider === 'ea') {
            this.activeEaPlan =  plan.name;
            this.activeOriginPlan = '';
          }

          if( this.selectedPowerProvider === 'origin') {
            this.activeOriginPlan = plan.name;
            this.activeEaPlan = '';
          }


            this.isActivePlan = plan.name;
            this.activeOriginPlan = plan.name;
                //todo update provider array for sumo plan
                console.log('plan provider click' , plan);
                let payload = {
                    service_type: this.leadSummary?.service_interests,
                    provider_name: this.selectedPowerProvider,
                    plan_type: plan.name,
                    service_area: 'energy'
                }

                if(this.selectedPowerProvider !== ''){
                  this.isActivePlan = plan.name;
                  this.activeOriginPlan = plan.name;
                    LeadApplicationService.updateApplicationProviders(payload , this.leadSummary.id);
                }
            this.$emit('updatePlan', {
                active: false,
                key: plan.name,
                title: plan.title,
                provider: this.selectedProviderId
            }, true);

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

        },
        submit(){
            let subType = 'energy';
            if(this.tabMapper.Energy == this.tab){
                subType = 'energy';
            } else if(this.tabMapper.Water == this.tab){
                subType = 'water';
            } else {
                subType = 'internet';
            }
            this.$eventBus.$emit("busWaterSubmit", subType)
        },
        updateMovingDate(){
            console.log("clicked")
            // const date         =  dayJs(this.moving_date, 'YYYY-MM-DD');
            // return date.isValid() ? date.format('DD/MM/YYYY'): null
            this.connection_date_menu = false;
            this.modified_moving_date = date.isValid() ? date.format('DD/MM/YYYY'): null
            this.$eventBus.$emit("update_moving_date", this.modified_moving_date)
        },
        updateConnectionEndDate(){
            console.log("clicked")
            this.connection_end_date_menu = false;
            this.$eventBus.$emit("update_connection_end_date", this.modified_connection_end_date)
        }
    }
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
.sumo-loading-container {
    flex: 1;
    text-align: center;
}

</style>
