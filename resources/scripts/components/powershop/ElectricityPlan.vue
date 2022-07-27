<template>
    <div>

        <div class="plan-details">
            <v-card>
                <div style="padding: 20px 10px">
                    <div class="text-center">
                        <div class="d-flex justify-center align-center">
                            <v-icon color="yellow" size="20" class="mr-2">mdi-flash</v-icon>
                            <h2 class="font-weight-bold plan-heading-text">Electricity</h2>
                        </div>
                        <div>
                            <h3>$1,249/year (inc. GST)</h3>
                            <h3>1% less than the</h3>
                            <p>Reference Pricing</p>
                        </div>
                    </div>
                    <div>
                        <p>Estimated cost and comparison for a residential customer using 4000kWh on a Single rate
                            tariff in the CitiPower network.</p>
                        <p>Your actual bills will vary depending on your usage and any price changes in the future.
                            You'll be notified of any change in accordance with our regulatory requirements.</p>
                    </div>
                    <div class="d-flex">
                        <h4 class="font-weight-bold plan-heading-text">Your distributor</h4>
                        <p>Jemena</p>
                    </div>
                    <v-divider></v-divider>
                    <div>
                        <v-expansion-panels>
                            <v-expansion-panel>

                                <v-expansion-panel-header class="font-weight-bold">
                                    Electricity usage and rates
                                </v-expansion-panel-header>

                                <v-expansion-panel-content>

                                    <div class="d-flex">
                                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Supply Charge</p>
                                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                            mdi-progress-question
                                        </v-icon>
                                    </div>

                                    <div class="price-list">
                                        <div class="plan-text" style="font-size:14px">
                                            Daily Supply Charge (¢/day)
                                        </div>
                                        <div  class="plan-text">114.90</div>
                                    </div>


                                    <div class="d-flex">
                                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Usage Charges</p>
                                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                            mdi-progress-question
                                        </v-icon>
                                    </div>

                                    <div class="price-list">
                                        <div class="plan-text" style="font-size:14px">
                                            Single rate tariff (¢/kWh)
                                        </div>
                                        <div  class="plan-text">23.05</div>
                                    </div>

                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </div>
                </div>
            </v-card>
        </div>

    </div>

</template>

<script>
export default {
    props: {},
    methods: {
        closeDialog() {
            this.$emit('toggleDialog')
        }
    }
}
</script>

<style scoped>

.plan-details {
    margin: 10px 0;
    padding: 0 10px;
}

.plan-details .v-expansion-panel::before {
    box-shadow: none !important;
}

.price-list {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.plan-heading-text {
    color: #662445;
}

.v-expansion-panel-header {
     padding: 0;
}

.theme--light.v-expansion-panels >>> .v-expansion-panel-content__wrap {
     padding: 0;
}

</style>
