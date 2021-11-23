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
            <div>
                This application have missing information. To proceed, please review above fields and manually submit the application by clicking the submit button below.
            </div>
        </div>
        <!-- <div class="d-flex justify-end mt-4">
            <div>
                <v-btn color="#542E89" @click="submit" class="white--text">
                    Submit for connection
                </v-btn>
            </div>
        </div> -->
    </v-card>
</template>

<script>
import DayJs from "dayjs";
import WaterService from "@scripts/services/crm/WaterService";
import {isNull} from "lodash-es";
import dayJs from "dayjs";

export default {
    name: "WaterService",
    props:['connection_id', 'leadSummary'],
    methods:{
        submit(){
            // * this will ber fired on ApplicationDetailsPage
            this.$eventBus.$emit("busWaterSubmit", 'water')
            console.log('clicking submit')
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


