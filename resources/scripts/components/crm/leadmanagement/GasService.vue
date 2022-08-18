<template>
    <div>
        <v-col cols="12" class="pb-0">
            <p class="py-0 mb-1 title-text">
                Status: <span v-if="this.status" class="value-text ml-1" :style="{color: this.status.color}">{{ this.status.text }}</span>
            </p>
            <p class="py-0 mb-1 title-text" v-if="reason && rejectedStatus">
                Rejection reason: <span class="value-text ml-1">{{ reason }}</span>
            </p>
            <p class="py-0 mb-1 title-text">
                Quote ID: <span class="value-text ml-1">{{ this.quoteReference }}</span>
            </p>
        </v-col>
        <v-col v-if="canShowBothEnergySubmitCheckbox" cols="12" class="d-flex pb-0">
            <v-checkbox
                v-model="isBothEnergySubmit"
                @change="changeIsBothEnergySubmit"
            ></v-checkbox>
            <p class="checkbox-text">Submit elec and gas to same retailer for same plan.</p>
        </v-col>
        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>
        <v-col cols="12">
            <p class="mb-0 sub-title">
                Which supplier would you like to connect with?
            </p>
            <div class="d-flex align-content-lg-space-around mt-2">
                <ServiceProvider
                    @onSelectProvider="onSelectProvider(provider.name)"
                    v-for="provider in providers"
                    :key="provider.name"
                    :selectedProvider="selectedProvider"
                    :provider="provider"
                ></ServiceProvider>
            </div>
        </v-col>
        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>

        <v-divider></v-divider>

        <v-col cols="12" ref="provider">
            <p class="sub-title" v-if="selectedServiceTitle.length > 0">
                Select a plan for {{ selectedServiceTitle }}
            </p>

            <div class="d-flex" v-if="isEaPlansLoaded && selectedProvider === 'ea'">
                <EnergyPlan
                    :class="{'not-editable': !isServiceEditable }"
                    v-for="plan in eaPlans"
                    :key="plan.key"
                    :plan="plan"
                    :selectedPlan="selectedPlan"
                    @selectPlan="selectEAPlan"
                    @view="openEaPlanDetails"
                    @click.native="selectEAPlan(plan, true)"
                ></EnergyPlan>
            </div>

            <div class="d-flex" v-if="selectedProvider === 'origin'">
                <OriginPlan
                    :class="{'not-editable': !isServiceEditable }"
                    v-for="plan in originPlans"
                    :key="plan.name"
                    :plan="plan"
                    @click.native="selectPlan(plan)"
                    :isActive="selectedPlan"
                    @toggleDialog="toggleOriginPlanDetails"
                ></OriginPlan>
            </div>

            <div class="d-flex" v-if="selectedProvider === 'sumo'">
                <div v-if="isSumoPlansLoading" class="sumo-loading-container">
                    <v-progress-circular
                        indeterminate
                        color="primary"
                    ></v-progress-circular>
                </div>
                <div v-else-if="isSumoPlansLoadError" class="d-flex justify-center" style="width: 100%">
                    <div class="text-center font-weight-bold red--text">
                        Something went wrong, Please retry.
                    </div>
                </div>
                <div
                    v-else-if="sumoPlans"
                    class="d-flex"
                >
                    <SumoPlan
                        :class="{'not-editable': !isServiceEditable }"
                        :sumoPlanDetails="sumoPlans"
                        :isActive="selectedPlan"
                        @toggleDialog="toggleSumoPlanDetails"
                        @click.native="selectPlan({...sumoPlans, ...{ name: sumoPlans.plan_name }})"
                    >
                    </SumoPlan>
                </div>
            </div>

            <div v-if="selectedProvider === 'ea'" class="d-flex mt-1">
                <v-checkbox
                    v-model="leadSummary.ea_go_neutral"
                    @change="changeGoNeutral">
                </v-checkbox>
                <p class="neutral-checkbox-text">Customer opts in for <span class="font-weight-bold">Go Neutral</span>.</p>
            </div>

        </v-col>
        <v-col cols="12">
            <div class="d-flex justify-end py-4 px-4" style="width: 100%; background-color: white;">
                <v-btn
                    :disabled="isDisable()"
                    color="#542E89"
                    @click="submit"
                    class="white--text"
                >
                    Submit {{ selectedServiceTitle }}
                </v-btn>
            </div>
        </v-col>

        <v-dialog v-model="viewEaPlanDetails" max-width="500"
            v-if="viewEaPlanDetails && eaPlanForDetails"
        >
            <v-card>
                <EnergyPlanDetails
                    :plan="eaPlanForDetails"
                    :postcode="leadSummary.postcode"
                    :services="leadSummary.service_interests"
                    :state="leadSummary.state"
                />
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="green darken-1" text @click="closeEaPlanDetails">
                        Close
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="originPlanDetails" max-width="450">
            <v-card>
                <OriginPlanDetails
                    @toggleDialog="toggleOriginPlanDetails"
                    :serviceType="isBothEnergySubmit ? 'energy' : 'gas'"
                    :selectedPlan="selectedPlan"
                    :leadSummary="leadSummary"
                    :planDetails="planDetails"
                />
            </v-card>
        </v-dialog>

        <v-dialog v-model="sumoPlanDetails" max-width="1200">
            <v-card>
                <SumoPlanDetails
                    @toggleDialog="toggleSumoPlanDetails"
                    :sumoPlanDetails="sumoPlans"
                    :selectedPowerProvider="selectedProvider"
                />
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import ServiceProvideres from "@scripts/data/ServiceProvideres";
import ServiceProvider from "@scripts/components/crm/leadmanagement/ServiceProvider";
import TemporaryConnection from "@scripts/components/crm/leadmanagement/TemporaryConnection";
import SameDayConnection from "@scripts/components/crm/leadmanagement/SamedayConnection";
import EAPlanService from "@scripts/services/ea/EAPlanService";
import SumoService from "@scripts/services/crm/SumoService";
import EnergyPlan from "@scripts/components/crm/leadmanagement/EnergyPlan";
import SumoPlan from "@scripts/components/crm/leadmanagement/SumoPlan";
import OriginPlan from "@scripts/components/crm/leadmanagement/OriginPlan";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import EnergyPlanDetails from "@scripts/components/ea/EnergyPlanDetails";
import OriginPlanDetails from "@scripts/components/crm/leadmanagement/OriginPlanDetails";
import SumoPlanDetails from "@scripts/components/crm/leadmanagement/SumoPlanDetails";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";
import {isNull} from "lodash-es";
import {connectionServicesMapper} from "@scripts/data/ConnectionApplicationMapper";
import OriginService from "@scripts/modules/origin/services/OriginService";
import OriginMapper from "@scripts/modules/origin/api/mappers/OriginMapper";
import ProviderPlan from "@scripts/models/crm/ProviderPlan";

export default {
    //todo reduce emit functions
    //todo shift all lead variables to vuex store
    name: "GasService",
    components: {
        ServiceProvider,
        TemporaryConnection,
        SameDayConnection,
        EnergyPlan,
        SumoPlan,
        OriginPlan,
        EnergyPlanDetails,
        OriginPlanDetails,
        SumoPlanDetails
    },
    props: {
        leadSummary: {
            require: true
        },
        afterHourFlag: {
            require: false
        }
    },
    data() {
        return {
            eaPlans: [],
            isEaPlansLoaded: false,
            viewEaPlanDetails: false,
            eaPlanForDetails: null,
            sumoPlans: null,
            isSumoPlansLoading: false,
            isSumoPlansLoadError: false,
            sumoPlanDetails: false,
            originPlans: [],
            originPlanDetails: false,
            planDetails: null
        };
    },
    computed: {
        providers() {
            return ServiceProvideres.filter(dt => {
                return dt.service_type === "energy";
            });
        },
        selectedServiceTitle() {
            return this.isBothEnergySubmit ? "Power & Gas" : "Gas";
        },
        selectedProvider: {
            get() {
                return UtilityStoreService.getGasProvider();
            },
            set(value) {
                this.isBothEnergySubmit === true ?
                    UtilityStoreService.setBothProvider(value)
                    : UtilityStoreService.setGasProvider(value);
            }
        },
        selectedPlan: {
            get() {
                return UtilityStoreService.getGasPlan();
            },
            set(value) {
                this.isBothEnergySubmit === true ?
                    UtilityStoreService.setBothPlan(value, this.selectedProvider)
                    : UtilityStoreService.setGasPlan(value);
            }
        },
        isBothEnergySubmit: {
            get() {
                return UtilityStoreService.getIsBothEnergySelected();
            },
            set(value) {
                UtilityStoreService.setIsBothEnergySelected(value);
            }
        },
        canShowBothEnergySubmitCheckbox: {
            get() {
                return LeadApplicationService.canShowBothEnergySubmitCheckbox(this.leadSummary.connection_services);
            }
        },
        isServiceEditable() {
            return LeadApplicationService.canEditService(
                UtilityStoreService.getGasStatus()
            );
        },
        isPayeeSelectedForAfterHourSubmission() {

            return this.afterHourFlag && isNull(this.leadSummary.after_hour_payee);
        },
        status() {
            let utilityStatus = UtilityStoreService.getGasStatus();
            return utilityStatus ? LeadApplicationService.mapStatus(utilityStatus) : null;
        },
        service() {
            return this.leadSummary.connection_services?.find(service => service.service_type === 'gas');
        },
        quoteReference() {
            return this.service?.quote_reference ? this.service.quote_reference : (this.service?.lead_reference ? this.service.lead_reference : '-');
        },
        reason() {
            const reasons = this.service?.reasons;
            if (Array.isArray(reasons) && reasons.length > 0) {
                return reasons.sort((a, b) => {
                    return new Date(b.created_at) - new Date(a.created_at);
                })[0].reason_text;
                // return reasons.map(reason => reason.reason_text).join(', ');
            }
            return null;
        },
        rejectedStatus() {
            return UtilityStoreService.getGasStatus() === connectionServicesMapper.STATUS_REJECTED
            || UtilityStoreService.getGasStatus() === connectionServicesMapper.STATUS_CANT_CONNECT;
        },
        getNMIPrefix() {
            return this.leadSummary.nmi?.substr(0, 2) ?? '';
        },
        state() {
            switch(this.leadSummary.state) {
                case "New South Wales":
                    return 'nsw'
                case "Victoria":
                    return 'vic'
                case "Queensland":
                    return 'qld'
                case "South Australia":
                    return 'sa'
                case "Northern Territory":
                    return 'nt'
                case "Tasmania":
                    return 'tas'
                case "Australian Capital Territory":
                    return 'act'
                case 'Western Australia':
                    return 'wa'
            }
        },
    },
    mounted() {
        this.fetchEaPlans();
        this.getOriginData();
        // this.fetchOriginPlans();
        this.loadSelectedProviderAndPlan();

        // On address change refetch Sumo Plan Details
        const updateAddress = address => {
            this.selectedProvider === "sumo" ? this.$eventBus.$emit("validate", this.fetchSumoPlans) : null;
        };
        this.$eventBus.$on("address_updated", updateAddress);
        this.$once("hook:beforeDestroy", () => {
            this.$eventBus.$off("address_updated", updateAddress);
        });
    },
    watch: {
        isBothEnergySubmit() {
            this.getOriginData()
        },
        getNMIPrefix() {
            this.getOriginData()
        },
    },
    methods: {
        loadSelectedProviderAndPlan() {
            const connectionService = this.leadSummary.connection_services.find(
                data => data.service_type === "gas"
            );

            this.selectedProvider = connectionService?.provider_name;
            this.selectedPlan = connectionService?.plan_type;

            if (this.selectedProvider === "sumo") {
                setTimeout(() => this.$eventBus.$emit("validate", this.fetchSumoPlans), 600);
            }
        },
        async fetchEaPlans() {
            this.eaPlans = await EAPlanService.getAllPlans({
                service_type: this.isBothEnergySubmit ? ['power', 'gas'] : ['gas'],
                postcode: this.leadSummary.postcode,
                state: this.leadSummary.state
            });
            this.isEaPlansLoaded = true;
            console.log("eaPlans", this.eaPlans);
        },
        async fetchOriginPlans() {
            const originProvider = this.providers.find(pl => {
                return pl.name === 'origin';
            });
            this.originPlans = originProvider.plans.filter(plan => {
                return plan.type === 'gas';
            });
            console.log("originPlans", this.originPlans);
        },
        async fetchSumoPlans(name) {
            this.isSumoPlansLoading = true;
            this.isSumoPlansLoadError = false;
            this.sumoPlans = null;
            try {
                let address = this.leadSummary.unit_number ?
                    `${this.leadSummary.unit_number}  ${this.leadSummary.street_number} ${this.leadSummary.street_name_only} ${this.leadSummary.street_type} ${this.leadSummary.city} ${this.leadSummary.state} ${this.leadSummary.postcode}`
                    : `${this.leadSummary.street_number} ${this.leadSummary.street_name_only} ${this.leadSummary.street_type} ${this.leadSummary.city} ${this.leadSummary.state} ${this.leadSummary.postcode}`;

                this.sumoPlans = await SumoService.getPlans(
                    address,
                    this.leadSummary.service_interests,
                    this.leadSummary?.created_by_agent,
                    this.leadSummary
                );
                this.isSumoPlansLoading = false;
                console.log("sumoPlans", this.sumoPlans);
                return 0;
            } catch (error) {
                console.log('Sumo Error', error);
                this.isSumoPlansLoadError = true;
                this.sumoPlans = null;
            } finally {
                this.isSumoPlansLoading = false;
            }
        },
        resetSelectedPlan() {
            this.selectedPlan = null;
        },
        onSelectProvider(provider) {
            this.resetSelectedPlan();
            this.selectedProvider = provider;

            if (provider === "sumo") {
                this.$eventBus.$emit("validate", this.fetchSumoPlans);
            }
        },
        selectEAPlan(plan, isManual = false) {
            let planObj = {
                name: plan.key,
                service_area: "energy",
                title: plan.title,
            };
            this.selectPlan(planObj, isManual);
        },
        async selectPlan(plan, isManual = true) {
            if(!this.isServiceEditable) return;
            this.selectedPlan = plan.name;
            let payload = {
                service_type: this.leadSummary?.service_interests,
                provider_name: this.selectedProvider,
                plan_type: plan.name,
                service_area: this.isBothEnergySubmit ? "energy" : "gas"
            };

            if (this.selectedProvider !== null) {
                await LeadApplicationService.updateApplicationProviders(payload, this.leadSummary.id);
                this.reloadUtilityStore();
            }
        },
        async reloadUtilityStore() {
            let leadSummary = await LeadApplicationService.loadUserLead(this.leadSummary.id);
            UtilityStoreService.setUtilityDetails(leadSummary.connection_services);
        },
        openEaPlanDetails(plan) {
            this.eaPlanForDetails = plan.key;
            this.viewEaPlanDetails = true;
        },
        closeEaPlanDetails() {
            this.viewEaPlanDetails = false;
        },
        toggleOriginPlanDetails() {
            this.originPlanDetails = !this.originPlanDetails;
        },
        toggleSumoPlanDetails() {
            this.sumoPlanDetails = !this.sumoPlanDetails;
        },
        changeIsBothEnergySubmit(value) {
            if(value) {
                UtilityStoreService.setBothProvider(this.selectedProvider);
                UtilityStoreService.setBothPlan(this.selectedPlan, this.selectedProvider);

                if(this.selectedProvider && this.selectedPlan) {
                    let payload = {
                        service_type: this.leadSummary?.service_interests,
                        provider_name: this.selectedProvider,
                        plan_type: this.selectedPlan,
                        service_area: this.isBothEnergySubmit ? "energy" : "gas"
                    };
                    LeadApplicationService.updateApplicationProviders(payload, this.leadSummary.id);
                }
            }
        },
        isDisable() {
            return (
                !LeadApplicationService.canSubmitEnergy('gas') ||
                !this.selectedProvider ||
                !this.selectedPlan ||
                (this.isBothEnergySubmit && this.isPayeeSelectedForAfterHourSubmission)
            );
        },
        submit() {
            let subType = this.isBothEnergySubmit ? "energy" : "gas";
            this.$eventBus.$emit("busUtilitySubmit", subType);
        },
        async changeGoNeutral() {
            await LeadApplicationService.saveSoleField('ea_go_neutral', this.leadSummary.ea_go_neutral, this.leadSummary.id);
        },
        async getOriginData() {
            let query = null;
            if(this.isBothEnergySubmit) {
                query = {
                    state: this.state,
                    postcode: this.leadSummary.postcode,
                    nmi_prefix: this.getNMIPrefix,
                }
            } else {
                query = {
                    service_type: 'gas',
                    state: this.state,
                    postcode: this.leadSummary.postcode,
                    nmi_prefix: this.getNMIPrefix,
                }
            }

            this.planDetails = await OriginService.getOriginData(query);

            this.originPlans = [new ProviderPlan({
                title: this.planDetails.plans.gas.plan_name_text,
                name: this.planDetails.plans.gas.plan_name_code,
                bgColor: 'red',
                type: 'gas',
            })]

        },
    },
};
</script>

<style lang="scss" scoped>
.checkbox-text {
    font-size: 18px;
    margin-top: 18px;
}
.sumo-loading-container {
    flex: 1;
    text-align: center;
}
.not-editable {
    cursor: not-allowed;
}
.title-text {
    font-size: 16px;
    font-weight: 700;
}
.value-text {
    font-size: 16px;
    font-weight: 400;
}
.neutral-checkbox-text {
    margin-top: 20px;
}
</style>
