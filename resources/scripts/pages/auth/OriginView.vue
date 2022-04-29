<template>
    <v-app>
        <div fluid>
            <v-container fluid>
               <v-card class="card-section">
                   <div class="plan-title-header pt-4 pl-4 pb-2">
                       <p class="font-weight-bold mb-0" style="font-size:24px"> Origin Plan - Origin Go </p>
                       <p style="font-size:20px">Electricity & Gas </p>
                   </div>
                   <div class="pl-6 pt-8">
                       <p class="font-weight-bold" style="font-size:20px">Great rates that wont change over 12 months</p>
                       <p class="pb-4">Rates guaranteed for 12 months, simplified pricing, Everyday Rewards points</p>
                   </div>
                    <div class="plan-details">
                        <div class="d-flex">
                            <v-icon color="yellow" size="20" class="pb-4 pr-2">mdi-flash</v-icon>
                            <p class="font-weight-bold"> Electricity </p>
                        </div>
                       <div class="pb-2">
                            <p class="font-weight-bold" style="font-size:26px">$1,212/year</p>
                            <p class="font-weight-bold" style="font-size:14px">70% off the Victorian Default Offer Reference Price</p>
                            <p class="plan-content">Estimated cost inc GST for an average household using 3900 kWh/yearly on a flat rate tariff in the Ausgrid network.</p>
                       </div>
                       <v-expansion-panels >
                            <v-expansion-panel color="red">
                                 <v-expansion-panel-header class="font-weight-bold">
                                    See electricity prices
                                    <template v-slot:actions><v-icon color="orange">mdi-menu-down</v-icon></template> 
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <div class="d-flex">
                                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Supply Charge</p>
                                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                            mdi-progress-question
                                        </v-icon>
                                    </div>
                                    <div class="d-flex">
                                        <p class="pr-12 plan-text" style="font-size:14px">Daily Supply Charge
                                            <span>(c/day)</span>
                                        </p>
                                        <p class="pl-14 plan-text">76.99</p>
                                    </div>

                                    <div class="d-flex">
                                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Usage Charges</p>
                                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                            mdi-progress-question
                                        </v-icon>
                                    </div>
                                    <div class="d-flex">
                                        <p class="pr-15 plan-text" style="font-size:14px">Peak Usage
                                            <span>(c/kWh)</span>
                                        </p>
                                        <p class="pl-16 plan-text">23.87 </p>
                                    </div>
                                    <p class="plan-text">We’ve already included any discounts in the rates above. All prices are inclusive of GST.</p>
                                    <p class="plan-text">Rates are rounded up to the nearest 2 decimal places where applicable.</p>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                    </v-expansion-panels>
                    </div>

                    <div class="plan-details">
                        <div class="d-flex">
                            <v-icon color="#FF5722" size="20" class="pb-4 pr-2">mdi-fire</v-icon>
                             <p class="font-weight-bold"> Natural Gas </p>
                        </div>
                       <div class="pb-2">
                            <p class="font-weight-bold" style="font-size:26px">$1,437/year</p>
                            <p class="font-weight-bold" style="font-size:14px">Our 12month fixed rates</p>
                            <p class="plan-content">Estimated cost inc GST for an average household using 3900 kWh/yearly on a flat rate tariff in the Ausgrid network.</p>
                       </div>
                       <v-expansion-panels>
                            <v-expansion-panel color="red">
                                 <v-expansion-panel-header class="font-weight-bold">
                                    See natural gas prices
                                    <template v-slot:actions><v-icon color="orange">mdi-menu-down</v-icon></template> 
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <div class="d-flex">
                                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Supply Charge</p>
                                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                            mdi-progress-question
                                        </v-icon>
                                    </div>
                                    <div class="d-flex">
                                        <p class="pr-12 plan-text" style="font-size:14px">Daily Supply Charge
                                            <span>(c/day)</span>
                                        </p>
                                        <p class="pl-14 plan-text">76.99</p>
                                    </div>

                                    <div class="d-flex">
                                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Usage Charges</p>
                                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                            mdi-progress-question
                                        </v-icon>
                                    </div>
                                    <div class="d-flex">
                                        <p class="pr-15 plan-text" style="font-size:14px">Peak Usage
                                            <span>(c/MJ)</span>
                                        </p>
                                        <p class="pl-16 plan-text">22.87 </p>
                                    </div>
                                    <div class="d-flex">
                                        <p class="pr-15 plan-text" style="font-size:14px">Off Peak Usage
                                            <span>(c/MJ)</span>
                                        </p>
                                        <p class="pl-16 plan-text">{{ value }} </p>
                                    </div>
                                    <p class="plan-text">We’ve already included any discounts in the rates above. All prices are inclusive of GST.</p>
                                    <p class="plan-text">Rates are rounded up to the nearest 2 decimal places where applicable.</p>
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                    </v-expansion-panels>
                    </div>

                    <div class="pl-8 pr-8">
                        <p style="font-weight-bold font-size:14px">Inlcuded in your plan</p>
                        <div class="d-flex">
                            <p class="font-weight-bold mb-0" style="font-size:14px;">Rates: </p>
                            <div class="d-flex">
                                <p class="pl-2 mb-0" style="font-size:14px;">Guarranteed</p>
                                <v-icon aria-hidden="false" class="pl-1 mb-0" size="80%">
                                    mdi-progress-question
                                </v-icon>
                            </div>
                        </div>
                        <div class="d-flex">
                            <p class="font-weight-bold mb-0" style="font-size:14px;">Exit Fees: </p>
                            <p class="pl-2 mb-0" style="font-size:14px;">No</p>
                        </div>
                        <div class="d-flex">
                            <p class="font-weight-bold mb-0" style="font-size:14px;">Benefit Period: </p>
                            <p class="pl-2 mb-0" style="font-size:14px;">12 months</p>
                        </div>
                        <p class="font-weight-bold mb-0" style="font-size:14px;">Green options</p>
                        <p class="plan-text mt-0" style="font-size:14px;">Everyday Rewards members enjoy 25% GreenPower and 100% Green Gas for nothing extra</p>
                        <hr class="mb-4" style="width:95%">

                        <div class="pb-6" style="font-size:14px; text-decoration: underline;">
                            <p class="mb-1">Energy Fact Sheet (Electricity)</p>
                            <p class="mb-1">Energy Fact Sheet (Gas)</p>
                            <p class="mb-1">Terms and conditions</p>
                        </div>
                    </div>

                    <v-btn block class="selectButton" color="#cd5b32">Select Plan</v-btn>
                    <v-btn
                    depressed
                    color="primary"
                    >
                    Primary
                    </v-btn>
               
               </v-card>
            </v-container>
        </div>
    </v-app>
</template>

<script>

export default {
    data() {
        return {
            value : 100
        }
    },
    mounted() {
    },

    methods: {
    },
}
</script>

<style scoped>
.card-section {
    width: 26%;
    border-radius: 2%;
}
.plan-title-header {
    background-color: #cd5b32;
    color: white;
}
.plan-details {
    padding: 4% 2% 4% 6%;
    margin: 2% 2% 8% 2%;
    outline: #cccaca solid 1px;
}
.plan-title {
    padding-top: 8%;
    padding-left: 8%;
}
.plan-text {
    color: #505050;
    display: block;
    font-size: 14px;
    font-family: sans-serif;
    line-height: 24px;
    text-rendering: optimizeLegibility;
    text-transform: none;
    -webkit-font-smoothing: antialiased;
}
.plan-content {
    color: #505050;
    font-size: 14px;
    font-family: sans-serif;
    margin-top: 0%;
    padding-top: 0%;

}
hr.orange-line {
      border-top: 4px solid red;
      width: 20%;
      margin-bottom: 4%;
}
.orangeColor{
    color: #cd5b32;
}
.selectButton {
    color: white;
    font-weight: bold;
    font-size: 16px;
    width: 80%;
}

.plan-details .v-expansion-panel::before {
    box-shadow: none !important;
}
.plan-details .v-expansion-panel-header {
    padding-left: 6px;
}

</style>
