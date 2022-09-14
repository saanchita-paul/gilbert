<template>
    <v-app>
        <div class="card-section">
            <ElectricityPlan v-if="willShowElectricity" :plan="planDetails.plans.electricity"
                                 :victoriaState="isVictoria" :selectedPlan="getSelectedElecPlanDetails"></ElectricityPlan>

            <GasPlan v-if="willShowGas" :plan="planDetails.plans.gas" :selectedPlan="getSelectedGasPlanDetails"></GasPlan>

            <div class="plan-details">
                <v-card>
                    <div style="padding: 20px 10px">
                        <div class="mb-2">
                            <h2 class="font-weight-bold plan-heading-text">Plan Details</h2>
                        </div>
                        <div>
                            <ul class="paragraph-text ">
                                <li>100% carbon neutral plan at no additional fee.</li>
                                <li>Get rewarded. Rewards and extras available through the Powershop Shop.</li>
                                <!-- <li>GreenPower available. To support Australian renewable projects.</li> -->
                            </ul>
                        </div>

                    </div>
                </v-card>
            </div>

            <div class="plan-details">
                <v-card>
                    <div style="padding: 20px 10px">
                        <div class="mb-2">
                            <h2 class="font-weight-bold plan-heading-text">Solar feed in tariff</h2>
                        </div>

                        <div class="price-list">
                            <div class="font-weight-bold" style="font-size:14px">
                                Solar feed in tariff c/kWh
                            </div>
                            <div>{{ getSolarFeedInTariff }} </div>
                        </div>

                    </div>
                </v-card>
            </div>

            <div class="plan-details">
                <v-card>
                    <div style="padding: 10px">
                        <div class="mb-2">
                            <h2 class="font-weight-bold plan-heading-text">
                                {{ isVictoria ? 'VEFS' : 'BPID' }} links</h2>
                        </div>

                        <div v-if="willShowElectricity">
                            <v-divider></v-divider>
                            <v-expansion-panels :value="opened">
                                <v-expansion-panel>

                                    <v-expansion-panel-header class="font-weight-bold" style="font-size: 16px">
                                        Electricity Fact Sheets
                                        <template v-slot:actions>
                                            <v-icon color="#F1186C">
                                                $expand
                                            </v-icon>
                                        </template>
                                    </v-expansion-panel-header>

                                    <v-expansion-panel-content>
                                        <div v-if="electricityBPIDLinksList">
                                            <p class="mb-1" v-for="bpid_link in electricityBPIDLinksList"
                                                :key="bpid_link.id">
                                                <a class="linkable" :href="bpid_link.file_url"
                                                    target="_blank">{{ bpid_link.title }}</a>
                                            </p>
                                        </div>
                                    </v-expansion-panel-content>
                                </v-expansion-panel>
                            </v-expansion-panels>
                        </div>
                    </div>

                    <div style="padding: 0 10px" v-if="willShowGas">
                        <v-divider></v-divider>
                        <v-expansion-panels :value="opened">
                            <v-expansion-panel>

                                <v-expansion-panel-header class="font-weight-bold" style="font-size: 16px">
                                    Gas Fact Sheets
                                    <template v-slot:actions>
                                        <v-icon color="#F1186C">
                                            $expand
                                        </v-icon>
                                    </template>
                                </v-expansion-panel-header>

                                <v-expansion-panel-content>
                                    <div v-if="gasBPIDLinksList">
                                        <p class="mb-1" v-for="bpid_link in gasBPIDLinksList" :key="bpid_link.id">
                                            <a class="linkable" :href="bpid_link.file_url"
                                                target="_blank">{{ bpid_link.title }}</a>
                                        </p>
                                    </div>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </div>
                </v-card>
            </div>

            <v-btn class="selectButton" color="#FA0C69" style="border-radius: 10px !important;"
                    @click="closeDialog">Select Plan
            </v-btn>
        </div>
        <!-- </div> -->
    </v-app>
</template>

<script>
import ElectricityPlan from "@scripts/components/powershop/ElectricityPlan";
import GasPlan from "@scripts/components/powershop/GasPlan";
import PowershopService from "@scripts/modules/powershop/services/PowershopService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";
import PowershopMapper from "@scripts/modules/powershop/api/mappers/PowershopMapper";

export default {
    name: "PowershopPlanDetails",
    components: {
        ElectricityPlan,
        GasPlan,
    },
    props: {
        serviceType: {
            require: true
        },
        state: {
            require: true
        },
        planDetails: {
            require: true
        },
        plan: {
            require: true
        },
        gasPlan: {
            require: false
        },
    },
    data() {
        return {
            opened: 0,

        }
    },
    computed: {
        getServiceText() {
            return PowershopMapper.mapServiceText(this.serviceType);
        },

        willShowElectricity() {
            return this.planDetails?.plans?.electricity && (this.serviceType === "power" || this.serviceType === "energy");
        },

        willShowGas() {
            return this.planDetails?.plans?.gas && (this.serviceType === "gas" || this.serviceType === "energy") && this.getSelectedGasPlanDetails;
        },

        isVictoria() {
            return this.state.toLowerCase() === 'victoria';
        },

        electricityBPIDLinksList() {
            if (this.getSelectedElecPlanDetails){
                return PowershopMapper.mapBPIDLinks(this.getSelectedElecPlanDetails.bpid_links);
            }
            return this.planDetails?.plans?.electricity?.bpid_links || [];
        },

        gasBPIDLinksList() {
            if (this.getSelectedGasPlanDetails){
                return PowershopMapper.mapBPIDLinks(this.getSelectedGasPlanDetails.bpid_links);
            }
            return this.planDetails?.plans?.gas?.bpid_links || [];
        },

        getSolarFeedInTariff() {
            return this.planDetails?.plans?.electricity?.solar_buy_pack_value ? parseFloat(this.planDetails?.plans?.electricity?.solar_buy_pack_value).toFixed(1) : "N/A";
        },
        getSelectedElecPlanDetails() {
            let plan = this.planDetails?.plans?.electricity?.vdo.find((item) => item.name === this.plan);
            if (plan === undefined) plan = this.planDetails?.plans?.electricity?.vdo[0] ?? null;
            return plan;
        },
        getSelectedGasPlanDetails() {
            if (this.serviceType === "energy"){
                let plan = this.planDetails?.plans?.gas?.vdo.find((item) => item.name === this.gasPlan);
                if (plan === undefined) plan = this.planDetails?.plans?.gas?.vdo[0] ?? null;
                return plan;
            }
            if (this.serviceType === "gas"){
                let plan = this.planDetails?.plans?.gas?.vdo.find((item) => item.name === this.plan);
                if (plan === undefined) plan = this.planDetails?.plans?.gas?.vdo[0] ?? null;
                return plan;
            }
            return null;
        }
        // getPlanTitle() {
        //     if (this.serviceType === "energy" || this.serviceType === "power") {
        //         return (this.planDetails?.plans?.electricity?.vdo.find((item) => item.name === this.plan))?.marketing_offer_name;
        //     }

        //     return 'Powershop 100% Carbon Neutral';
        // }
    },
    watch: {},
    mounted() {
    },
    methods: {
        closeDialog() {
            this.$emit('toggleDialog')
        }
    },
}
</script>

<style scoped>
.card-section {
    max-width: 450px;
    margin: 0 auto;
    border: 2px solid #F1186C;
    border-radius: 8px;
    font-family: Arial, Helvetica, sans-serif;
}

.selectButton {
    color: white;
    font-weight: bold;
    font-size: 16px;
    width: 95%;
    padding-bottom: 4px;
    padding-top: 10px;
    border-radius: 8px;
}

.v-size--default {
    height: 50px !important;
    min-width: 64px !important;
    padding: 0 16px !important;
    margin: 10px auto !important;
    display: block !important;
}

.plan-title-header {
    background-color: #F1186C;
    color: white;
    padding: 10px;
}

.plan-heading-text {
    color: #662445;
}

.plan-details {
    margin: 20px 0;
    padding: 0 10px;
}

.price-list {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.plan-details .v-expansion-panel::before {
    box-shadow: none !important;
}

.v-expansion-panel-header {
    padding: 0;
}

.theme--light.v-expansion-panels >>> .v-expansion-panel-content__wrap {
    padding: 0;
}

.v-sheet.v-card {
    border-radius: 10px;
}

.paragraph-text {
    font-size: 14px;
    font-weight: 400;
}

.linkable {
    color: #F1186C;
    font-size: 14px;
}
</style>
