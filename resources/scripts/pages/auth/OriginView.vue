<template>
    <v-app>
        <div fluid>
            <v-container fluid>
               <v-card class="card-section">
                   <div class="plan-title">
                       <p> Origin Go </p>
                       <hr class="orange-line">
                       <p class="plan-text">Rates guaranteed for 12 months, simplified pricing, Everyday Rewards points</p>
                   </div>
                    <hr class="devider-hr">

                    <div class="plan-title">
                       <p class="font-weight-bold"> Electricity </p>
                       <div class="pb-2">
                           <p class="font-weight-bold orangeColor">13% off</p>
                           <p class="plan-content">the reference price</p>
                       </div>
                       <div class="pb-2">
                           <div class="d-flex">
                               <p class="font-weight-bold orangeColor">$1,212/year</p>
                                <v-icon aria-hidden="false" class="pl-2 pb-4">
                                    mdi-alert-circle
                                </v-icon>
                           </div>
                           <p class="plan-content">Estimated cost inc GST for an average household using 3900 kWh / yearly on a flat rate tariff in the Ausgrid network.</p>
                       </div>
                   </div>
                    <hr class="devider-hr">

                    <div class="plan-title">
                       <p class="font-weight-bold"> Natural gas </p>
                       <div class="pb-2">
                           <p class="orangeColor">Our 12 month fixed rates</p>
                       </div>
                       <div class="pb-2">
                           <div class="d-flex">
                               <p class="font-weight-bold orangeColor">$827/year</p>
                                <v-icon aria-hidden="false" class="pl-1 pb-4">
                                    mdi-alert-circle
                                </v-icon>
                           </div>
                           <p class="plan-content">Estimated cost inc GST for an average household using 3900 kWh / yearly on a flat rate tariff in the Ausgrid network.</p>
                       </div>
                   </div>
                    <hr class="devider-hr">

                    <div class="reward-card">
                        <v-row>
                            <v-col class="section-dialogs" cols="12">
                                <div class="p-6 d-flex">
                                    <p class="font-weight-bold sans-serif">Origin Everyday Rewards Plan</p>
                                    <img class="width-25" src="https://res.cloudinary.com/originenergy/image/upload/v1606344623/43faa35315f4347eb9962d54418ce63f924a0150.png">
                                </div>
                            </v-col>
                        </v-row>
                        <p class="reward-card-text">Add your Everyday Rewards number to get 10k bonus points if you get both electricity and natural gas – that’s up to $50 off a future shop at Woolworths, BWS, Big W and more. Plus, ongoing points, 25% GreenPower and 100% Green Gas.</p>
                        <div class="d-flex">
                            <p class="pl-2">More on Everyday Rewards</p>
                            <v-icon aria-hidden="false" class="pl-1 pb-4">
                                mdi-alert-circle
                            </v-icon>
                        </div>
                    </div>
                    <hr class="devider-hr">
                    
                    <v-expansion-panels>
                            <v-expansion-panel color="red">
                                 <v-expansion-panel-header class="font-weight-bold">
                                    See electricity prices
                                    <template v-slot:actions><v-icon color="orange">mdi-menu-down</v-icon></template> 
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    <p class="plan-text">Showing rates for postcode 2000. For more accurate rates, enter your address.</p>
                                    <div class="d-flex">
                                        <p class="font-weight-bold">Supply Charge</p>
                                        <v-icon aria-hidden="false" class="pl-1 pb-4">
                                            mdi-progress-question
                                        </v-icon>
                                    </div>

                                    <div class="d-flex">
                                        <p class="plan-text">Daily Supply Charge</p>
                                        <p class="pl-14 plan-text">76.99 c/day</p>
                                    </div>

                                </v-expansion-panel-content>
                            </v-expansion-panel>
                    </v-expansion-panels>
                    <!-- <hr class="devider-hr"> -->
                    
                    <v-expansion-panels>
                            <v-expansion-panel>
                                <v-expansion-panel-header>
                                    See natural gas prices
                                    <template v-slot:actions><v-icon color="orange">mdi-menu-down</v-icon></template> 
                                </v-expansion-panel-header>
                                <v-expansion-panel-content>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                </v-expansion-panel-content>
                            </v-expansion-panel>
                    </v-expansion-panels>
                    <!-- <hr> -->

               </v-card>
            </v-container>
        </div>
    </v-app>
</template>

<script>

export default {
    data() {
        return {
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
    width: 22%;
    border-radius: 2%;
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
hr.devider-hr {
    width: 86%;
    margin-left: 8%;
    margin-right: 5%;
    margin-top: 8%;
    border-top: 1px solid #dfdfdf;
}
.orangeColor{
    color: orangered;
}
img{
    height: 20%;
    width: 35%;
}
.reward-card{
    background-color: rgb(252, 233, 240);
    margin: 8% 8% 12% 8%;
    padding: 3% 3% 3% 3%;
}
.reward-card-text {
    padding-left: 4%;
    word-wrap: normal;
}

</style>
