<template>
    <div>
        <div
            class="header d-flex justify-space-between align-center"
>
                <div class=" text-h5 font-weight-bold">Sumo Plan - ASSURE</div>
                <div @click="closeDialog">
                    <v-icon aria-hidden="false" color="white">
                        mdi-close
                    </v-icon>    
                    close
                </div>
        </div>
        
        <div class="d-flex justify-space-between px-4 py-6">
            <div class="flex-basis-33">
                <div class="px-2"> 
                    <p class="titleFontSize font-weight-bold primaryFontColor">SUMO ASSURE</p>
                    <p class="textFontSize font-weight-bold">Features:</p>
                    <p class="textFontSize">Missing input put here</p>
                </div>
            </div>
            <div class="flex-basis-33">
                <div class="border-left-right px-2">
                    <p class="textFontSize font-weight-bold"> <v-icon class="textFontSize" color="yellow">mdi-flash</v-icon> Electricity changes <span class="subTitleFontSize font-weight-regular">(incl. GST)</span></p>

                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Meter Type</div>
                        <div class="textFontSize">Single Rate</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Daily supply charge <span class="subTitleFontSize font-weight-regular" >(c/day)</span> </div>
                        <div class="textFontSize">77.00</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Peak Usage <span class="subTitleFontSize font-weight-regular" >(c/day)</span> </div>
                        <div class="textFontSize">77.00</div>
                    </div>

                    <div class="my-4">
                        <a href="#" class="textFontSize linkColor">Electricty Fact Sheet</a>
                    </div>
                    
                    <div class="border-bottom"></div>

                    <p class="textFontSize font-weight-bold mt-4"> <v-icon class="textFontSize pb-1"  color="error">mdi-fire</v-icon> Gas changes <span class="subTitleFontSize font-weight-regular">(incl. GST)</span></p>
                
                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Daily supply charge <span class="subTitleFontSize font-weight-regular" >(c/day)</span> </div>
                        <div class="textFontSize">77.00</div>
                    </div>

                    <p class="infoTextSize infoColor my-2">
                        Disclaimer:  Winter is 1 May to 31 October (inclusive).  Non-winter is 1 November to 30 April (inclusive).
                    </p>

                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Peak Usage <span class="subTitleFontSize font-weight-regular" >(c/MJ)</span> </div>
                        <div class="textFontSize">Winter</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="textFontSize">0-100 MJ <span class="subTitleFontSize font-weight-regular" >(per day)</span> </div>
                        <div class="textFontSize">2.09</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="textFontSize">0-100 MJ <span class="subTitleFontSize font-weight-regular" >(per day)</span> </div>
                        <div class="textFontSize">2.09</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="textFontSize">0-100 MJ <span class="subTitleFontSize font-weight-regular" >(per day)</span> </div>
                        <div class="textFontSize">2.09</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="textFontSize">0-100 MJ <span class="subTitleFontSize font-weight-regular" >(per day)</span> </div>
                        <div class="textFontSize">2.09</div>
                    </div>


                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Off-Peak Usage <span class="subTitleFontSize font-weight-regular" >(c/MJ)</span> </div>
                        <div class="textFontSize">Summar</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="textFontSize">0-100 MJ <span class="subTitleFontSize font-weight-regular" >(per day)</span> </div>
                        <div class="textFontSize">2.09</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="textFontSize">0-100 MJ <span class="subTitleFontSize font-weight-regular" >(per day)</span> </div>
                        <div class="textFontSize">2.09</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="textFontSize">0-100 MJ <span class="subTitleFontSize font-weight-regular" >(per day)</span> </div>
                        <div class="textFontSize">2.09</div>
                    </div>

                    <div class="my-4">
                        <a href="#" class="textFontSize linkColor">Electricty Fact Sheet</a>
                    </div>

                </div>


            </div>
            <div class="flex-basis-33 ">
                <div class=" px-2 "> 
                    <p class="text-center primaryFontColor largeFontSize font-weight-bold">23% less than current victorian default offer electricity price</p>
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
                                <span class="font-weight-bold" style="font-size: 32px;">90</span> 
                                <span class="subTitleFontSize">mo</span> 
                            </div>
                            <div class="d-flex justify-center">
                                <div class="subTitleFontSize">$1,074 yr</div>
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
                                <span class="font-weight-bold" style="font-size: 32px;"> 108 </span> 
                                <span class="subTitleFontSize">mo</span> 
                            </div>
                            <div class="d-flex justify-center">
                                <div class="subTitleFontSize">$1,074 yr</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex mt-4">
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
    export default {
        name: "SoleDetails",
        methods:{
            closeDialog(){
                this.$emit('soleDialog')
            }
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

.flex-basis-33{
    flex-basis: 33%;
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