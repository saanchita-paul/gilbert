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
        
        <div class="d-flex justify-space-between px-4">
            <div class="flex-basis-33 ">
                <div class="px-2"> 
                    <p class="py-4 titleFontSize font-weight-bold primaryFontColor">SUMO ASSURE</p>
                    <p class="textFontSize font-weight-bold">Features:</p>
                    <p class="textFontSize">Missing input put here</p>
                </div>
            </div>
            <div class="flex-basis-33 ">
                <div class="border-left-right px-2">
                    <p class="textFontSize font-weight-bold py-4"> <v-icon class="textFontSize" color="yellow">mdi-flash</v-icon> Electricity changes <span class="subTitleFontSize font-weight-regular">(incl. GST)</span></p>

                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Meter Type</div>
                        <div class="textFontSize">Single Rate</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Daily supply charge <span class="subTitleFontSize" >(c/day)</span> </div>
                        <div class="textFontSize">77.00</div>
                    </div>

                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Peak Usage <span class="subTitleFontSize" >(c/day)</span> </div>
                        <div class="textFontSize">77.00</div>
                    </div>

                    <div class="my-4">
                        <a href="#" class="textFontSize linkColor">Electricty Fact Sheet</a>
                    </div>
                    
                    <div class="border-bottom"></div>

                    <p class="textFontSize font-weight-bold pt-4"> <v-icon class="textFontSize" color="yellow">mdi-flash</v-icon> Gas changes <span class="subTitleFontSize font-weight-regular">(incl. GST)</span></p>
                
                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Daily supply charge <span class="subTitleFontSize" >(c/day)</span> </div>
                        <div class="textFontSize">77.00</div>
                    </div>

                    <p class="infoTextSize infoColor my-2">
                        Disclaimer:  Winter is 1 May to 31 October (inclusive).  Non-winter is 1 November to 30 April (inclusive).
                    </p>

                    <div class="d-flex justify-space-between ">
                        <div class="font-weight-bold textFontSize">Meter Type</div>
                        <div class="textFontSize">Single Rate</div>
                    </div>

                </div>


            </div>
            <div class="flex-basis-33 ">
                <div class=" px-2 "> 
                    <p class="text-center">23% less than current victorian default offer electricity price</p>
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

.header{
    height: 88px;
    background-color: $primaryColor;
    color: white;
    padding: 23px;
}

.border-left-right{
    border-left: 1px solid gray;
    border-right: 1px solid gray;
    height: 100%;
}

.flex-basis-33{
    flex-basis: 33%;
}

.border-bottom{
    border: 1px solid gray;
    width: 90%;
    margin-left: 5%;
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
</style>