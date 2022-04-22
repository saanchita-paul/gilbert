<template>
    <v-card class="hood-card">
        <div class="d-flex ml-2 mb-4 font-weight-bold titleFontSize">
            <div>
                Connection Details
            </div>
        </div>
        <div class="d-flex ml-6 mt-4 font-weight-bold subtitleFontSize">
            <div>
                Water Application Update
            </div>
        </div>
        <div class="d-flex ml-6 regularFontSize">
            <div :class="{ errorColor: isError }">{{ connectionStatusReason }}</div>
        </div>
    </v-card>
</template>

<script>
export default {
    name: "WaterService",
    props:['connection_id', 'leadSummary'],
    data() {
        return {
            isError: false
        }
    },
    methods:{
        submit(){
            // * this will ber fired on ApplicationDetailsPage
            this.$eventBus.$emit("busUtilitySubmit", 'water')
        }
    },
    computed:{
        connectionStatusReason(){
            let waterService = this.leadSummary.connection_services.find(n=>n.service_type=='water');

            if(waterService && waterService.reasons && waterService.reasons.length>0){
                this.isError = true;
                return waterService.reasons[0].reason_text;
            }

            return waterService &&
                   waterService.reason !== null &&
                   waterService.reason !== undefined && 
                   waterService.reason !== "" ?
                   waterService.reason : 
                   this.leadSummary.is_auto_water_submit  &&
                   this.leadSummary.fast_connect_customer_reference !== null ? 
                   "Your application has been submitted automatically. Please wait while we process." :
                   "We are processing your application..." 
                   
        }
    }
}

</script>

<style lang="scss" scoped>
    $titleFontSize: 18px;
    $subtitleFontSize: 16px;
    $regularFontSize: 16px;
    $errorColor : #E91E63;
    $normalColor: black;
    $successColor: #16A948;
    $buttonBackgroundColor : #542E89;

    .titleFontSize{
        font-size: $titleFontSize;
    }
    .regularFontSize{
        font-size: $regularFontSize;
    }
    .subtitleFontSize{
        font-size: $subtitleFontSize;
    }
    .errorColor{
        color: $errorColor;
    }
    .successColor{
        color: $successColor;
    }
    .buttonBackgroundColor{
        background-color: $buttonBackgroundColor;
    }
</style> 


