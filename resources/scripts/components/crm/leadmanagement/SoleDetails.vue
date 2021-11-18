<template>
    <div>
        <div
            class="header d-flex justify-space-between align-center"
>
                <div class=" text-h5 font-weight-bold">Sumo Plan - {{sumoPlanDetails.elec_plan_name || sumoPlanDetails.gas_plan_name}}</div>
                <div @click="closeDialog">
                    <v-icon aria-hidden="false" color="white">
                        mdi-close
                    </v-icon>
                    close
                </div>
        </div>

        <div class="d-flex justify-space-between px-4 py-6">
            <div class="flex-basis-30">
                <div class="px-2">
                    <p class="titleFontSize font-weight-bold primaryFontColor">{{sumoPlanDetails.elec_plan_name || sumoPlanDetails.gas_plan_name}}</p>
                    <p class="textFontSize font-weight-bold">Features:</p>
                </div>
            </div>
            <div class="flex-basis-45">
                <div class="border-left-right px-2">
                    <template v-if="sumoPlanDetails.is_elec_available">
                        <p class="textFontSize font-weight-bold"> <v-icon class="textFontSize" color="yellow">mdi-flash</v-icon> Electricity charges <span class="subTitleFontSize font-weight-regular">(incl. GST)</span></p>

                        <div class="d-flex justify-space-between ">
                            <div class="font-weight-bold textFontSize">Meter Type</div>
                            <div class="textFontSize">{{sumoPlanDetails.elec_charge_name}}</div>
                        </div>

                        <div class="d-flex justify-space-between " v-for="(charge , i) in sumoPlanDetails.elec_charge_supply" :key="i">
                            <div class="font-weight-bold textFontSize">{{charge.name}} <span class="subTitleFontSize font-weight-regular" >{{ charge.unit }}</span> </div>
                            <div class="textFontSize">{{charge.incGST}}</div>
                        </div>

                        <div class="d-flex justify-space-between " v-for="(charge , i) in sumoPlanDetails.elec_charge_usage" :key="i+'s'">
                            <div class="font-weight-bold textFontSize">{{charge.name}} <span class="subTitleFontSize font-weight-regular" >{{ charge.unit }}</span> </div>
                            <div class="textFontSize">{{charge.incGST}}</div>
                        </div>

                        <!--                    <div class="d-flex justify-space-between ">-->
                        <!--                        <div class="font-weight-bold textFontSize">Peak Usage <span class="subTitleFontSize font-weight-regular" >(c/day)</span> </div>-->
                        <!--                        <div class="textFontSize">77.00</div>-->
                        <!--                    </div>-->

                        <div class="my-4">
                            <a href="#" class="textFontSize linkColor">Electricty Fact Sheet</a>
                        </div>
                    </template>



                    <div class="border-bottom"></div>
                    <template v-if="sumoPlanDetails.is_gas_available">
                        <p class="textFontSize font-weight-bold mt-4"> <v-icon class="textFontSize pb-1"  color="error">mdi-fire</v-icon> Gas charges <span class="subTitleFontSize font-weight-regular">(incl. GST)</span></p>

                        <div class="d-flex justify-space-between " v-for="(charge , i) in sumoPlanDetails.gas_charge_supply" :key="i+'p'">
                            <div class="font-weight-bold textFontSize">{{charge.name}} <span class="subTitleFontSize font-weight-regular" >{{ charge.unit }}</span> </div>
                            <div class="textFontSize">{{charge.incGST}}</div>
                        </div>

                        <p class="infoTextSize infoColor my-2">
                            {{ sumoPlanDetails.gas_disclaimer_text }}
                        </p>

                        <div v-for="(charge , i) in sumoPlanDetails.gas_charge_usage" :key="i+'k'" >

                            <div class="d-flex justify-space-between ">
                                <div class="font-weight-bold textFontSize">{{ charge.name }} <span class="subTitleFontSize font-weight-regular" >{{ charge.unit }}</span> </div>
                                <div class="textFontSize">{{ charge.period }}</div>
                            </div>

                            <div class="d-flex justify-space-between " v-for="(rate , i) in charge.rates"  :key="i+'ch'">
                                <div class="textFontSize">{{ rate.name }}
                                    <!-- <span class="subTitleFontSize font-weight-regular" >(per day)</span>  -->
                                </div>
                                <div class="textFontSize">{{ rate.incGST }}</div>
                            </div>

                        </div>

                        <div class="my-4">
                            <a href="#" class="textFontSize linkColor">Electricty Fact Sheet</a>
                        </div>
                    </template>



                </div>


            </div>
            <div class="flex-basis-35">
                <div class=" px-2 ">
                    <p class="text-center primaryFontColor largeFontSize font-weight-bold">{{sumoPlanDetails.elec_price_reference}}</p>
                </div>
                <div class=" px-2 ">
                    <p class="text-center textFontSize">
                        GST incl. Estimated Price based on a family using 4000kWh per year on a single rate tariff in the Jemena distribution area.
                    </p>
                </div>

                <div class="px-2">
                    <p class="text-center titleFontSize font-weight-bold">
                        Estimated Cost
                    </p>
                </div>

                <div class="d-flex">
                    <div class="flex-grow-1 d-flex justify-center border-right">
                        <div class="">
                            <div class="d-flex justify-center">
                                <v-icon class="pb-1" size="33"  color="yellow">mdi-flash</v-icon>
                            </div>
                            <div class="d-flex justify-center align-center">
                                <span class="subTitleFontSize">$</span>
                                <span class="font-weight-bold" style="font-size: 32px;">{{sumoPlanDetails.elec_monthly_cost}}</span>
                                <span class="subTitleFontSize">mo</span>
                            </div>
                            <div class="d-flex justify-center">
                                <div class="subTitleFontSize">${{sumoPlanDetails.elec_yearly_cost}} yr</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-grow-1 d-flex justify-center">
                        <div class="">
                            <div class="d-flex justify-center">
                                <v-icon class="pb-1" size="33"  color="error">mdi-fire</v-icon>
                            </div>
                            <div class="d-flex justify-center align-center">
                                <span class="subTitleFontSize">$</span>
                                <span class="font-weight-bold" style="font-size: 32px;"> {{sumoPlanDetails.gas_monthly_cost}} </span>
                                <span class="subTitleFontSize">mo</span>
                            </div>
                            <div class="d-flex justify-center">
                                <div class="subTitleFontSize">{{sumoPlanDetails.gas_yearly_cost}} yr</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex mt-4 ml-2">
                    <v-btn block color="#2989F4" class="white--text">
                        Select Plan
                        <v-icon dark>
                            mdi-arrow-right
                        </v-icon>
                    </v-btn>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
    import SumoPlanDetails from "@scripts/modules/sumo/models/SumoPlanDetails";

    export default {
        name: "SoleDetails",
        props:['sumoPlanDetails'],
        data() {
            return {
                planDetails: new SumoPlanDetails({})
            }
        },
        methods:{
            closeDialog(){
                this.$emit('soleDialog')
            }
        },
        mounted() {
        this.$eventBus.$on("address_updated", address => {
            console.log("EventBus: ", address)
        });
    }
    }
</script>

<style lang="scss" scoped>
$primaryColor : #2989F4;
$yellowColor  : #FFC107;
$linkColor : #03A9F4;
$textFontSize : 14px;
$titleFontSize : 18px;
$subTitleFontSize: 10px;
$linkTextSize : 12px;
$infoTextSize : 12px;
$infoColor: #9BA2A6;
$largeFontSize: 24px;
$errorColor: #FF5722;

.header{
    height: 88px;
    background-color: $primaryColor;
    color: white;
    padding: 23px;
}

.border-left-right{
    border-left: 1px solid #E0E0E0;
    border-right: 1px solid #E0E0E0;
    height: 100%;
}

.flex-basis-30{
    flex-basis: 30%;
}
.flex-basis-45{
    flex-basis: 43%;
}
.flex-basis-35{
    flex-basis: 35%;
}


.largeFontSize{
    font-size: $largeFontSize;
}

.border-bottom{
    border: 1px solid #E0E0E0;
    width: 90%;
    margin-left: 5%;
}

.errorColor{
    color: $errorColor;
}

.titleFontSize{
    font-size: 18px;
}

.primaryFontColor{
    color: $primaryColor;
}

.textFontSize{
    font-size: $textFontSize;
}

.subTitleFontSize{
    font-size: $subTitleFontSize;
}

.linkColor{
    color: $linkColor;
}

.infoColor{
    color: $infoColor;
}

.infoTextSize{
    font-size: $infoTextSize;
}

.border-right{
    border-right: 1px solid #E0E0E0;
}
</style>
