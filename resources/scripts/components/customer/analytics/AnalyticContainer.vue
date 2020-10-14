<template>
    <v-card class="pa-4 teal lighten-1  white--text">
        <v-row>
            <v-col sm="8">
                <h2>Customer Insight</h2>
            </v-col>
            <v-col sm="4" class="menu-color py-0">
                <date-range-picker/>
            </v-col>
            <v-col md="12">
                <v-row class="customer-insight">
                    <v-col lg="3" md="6" sm="12" class="box">
                       <InfoCard icon="mdi-account-outline" title="New Users" value="37"></InfoCard>
                    </v-col>
                    <v-col lg="3" md="6" sm="12" class="box">
                        <InfoCard icon="mdi-email-send" title="Message Sent" value="1281"></InfoCard>
                    </v-col>
                    <v-col lg="3" md="6" sm="12" class="box">
                        <InfoCard icon="mdi-email-receive-outline" title="Message Received" value="1894"></InfoCard>
                    </v-col>
                    <v-col md="3" class="box">
                        <!-- <div class="row">
                            <v-col sm="8">
                                <img src="/assets/images/GenderChart.jpg">
                            </v-col>
                            <v-col sm="4">

                            </v-col>
                        </div> -->
                        <GenderChart/>
                    </v-col>
                </v-row>
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
