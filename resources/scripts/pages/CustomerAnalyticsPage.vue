<template>
    <v-container>
        <AnalyticContainer/>
        <v-row style="margin-top: 20px">
            <v-col md="6" sm="12">
                <EmotionMeter/>
            </v-col>
            <v-col md="6" sm="12">
                <ActiveCustomer/>
            </v-col>
            <v-col sm="12">
                <ActiveCustomer/>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import CustomersTable from "@scripts/components/customer/analytics/CustomersTable";
import EmotionMeter from "@scripts/components/customer/analytics/EmotionMeter";
import ActiveCustomer from "@scripts/components/customer/analytics/ActiveCustomer";
import AnalyticContainer from "@scripts/components/customer/analytics/AnalyticContainer";

export default {
    name: "CustomerAnalyticsPage",
    components: {
        CustomersTable,
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
