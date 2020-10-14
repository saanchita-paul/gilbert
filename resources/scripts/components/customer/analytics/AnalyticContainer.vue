<template>
    <v-card class="pa-4 purple darken-2 white--text">
        <v-row>
            <v-col sm="8">
                <h2>Customer Insight</h2>
            </v-col>
            <v-col sm="4" class="white--text">
                <date-range-picker/>
            </v-col>
            <v-col md="8">
                <v-row>
                    <v-col lg="4" md="6" sm="12" class="box">
                        <InfoCard icon="mdi-account-outline" title="New Users" value="37"></InfoCard>
                    </v-col>
                    <v-col lg="4" md="6" sm="12" class="box">
                        <InfoCard icon="mdi-email-send" title="Message Sent" value="1281"></InfoCard>
                    </v-col>
                    <v-col lg="4" md="6" sm="12" class="box">
                        <InfoCard icon="mdi-email-receive-outline" title="Message Received" value="1894"></InfoCard>
                    </v-col>
                </v-row>
            </v-col>
            <v-col md="4">
                <GenderChart/>
            </v-col>
        </v-row>
    </v-card>

</template>

<script>
import InfoCard from "@scripts/components/customer/analytics/InfoCard";
import GenderChart from "@scripts/components/customer/analytics/GenderChart";
import DateRangePicker from "@scripts/components/customer/analytics/DateRangePicker"

export default {
    name: "AnalyticContainer",
    components: {
        InfoCard,
        GenderChart,
        DateRangePicker
    },
}
</script>

<style scoped>

</style>
