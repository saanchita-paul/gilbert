<template>
    <v-app>
        <div fluid>
            <v-container>
                <div class="card-section">
                    <div class="plan-title-header">
                        <div class="d-flex">
                            <img src="" alt="">
                            <h3>Powershop</h3>
                        </div>
                        <div>
                            <h2>100% Carbon Neutral Plan</h2>
                            <p>Electricity & Gas</p>
                        </div>
                    </div>

                    <ElectricityPlan></ElectricityPlan>

                    <GasPlan></GasPlan>

                    <div class="plan-details">
                        <v-card>
                            <div style="padding: 20px 10px">
                                <div>
                                    <h2 class="font-weight-bold plan-heading-text">Plan Details</h2>
                                </div>
                                <div>
                                    <p>This offer is an ongoing contract, until you or we end it.</p>
                                </div>

                            </div>
                        </v-card>
                    </div>

                    <div class="plan-details">
                        <v-card>
                            <div style="padding: 20px 10px">
                                <div>
                                    <h2 class="font-weight-bold plan-heading-text">Solar feed in tariff</h2>
                                </div>

                                <div class="price-list">
                                    <div class="plan-text" style="font-size:14px">
                                        Solar feed in tariff if applicable (Excl. GST)
                                    </div>
                                    <div  class="plan-text">6.7c/kWh</div>
                                </div>

                            </div>
                        </v-card>
                    </div>

                    <div class="plan-details">
                        <v-card>
                            <div style="padding: 20px 10px">
                                <div>
                                    <h2 class="font-weight-bold plan-heading-text">BPID links</h2>
                                </div>

                                <v-divider></v-divider>

                                <div>
                                    <v-expansion-panels>
                                        <v-expansion-panel>

                                            <v-expansion-panel-header class="font-weight-bold">
                                                Electricity Fact Sheets
                                            </v-expansion-panel-header>

                                            <v-expansion-panel-content>

                                                <div class="">
                                                    <div>
                                                        <a href="#">SingeRate-controlload-cz6</a>
                                                        <a href="#">SingeRate-controlload-cz7</a>
                                                    </div>
                                                </div>

                                            </v-expansion-panel-content>
                                        </v-expansion-panel>
                                    </v-expansion-panels>
                                </div>
                            </div>

                            <div style="padding: 20px 10px">

                                <v-divider></v-divider>

                                <div>
                                    <v-expansion-panels>
                                        <v-expansion-panel>

                                            <v-expansion-panel-header class="font-weight-bold">
                                                Gas Fact Sheets
                                            </v-expansion-panel-header>

                                            <v-expansion-panel-content>

                                                <div class="price-list">
                                                    <div class="plan-text" style="font-size:14px">
                                                        <a href="#">cardinia</a>
                                                        <a href="#">central</a>
                                                    </div>
                                                </div>

                                            </v-expansion-panel-content>
                                        </v-expansion-panel>
                                    </v-expansion-panels>
                                </div>
                            </div>


                        </v-card>
                    </div>

                    <v-btn class="selectButton" color="#FA0C69">Select Plan</v-btn>
                </div>
            </v-container>
        </div>
    </v-app>
</template>

<script>
import ElectricityPlan from "@scripts/components/powershop/ElectricityPlan"
import GasPlan from "@scripts/components/powershop/GasPlan"

export default {
    name: "PowershopPlanDetails",
    components: {
        ElectricityPlan,
        GasPlan,
    },
    props: {},
    data() {
        return {}
    },
    computed: {},
    watch: {},
    mounted() {
    },
    methods: {},
}
</script>

<style scoped>

.card-section {
    max-width: 450px;
    margin: 0 auto;
    border: 4px solid #F1186C;
    border-radius: 10px;
}

.selectButton {
    color: white;
    font-weight: bold;
    font-size: 16px;
    width: 80%;
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
    margin: 10px 0;
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

</style>
