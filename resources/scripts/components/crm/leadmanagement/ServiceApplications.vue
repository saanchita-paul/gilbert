<template>
    <v-row class="ml-0">
        <v-tabs
            v-model="tab"
            height="80px"

        >
            <v-tab class="px-0 tab-capital-case">
                <v-card class="hood-card" width="100%">
                    <p class="pt-1 pb-1 mb-0 services service-title">
                        <span>
                            <v-icon color="yellow">mdi-flash</v-icon>Power
                        </span>
                    </p>
                    <EnergyStatus :leadSummary="leadSummary" type="power"/>
                </v-card>
            </v-tab>
            <v-tab class="px-0 tab-capital-case">
                <v-card class="hood-card" width="100%">
                    <p class="pt-1 pb-1 mb-0 services service-title">
                        <span class="ml-1">
                            <v-icon color="red">mdi-fire</v-icon>Gas
                        </span>
                    </p>
                    <EnergyStatus :leadSummary="leadSummary" type="gas"/>
                </v-card>
            </v-tab>
            <v-tab class="px-0 tab-capital-case">
                <v-card class="hood-card" width="100%">
                    <p class="pt-1 pb-1 mb-0 services service-title">
                        <span class="ml-1">
                            <v-icon color="blue">mdi-water</v-icon>Water
                        </span>
                    </p>
                    <p class="py-0 my-0 text-center active-power-subtitle"
                        :class="{ dangerText: isWaterFailed }"
                    >
                        {{ getWaterStatus }}
                    </p>
                </v-card>
            </v-tab>
            <v-tab class="px-0 tab-capital-case">
                <v-card class="hood-card" width="100%">
                    <div>
                        <p class="pt-1 pb-1 mb-0 services service-title">
                            <span class="ml-1">
                                <v-icon color="red">mdi-wifi</v-icon>Internet
                            </span>
                        </p>
                        <p class="py-0 my-0 text-center active-power-subtitle"
                        >
                            {{ getInternetStatus }}
                        </p>
                    </div>
                </v-card>
            </v-tab>

            <v-tabs-items v-model="tab" class="p-24">
                <v-tab-item>
                    <PowerService
                        :leadSummary="leadSummary"
                        :afterHourFlag="afterHourFlag"
                        @changeAfterHourPayee="changeAfterHourPayee"
                        @serviceType="serviceType"
                    ></PowerService>
                </v-tab-item>
                <v-tab-item>
                    <GasService
                        :leadSummary="leadSummary"
                        :afterHourFlag="afterHourFlag"
                        @serviceType="serviceType"
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
    </v-row>
</template>

<script>
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import WaterService from "@scripts/components/crm/leadmanagement/WaterService";
import InternetService from "@scripts/components/crm/leadmanagement/InternetService";
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
    },
    methods: {
        changeAfterHourPayee() {
            this.$emit("updateDraft", "after_hour_payee", this.leadSummary.after_hour_payee, false, null, false);
        },
        serviceType(value) {
            this.$emit("serviceType", value);
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
    width: 190px !important;
}
.service-title {
    font-size: 16px;
    font-weight: bold;
}
.dangerText {
    color: red;
}
.p-24 {
    padding: 24px !important;
}
</style>
