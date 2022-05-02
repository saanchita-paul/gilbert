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
                            <v-icon color="yellow">mdi-flash</v-icon>Power
                        </span>
                    </p>
                    <EnergyStatus :leadSummary="leadSummary" title="Power"/>
                </v-card>
            </v-tab>
            <v-tab class="px-0 py-3 tab-capital-case">
                <v-card class="hood-card" width="100%">
                    <p class="pt-2 pb-1 mb-0 services service-title">
                        <span class="ml-1">
                            <v-icon color="red">mdi-fire</v-icon>Gas
                        </span>
                    </p>
                    <EnergyStatus :leadSummary="leadSummary" title="Gas"/>
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

            <v-tabs-items v-model="tab">
                <v-tab-item>
                    <PowerService
                        :leadSummary="leadSummary"
                        :afterHourFlag="afterHourFlag"
                        @updatePlan="updatePlan"
                        @changeAfterHourPayee="changeAfterHourPayee"
                    ></PowerService>
                </v-tab-item>
                <v-tab-item>
                    <GasService
                        :leadSummary="leadSummary"
                        :afterHourFlag="afterHourFlag"
                        @updatePlan="updatePlan"
                    ></GasService>
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
            </v-tabs-items>
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
    </v-row>
</template>

<script>
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import WaterService from "@scripts/components/crm/leadmanagement/WaterService";
import InternetService from "@scripts/components/crm/leadmanagement/InternetService";
import { isNull } from "lodash-es";
import EnergyStatus from "@scripts/components/crm/leadmanagement/EnergyStatus";
import PowerService from "@scripts/components/crm/leadmanagement/PowerService";
import GasService from "@scripts/components/crm/leadmanagement/GasService";

export default {
    name: "ServiceApplications",
    components: {
        PowerService,
        GasService,
        WaterService,
        InternetService,
        EnergyStatus
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
            selectedPowerProvider: "",
            selected_plan: null,
            isWaterFailed: false,
        };
    },
    computed: {
        tab: {
            get() {
                return LeadApplicationService.getActiveServiceTab();
            },
            set(value) {
                LeadApplicationService.setActiveServiceTab(value);
            }
        },
        tabMapper() {
            return {
                Power: 0,
                Gas: 1,
                Water: 2,
                Internet: 3
            };
        },
        getWaterStatus() {
            const status = LeadApplicationService.mapStatus(
                LeadApplicationService.getServiceObj(
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
        isPayeeSelectedForAfterHourSubmission() {
            return (
                this.afterHourFlag && isNull(this.leadSummary.after_hour_payee)
            );
        }
    },
    methods: {
        //todo disable submit if already submitted
        //todo disable submit if isBothSelected but for one of them provider/plan is not selected
        isDisable() {
            switch (this.tab) {
                case this.tabMapper.Power:
                    return (
                        !LeadApplicationService.canSubmitEnergy(
                            this.leadSummary.connection_services
                        ) ||
                        isNull(this.selectedPowerProvider) ||
                        !this.selected_plan ||
                        this.isPayeeSelectedForAfterHourSubmission
                    );
                case this.tabMapper.Gas:
                    return (
                        !LeadApplicationService.canSubmitEnergy(
                            this.leadSummary.connection_services
                        ) ||
                        isNull(this.selectedPowerProvider) ||
                        !this.selected_plan ||
                        this.isPayeeSelectedForAfterHourSubmission
                    );
                case this.tabMapper.Water:
                    return !LeadApplicationService.canSubmitWater(
                        this.leadSummary.connection_services
                    );
                default:
                    return true;
            }
        },
        //todo before submit check if single service or both selected, then submit base on that
        submit() {
            let subType = "energy";
            if (this.tabMapper.Power === this.tab) {
                subType = "energy";
            } else if (this.tabMapper.Gas === this.tab) {
                subType = "energy";
            }  else if (this.tabMapper.Water === this.tab) {
                subType = "water";
            } else {
                subType = "internet";
            }
            this.$eventBus.$emit("busUtilitySubmit", subType);
        },
        //todo modify to add new service (Power, Gas) to application
        updateService(service) {
            // this.resetSelectedPlan();
            // if (
            //     (service === "Gas" || service === "Power") &&
            //     this.selectedPowerProvider === "sumo"
            // ) {
            //     this.onSelectProvider("sumo");
            // }

            // this.$emit("updateService", service);
            // if (this.selectedPowerProvider === "sumo") {
            //     this.$eventBus.$emit("validate", this.setSumoDetailsData);
            // }
        },
        updatePlan(plan, isManual) {
            console.log('updatePlan', plan);
            this.$emit('updatePlan', plan, isManual);
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
.dangerText {
    color: red;
}
.not-editable {
    cursor: not-allowed;
}
</style>
