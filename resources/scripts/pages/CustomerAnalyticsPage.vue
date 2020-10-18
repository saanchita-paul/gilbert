<template>
    <v-container>
        <AnalyticContainer/>
        <v-row style="margin-top: 20px">
            <v-col md="6" sm="12">
                <EmotionMeter/>
            </v-col>
            <v-col md="6" sm="12">
                <GenderChart/>
            </v-col>
            <v-col sm="12">
                <ActiveCustomer/>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import GenderChart from "@scripts/components/customer/analytics/GenderChart";
import EmotionMeter from "@scripts/components/customer/analytics/EmotionMeter";
import ActiveCustomer from "@scripts/components/customer/analytics/ActiveCustomer";
import AnalyticContainer from "@scripts/components/customer/analytics/AnalyticContainer";

export default {
    name: "CustomerAnalyticsPage",
    components: {
        GenderChart,
        EmotionMeter,
        ActiveCustomer,
        AnalyticContainer
    },

}
</script>

<style scoped>
.box {
}
</style>
