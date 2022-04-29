<template>
    <div>
        <v-col cols="12" class="d-flex pb-0">
            <v-checkbox
                v-model="isBothEnergySubmit"
            ></v-checkbox>
            <p class="checkbox-text">Submit both Power and Gas</p>
        </v-col>
        <TemporaryConnection :leadSummary="leadSummary" />
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
        <v-col cols="12" v-if="selectedProvider === 'ea' && afterHourFlag && selectedPlan">
            <SameDayConnection :leadSummary="leadSummary" @changeAfterHourPayee="changeAfterHourPayee" />
        </v-col>

        <v-divider></v-divider>

        <v-col cols="12" ref="provider">
            <p class="sub-title" v-if="selectedServiceTitle.length > 0">
                Select a plan for {{ selectedServiceTitle }}
            </p>

            <div class="d-flex" v-if="isEaPlansLoaded && selectedProvider === 'ea'">
                <EnergyPlan
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
                <SolePlan
                    v-for="plan in originPlans"
                    :key="plan.name"
                    :plan="plan"
                    @click.native="selectPlan(plan)"
                    :isActive="selectedPlan"
                    @soleDialog="toggleViewPlanDetails"
                ></SolePlan>
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
                    v-else
                    class="d-flex"
                    v-for="plan in sumoTemporaryPlans"
                    :key="plan.name"
                >
                    <SumoPlan
                        :sumoPlanDetails="sumoPlans"
                        :plan="plan"
                        :isActive="selectedPlan"
                        @soleDialog="toggleViewPlanDetails"
                        @click.native="selectPlan({...plan, ...{ name: sumoPlanName }})"
                    >
                    </SumoPlan>
                </div>
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

        <v-dialog v-model="viewPlanDetails" max-width="1200">
            <v-card>
                <SoleDetails
                    @soleDialog="toggleViewPlanDetails"
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
import SameDayConnection from "@scripts/components/crm/leadmanagement/SameDayConnection";
import EAPlanService from "@scripts/services/ea/EAPlanService";
import SumoService from "@scripts/services/crm/SumoService";
import SumoPlanDetails from "@scripts/modules/sumo/models/SumoPlanDetails";
import EnergyPlan from "@scripts/components/crm/leadmanagement/EnergyPlan";
import SumoPlan from "@scripts/components/crm/leadmanagement/SumoPlan";
import SolePlan from "@scripts/components/crm/leadmanagement/SolePlan";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import EnergyPlanDetails from "@scripts/components/ea/EnergyPlanDetails";
import SoleDetails from "@scripts/components/crm/leadmanagement/SoleDetails";

export default {
    name: "PowerService",
    props: {
        leadSummary: {
            require: true
        },
        afterHourFlag: {
            require: false
        }
    },
    components: {
        ServiceProvider,
        TemporaryConnection,
        SameDayConnection,
        EnergyPlan,
        SumoPlan,
        SolePlan,
        EnergyPlanDetails,
        SoleDetails
    },
    data() {
        return {
            isBothEnergySubmit: false,
            selectedProvider: null,
            selectedPlan: null,
            eaPlans: [],
            isEaPlansLoaded: false,
            viewEaPlanDetails: false,
            eaPlanForDetails: null,
            sumoTemporaryPlans: [],
            sumoPlans: new SumoPlanDetails({}),
            isSumoPlansLoading: true,
            isSumoPlansLoadError: false,
            viewPlanDetails: false,
            originPlans: [],
        };
    },
    computed: {
        providers() {
            return ServiceProvideres.filter(dt => {
                return dt.service_type === "energy";
            });
        },
        selectedServiceTitle() {
            const services = this.leadSummary.service_interests;
            if ( services && services.includes("gas") && services.includes("power")) {
                return "Power & Gas";
            }
            return services && services.includes("gas") ? "Gas" : "Power";
        },
        sumoPlanName() {
            if (this.sumoPlans) {
                return this.sumoPlans?.plan_name ?? "";
            } else {
                return "";
            }
        },
    },
    mounted() {
        this.fetchEaPlans();
        this.fetchOriginPlans();
        this.fetchTemporarySumoPlans();
        this.loadSelectedProvider();

        // On address change refetch Sumo Plan Details
        const updateAddress = address => {
            this.selectedProvider === "sumo" ? this.$eventBus.$emit("validate", this.fetchSumoPlans) : null;
        };
        this.$eventBus.$on("address_updated", updateAddress);
        this.$once("hook:beforeDestroy", () => {
            this.$eventBus.$off("address_updated", updateAddress);
        });
    },
    methods: {
        loadSelectedProvider() {
            const connectionService = this.leadSummary.connection_services.find(
                data => data.service_type === "power" || data.service_type === "gas"
            );

            this.selectedProvider = connectionService?.provider_name;
            this.selectedPlan = connectionService?.plan_type;

            if (this.selectedProvider === "sumo") {
                setTimeout(() => this.$eventBus.$emit("validate", this.fetchSumoPlans), 600);
            }
        },
        async fetchEaPlans() {
            const services = this.leadSummary.service_interests;
            if (services.includes("gas") || services.includes("power")) { //todo keep any one
                this.eaPlans = await EAPlanService.getAllPlans({
                    service_type: this.leadSummary.service_interests,
                    postcode: this.leadSummary.postcode,
                    state: this.leadSummary.state
                });
                this.isEaPlansLoaded = true;
                console.log("eaPlans", this.eaPlans);
                // this.getPlanType(); //todo check if needed
            } else {
                this.isEaPlansLoaded = false;
            }
        },
        async fetchOriginPlans() {
            const originProvider = this.providers.find(pl => {
                return pl.name === 'origin';
            });
            this.originPlans = originProvider.plans;
            console.log("originPlans", this.originPlans);
        },
        async fetchTemporarySumoPlans() {
            const sumoProvider = this.providers.find(pl => {
                return pl.name === 'sumo';
            });
            this.sumoTemporaryPlans = sumoProvider.plans;
            console.log("sumoTemporaryPlans", this.sumoTemporaryPlans);
        },
        async fetchSumoPlans(name) {
            this.isSumoPlansLoading = true;
            this.isSumoPlansLoadError = false;
            this.sumoPlans = new SumoPlanDetails({});
            try {
                let address = `${this.leadSummary.street_number} ${this.leadSummary.street_name_only} ${this.leadSummary.street_type} ${this.leadSummary.city} ${this.leadSummary.state} ${this.leadSummary.postcode}`;
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
                this.isSumoPlansLoadError = true;
                this.sumoPlans = new SumoPlanDetails();
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
            this.selectedPlan = plan?.key;
            let planObj = {
                name: plan.key,
                service_area: "energy",
                title: plan.title,
            };
            this.selectPlan(planObj, isManual);
        },
        selectPlan(plan, isManual = true) {
            this.selectedPlan = plan.name;
            let payload = {
                service_type: this.leadSummary?.service_interests,
                provider_name: this.selectedProvider,
                plan_type: plan.name,
                service_area: "energy"
            };

            if (this.selectedProvider !== "" && this.selectedProvider !== null) {
                LeadApplicationService.updateApplicationProviders(payload, this.leadSummary.id);
            }
            this.$emit(
                "updatePlan",
                {
                    active: false,
                    key: plan.name,
                    title: plan.title,
                    provider: this.selectedProvider,
                    service_area: "energy"
                },
                isManual
            );
        },
        openEaPlanDetails(plan) {
            this.eaPlanForDetails = plan.key;
            this.viewEaPlanDetails = true;
        },
        closeEaPlanDetails() {
            this.viewEaPlanDetails = false;
        },
        toggleViewPlanDetails() {
            this.viewPlanDetails = !this.viewPlanDetails;
        },
        changeAfterHourPayee() {
            this.$emit("changeAfterHourPayee");
        }
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
</style>
