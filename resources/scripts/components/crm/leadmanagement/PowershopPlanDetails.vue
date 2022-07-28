<template>
    <v-app>
        <div>
            <div class="card-section">
                <div class="plan-title-header">
                    <div class="d-flex align-center">
                        <v-img
                            max-height="50"
                            max-width="50"
                            class="mr-2"
                            src="/assets/images/logo/providers/powershop_logo.png"
                        ></v-img>
                        <h3>Powershop</h3>
                    </div>
                    <div>
                        <h2>100% Carbon Neutral Plan</h2>
                        <p>{{ getServiceText }}</p>
                    </div>
                </div>

                <ElectricityPlan v-if="willShowElectricity" :plan="planDetails.plans.electricity"></ElectricityPlan>

                <GasPlan v-if="willShowGas" :plan="planDetails.plans.gas"></GasPlan>

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
                                    <li>GreenPower available. To support Australian renewable projects.</li>
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
                                    Solar feed in tariff if applicable (Excl. GST)
                                </div>
                                <div>6.7c/kWh</div>
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
                                            <div>
                                                <p class="mb-1"><a class="linkable"
                                                                   href="#">SingeRate-controlload-cz6</a></p>
                                                <p class="mb-1"><a class="linkable"
                                                                   href="#">SingeRate-controlload-cz7</a></p>
                                                <p class="mb-1"><a class="linkable" href="#">SingeRate-cz6</a></p>
                                                <p class="mb-1"><a class="linkable" href="#">SingeRate-cz7</a></p>
                                                <p class="mb-1"><a class="linkable" href="#">TimeofUse-cz6</a></p>
                                                <p class="mb-1"><a class="linkable" href="#">TimeofUse-cz7</a></p>
                                                <p class="mb-1"><a class="linkable"
                                                                   href="#">TimeofUse-controlLoad-cz6</a></p>
                                                <p class="mb-1"><a class="linkable"
                                                                   href="#">TimeofUse-controlLoad-cz7</a></p>
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
                                            <div>
                                                <p class="mb-1"><a class="linkable" href="#">cardinia</a></p>
                                                <p class="mb-1"><a class="linkable" href="#">central</a></p>
                                                <p class="mb-1"><a class="linkable" href="#">combined-all</a></p>
                                                <p class="mb-1"><a class="linkable" href="#">murray</a></p>
                                                <p class="mb-1"><a class="linkable" href="#">north</a></p>
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
        </div>
    </v-app>
</template>

<script>
import ElectricityPlan from "@scripts/components/powershop/ElectricityPlan"
import GasPlan from "@scripts/components/powershop/GasPlan"
import PowershopService from "@scripts/modules/powershop/services/PowershopService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";

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
        leadSummary: {
            require: true
        },
    },
    data() {
        return {
            opened: 0,
            planDetails: null,
        }
    },
    computed: {
        getServiceText() {
            switch (this.serviceType) {
                case "power":
                    return "Electricity";
                case "gas":
                    return "Gas";
                default:
                    return "Electricity & Gas";
            }
        },
        isBothEnergySubmit() {
            return UtilityStoreService.getIsBothEnergySelected() || this.serviceType === 'energy';
        },
        willShowElectricity() {
            return this.planDetails?.plans?.electricity && (this.serviceType === "power" || this.isBothEnergySubmit);
        },
        willShowGas() {
            return this.planDetails?.plans?.gas && (this.serviceType === "gas" || this.isBothEnergySubmit);
        },
        isVictoria() {
            return this.leadSummary.state === 'Victoria';
        }
    },
    watch: {},
    mounted() {
        this.getPowershopData();
    },
    methods: {
        async getPowershopData() {
            this.planDetails = await PowershopService.getPowershopData();
            console.log('Plan details data from powershop component: ', this.planDetails);
        },
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
    border: 4px solid #F1186C;
    border-radius: 8px;
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
