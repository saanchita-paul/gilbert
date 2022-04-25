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
                </v-card>
            </v-tab>
            <v-tab class="px-0 py-3 tab-capital-case">
                <v-card class="hood-card" width="100%">
                    <p class="pt-2 pb-1 mb-0 services service-title">
                        <span class="ml-1">
                            <v-icon color="blue">mdi-water</v-icon>Water
                        </span>
                    </p>
                    <p class="py-0 my-0 pl-4 service-status active-power-subtitle"
                        :class="{ dangerText: isWaterFailed }"
                    >
                        {{ getWaterStatus }}
                    </p>
                </v-card>
            </v-tab>
            <v-tab class="py-3 px-0 tab-capital-case">
                <v-card class="hood-card" width="100%">
                    <div>
                        <p class="pt-2 pb-1 mb-0 services service-title">
                            <span class="ml-1">
                                <v-icon color="red">mdi-wifi</v-icon>Internet
                            </span>
                        </p>
                        <p class="py-0 my-0 pl-4 service-status active-power-subtitle"
                        >
                            {{ getInternetStatus }}
                        </p>
                    </div>
                </v-card>
            </v-tab>

            <v-tab-item>
                <TemporaryConnection :leadSummary="leadSummary" />
                <v-card>
                    <v-col cols="12" class="service-box-area">
                        <div v-for="service in services" :key="service">
                            <EnergyService
                                @click.native="updateService(service)"
                                :title="service"
                                :lead-summary="leadSummary"
                            >
                            </EnergyService>
                        </div>
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
                                :selectedProvider="selectedPowerProvider"
                                :provider="provider"
                            ></ServiceProvider>
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <v-divider></v-divider>
                    </v-col>

                    <v-col cols="12" v-if="selectedPowerProvider === 'ea' && afterHourFlag && selected_plan">
                       <SameDayConnection :leadSummary="leadSummary" @changeAfterHourPayee="changeAfterHourPayee" />
                    </v-col>

                    <v-divider></v-divider>

                    <v-col cols="12" ref="provider">
                        <p class="sub-title" v-if="selectPlanTitle.length > 0">
                            Select a plan for {{ selectPlanTitle }}
                        </p>

                        <div class="d-flex" v-if="plansFlag && selectedPowerProvider === 'ea'">
                            <EnergyPlan
                                v-for="plan in plans"
                                :key="plan.key"
                                :plan="plan"
                                :selectedPlan="selected_plan"
                                @selectPlan="planSelect"
                                @view="view"
                                @click.native="planSelect(plan, true)"
                            ></EnergyPlan>
                        </div>

                        <div class="d-flex" v-if="plansFlag && selectedPowerProvider !== 'ea'">
                            <div
                                v-if="isSumoLoading"
                                class="sumo-loading-container"
                            >
                                <v-progress-circular
                                    indeterminate
                                    color="primary"
                                ></v-progress-circular>
                            </div>
                            <div
                                v-else-if="sumoOptions.isError"
                                class="d-flex justify-center"
                                style="width: 100%"
                            >
                                <div
                                    class="text-center font-weight-bold red--text"
                                >
                                    Something went wrong, Please retry.
                                </div>
                            </div>
                            <div
                                v-else
                                class="d-flex"
                                v-for="plan in originPlans"
                                :key="plan.name"
                            >
                                <SumoPlan
                                    :sumoPlanDetails="sumoPlanDetails"
                                    v-if="plan.name === 'sumo_saver'"
                                    :plan="plan"
                                    @soleDialog="soleDialog"
                                    @click.native="
                                        selectPlan(
                                            {
                                                ...plan,
                                                ...{ name: sumoPlanName }
                                            },
                                            'sumo'
                                        )
                                    "
                                    :isActive="selected_plan"
                                >
                                </SumoPlan>
                                <SolePlan
                                    v-else
                                    :plan="plan"
                                    @soleDialog="soleDialog"
                                    @click.native="selectPlan(plan)"
                                    :isActive="selected_plan"
                                ></SolePlan>
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
                <WaterService
                    :leadSummary="leadSummary"
                ></WaterService>
            </v-tab-item>
            <v-tab-item>
                <InternetService
                    :leadSummary="leadSummary"
                ></InternetService>
            </v-tab-item>
        </v-tabs>
        <div class="d-flex justify-end py-4 px-4" style="width: 100%; background-color: white;">
            <v-btn
                :disabled="isDisable()"
                color="#542E89"
                @click="submit"
                class="white--text"
            >
                Submit for connection
            </v-btn>
        </div>

        <v-dialog v-model="solePlanDialog" max-width="1200">
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
import leadApplicationService from "@scripts/services/crm/LeadApplicationService";
import EnergyPlan from "@scripts/components/crm/leadmanagement/EnergyPlan";
import InternetPlan from "@scripts/components/crm/leadmanagement/InternetPlan";
import EAPlanService from "@scripts/services/ea/EAPlanService";
import { PLAN_TYPE_TOTAL } from "@scripts/models/ea/EnergyPlan";
import EnergyPlanDetails from "@scripts/components/ea/EnergyPlanDetails";
import InternetPlanDetails from "@scripts/components/ea/InternetPlanDetails";
import WaterService from "@scripts/components/crm/leadmanagement/WaterService";
import InternetService from "@scripts/components/crm/leadmanagement/InternetService";
import { isNull } from "lodash-es";
import SolePlan from "@scripts/components/crm/leadmanagement/SolePlan";
import SumoPlan from "@scripts/components/crm/leadmanagement/SumoPlan";
import ServiceProvideres from "@scripts/data/ServiceProvideres";
import SumoService from "@scripts/services/crm/SumoService";
import SoleDetails from "@scripts/components/crm/leadmanagement/SoleDetails";
import SumoPlanDetails from "@scripts/modules/sumo/models/SumoPlanDetails";
import TemporaryConnection from "@scripts/components/crm/leadmanagement/TemporaryConnection";
import SameDayConnection from "@scripts/components/crm/leadmanagement/SameDayConnection";

export default {
    name: "ServiceApplications",
    components: {
        SumoPlan,
        SolePlan,
        InternetService,
        WaterService,
        EnergyPlan,
        InternetPlan,
        ServiceProvider,
        EnergyService,
        EnergyPlanDetails,
        InternetPlanDetails,
        SoleDetails,
        TemporaryConnection,
        SameDayConnection
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
            providerSpinner: null,
            services: ["Power", "Gas"],
            plans: [],
            plansFlag: false,
            selectedPlanType: PLAN_TYPE_TOTAL,
            viewPlanDialog: false,
            planTypeForDetails: null,
            activeService: "energy",
            selectedProviderId: 1,
            selectedPowerProvider: "",
            activeOriginPlan: "",
            activeEaPlan: "",
            waterStatus: null,
            originPlans: null,
            selected_plan: null,
            solePlanDialog: false,
            sumoPlanDetails: new SumoPlanDetails({}),
            isSumoLoading: false,
            sumoOptions: {
                isError: false,
                errorMsg: ""
            },
            isWaterFailed: false
        };
    },
    computed: {
        tab: {
            get() {
                return LeadApplicationService.getActiveServiceTab();
            },
            set(value) {
                leadApplicationService.setActiveServiceTab(value);
            }
        },
        sumoPlanName() {
            if (this.sumoPlanDetails) {
                return this.sumoPlanDetails?.plan_name ?? "";
            } else {
                return "";
            }
        },
        selectPlanTitle() {
            const services = this.leadSummary.service_interests;
            if (
                services &&
                services.includes("gas") &&
                services.includes("power")
            ) {
                return "Power & Gas";
            }
            return services && services.includes("gas")
                ? "Gas"
                : services && services.includes("power")
                ? "Power"
                : "";
        },
        getWaterStatus() {
            const status = LeadApplicationService.mapStatus(
                leadApplicationService.getServiceObj(
                    this.leadSummary.connection_services,
                    "water"
                )?.status
            );
            status.text === "Failed" ? (this.isWaterFailed = true) : (this.isWaterFailed = false);
            return status.text;
        },
        getInternetStatus() {
            return 'Connected';
        },
        providers() {
            return ServiceProvideres.filter(dt => {
                return dt.service_type === "energy";
            });
        },
        tabMapper() {
            return {
                Energy: 0,
                Water: 1,
                Internet: 2
            };
        },

        isPayeeSelectedForAfterHourSubmission() {
            return (
                this.afterHourFlag && isNull(this.leadSummary.after_hour_payee)
            );
        }
    },
    watch: {
        "leadSummary.service_interests"() {
            this.loadPlan();
        }
    },
    mounted() {
        this.loadPlan();
        this.loadSelectedPowerProvider();

        const updateAddress = address => {
            if (this.selectedPowerProvider === "sumo") {
                this.$eventBus.$emit("validate", this.setSumoDetailsData);
            }
        };
        this.$eventBus.$on("address_updated", updateAddress);
        this.$once("hook:beforeDestroy", () => {
            this.$eventBus.$off("address_updated", updateAddress);
        });
    },
    methods: {
        getPlanType() {
            let plan = null;
            switch (this.selectedPowerProvider) {
                case "ea":
                    plan = this.plans.find(p => p.key === this.activeEaPlan);
                    break;
                case "origin":
                    plan = this.originPlans.find(
                        p => p.name === this.activeOriginPlan
                    );
                    break;
                default:
                    console.log(
                        "getPlanType:defaultCase",
                        this.selectedPowerProvider
                    );
            }
            this.$emit(
                "updatePlan",
                {
                    ...plan,
                    provider: this.selectedPowerProvider,
                    service_area: "energy"
                },
                false
            );
        },
        isDisable() {
            switch (this.tab) {
                case this.tabMapper.Water:
                    return !LeadApplicationService.canSubmitWater(
                        this.leadSummary.connection_services
                    );
                case this.tabMapper.Energy:
                    return (
                        !LeadApplicationService.canSubmitEnergy(
                            this.leadSummary.connection_services
                        ) ||
                        isNull(this.selectedPowerProvider) ||
                        !this.selected_plan ||
                        this.isPayeeSelectedForAfterHourSubmission
                    );
                default:
                    return true;
            }
        },
        soleDialog() {
            this.solePlanDialog = !this.solePlanDialog;
        },
        planSelect(plan, isManual = false) {
            this.selectedPlanType = plan?.key;
            this.activeEaPlan = plan?.key;
            let newPlan = {
                name: plan.key,
                service_area: "energy"
            };
            this.selectedProviderId = "ea";
            this.selectPlan(newPlan);
            this.selectedPlanType = plan?.key;
            this.$emit(
                "updatePlan",
                {
                    ...plan,
                    provider: this.selectedProviderId,
                    service_area: "energy"
                },
                isManual
            );
        },
        isActive(service) {
            return this.leadSummary.service_types.includes(
                service.toLowerCase()
            )
                ? true
                : false;
        },
        async loadPlan() {
            const services = this.leadSummary.service_interests;
            if (services.includes("gas") || services.includes("power")) {
                this.plans = await EAPlanService.getAllPlans({
                    service_type: this.leadSummary.service_interests,
                    postcode: this.leadSummary.postcode,
                    state: this.leadSummary.state
                });
                this.plansFlag = true;
                this.getPlanType();
            } else {
                this.plansFlag = false;
            }
        },
        isServiceEditable(service) {
            return LeadApplicationService.canEditService(
                this.leadSummary.connection_services,
                service?.toLowerCase()
            );
        },
        updateService(service) {
            this.resetSelectedPlan();
            if (this.isServiceEditable(service)) {
                if (
                    (service === "Gas" || service === "Power") &&
                    this.selectedPowerProvider === "sumo"
                ) {
                    this.onSelectProvider("sumo");
                }

                this.$emit("updateService", service);
                if (this.selectedPowerProvider === "sumo") {
                    this.$eventBus.$emit("validate", this.setSumoDetailsData);
                }
            }
        },
        view(plan) {
            this.planTypeForDetails = plan.key;
            this.viewPlanDialog = true;
        },
        mapStatus(statusCode) {
            let statusText = "";
            switch (statusCode) {
                case 4:
                    statusText = "Submitted";
                    break;
                case 5:
                    statusText = "Connected";
                    break;
                case 7:
                    statusText = "In Progress";
                    break;
                case 9:
                    statusText = "Can not Connect";
                    break;
                case 10:
                    statusText = "Needs more info";
                    break;
                default:
                    break;
            }

            return statusText;
        },
        async setSumoDetailsData(name) {
            this.isSumoLoading = true;
            this.sumoPlanDetails = new SumoPlanDetails({});
            this.sumoOptions.isError = false;
            this.sumoOptions.errorMsg = "";
            try {
                let address = `${this.leadSummary.street_number} ${this.leadSummary.street_name_only} ${this.leadSummary.street_type} ${this.leadSummary.city} ${this.leadSummary.state} ${this.leadSummary.postcode}`;
                this.sumoPlanDetails = await SumoService.getPlans(
                    address,
                    this.leadSummary.service_interests,
                    this.leadSummary?.created_by_agent,
                    this.leadSummary
                );
                this.actionOnSelectProvider(name);
                this.isSumoLoading = false;
                return 0;
            } catch (error) {
                this.sumoOptions.isError = true;
                console.log("sumo sth went wrong");
                this.sumoOptions.errorMsg = "Something weng wrong, retry";
                this.sumoPlanDetails = new SumoPlanDetails();
            } finally {
                this.isSumoLoading = false;
            }
        },
        resetSelectedPlan() {
            this.selected_plan = null;
        },
        async onSelectProvider(name) {
            this.selectedPowerProvider = name;
            this.resetSelectedPlan();
            if(name === "ea") {}
            else if (name === "sumo") {
                //listening on ApplicationDetailsPage component
                this.$eventBus.$emit("validate", this.setSumoDetailsData);
                // await this.setSumoDetailsData(name);
            } else {
                this.isSumoLoading = false;
                this.actionOnSelectProvider(name);
            }
        },
        actionOnSelectProvider(name) {
            const providerData = this.providers.find(pl => {
                return pl.name === name;
            });
            this.originPlans = providerData.plans;
            this.selectedProviderId = name;
        },
        selectPlan(plan, provider = null) {
            if (this.selectedPowerProvider === "ea") {
                this.activeEaPlan = plan.name;
                this.activeOriginPlan = "";
            }

            if (this.selectedPowerProvider === "origin") {
                this.activeOriginPlan = plan.name;
                this.activeEaPlan = "";
            }
            this.selected_plan = plan.name;

            this.activeOriginPlan = plan.name;
            //todo update provider array for sumo plan
            let payload = {
                service_type: this.leadSummary?.service_interests,
                provider_name: this.selectedPowerProvider,
                plan_type: plan.name,
                service_area: "energy"
            };

            if (this.selectedPowerProvider !== "") {
                this.activeOriginPlan = plan.name;
                LeadApplicationService.updateApplicationProviders(
                    payload,
                    this.leadSummary.id
                );
            }
            this.$emit(
                "updatePlan",
                {
                    active: false,
                    key: plan.name,
                    title: plan.title,
                    provider: this.selectedProviderId
                },
                true
            );
        },
        loadSelectedPowerProvider() {
            const connectionService = this.leadSummary.connection_services.find(
                data =>
                    data.service_type === "power" || data.service_type === "gas"
            );

            this.selectedPowerProvider = connectionService?.provider_name;
            this.selected_plan = connectionService?.plan_type;

            if (this.selectedPowerProvider === "sumo") {
                setTimeout(
                    () =>
                        this.$eventBus.$emit(
                            "validate",
                            this.setSumoDetailsData
                        ),
                    600
                );
            }

            if (this.selectedPowerProvider === "ea") {
                this.activeEaPlan = connectionService?.plan_type;
            }

            if (this.selectedPowerProvider === "origin") {
                this.activeOriginPlan = connectionService?.plan_type;
                this.actionOnSelectProvider("origin");
            }
        },
        submit() {
            let subType = "energy";
            if (this.tabMapper.Energy === this.tab) {
                subType = "energy";
            } else if (this.tabMapper.Water === this.tab) {
                subType = "water";
            } else {
                subType = "internet";
            }
            this.$eventBus.$emit("busUtilitySubmit", subType);
        },
        changeAfterHourPayee() {
            this.$emit("updateDraft", "after_hour_payee", this.leadSummary.after_hour_payee, false, null, false);
        }
    }
};
</script>

<style scoped>
.active-power-subtitle {
    font-size: 12px !important;
}

.tab-capital-case {
    text-transform: capitalize !important;
    width: 180px !important;
}
.service-title {
    font-size: 16px;
    font-weight: bold;
}
.sumo-loading-container {
    flex: 1;
    text-align: center;
}
.dangerText {
    color: red;
}
</style>
