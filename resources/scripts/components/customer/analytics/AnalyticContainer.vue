<template>
    <v-card elevation="0" class="body-bg pa-4 white--text">
        <v-row>
            <v-col sm="8">
                <h2 class="black--text title-big">Customer Dashboard</h2>
            </v-col>
            <v-col sm="4" class="menu-color py-0">
                <date-range-picker v-model="dateRange"/>
            </v-col>
            <v-col md="12">
                <v-row class="customer-insight">
                    <v-col lg="4" md="6" sm="12" class="box">
                        <InfoCard
                            icon="mdi-account-outline"
                            title="New Users"
                            :value="analytic.new_user_count"
                        ></InfoCard>
                    </v-col>
                    <v-col lg="4" md="6" sm="12" class="box">
                        <InfoCard
                            icon="mdi-email-send"
                            title="Message Sent"
                            :value="analytic.messages_sent"
                        ></InfoCard>
                    </v-col>
                    <v-col lg="4" md="6" sm="12" class="box">
                        <InfoCard
                            icon="mdi-email-receive-outline"
                            title="Message Received"
                            :value="analytic.messages_received"
                        ></InfoCard>
                    </v-col>
<!--                    <v-col md="3" class="box">-->
<!--                        &lt;!&ndash; <div class="row">-->
<!--                            <v-col sm="8">-->
<!--                                <img src="/assets/images/GenderChart.jpg">-->
<!--                            </v-col>-->
<!--                            <v-col sm="4">-->

<!--                            </v-col>-->
<!--                        </div> &ndash;&gt;-->
<!--                        <GenderChart/>-->
<!--                    </v-col>-->
                </v-row>
            </v-col>

        </v-row>
    </v-card>

</template>

<script>
import InfoCard from "@scripts/components/customer/analytics/InfoCard";
import GenderChart from "@scripts/components/customer/analytics/GenderChart";
import DateRangePicker from "@scripts/components/customer/analytics/DateRangePicker"
import CustomerAnalytics from "@scripts/models/CustomerAnalytics";
import CustomerService from "@scripts/services/CustomerService";
import DateRange from "@scripts/models/DateRange";
import merge from "lodash-es/merge";

export default {
    name: "AnalyticContainer",
    components: {
        InfoCard,
        GenderChart,
        DateRangePicker
    },
    data() {
        return {
            analytic: new CustomerAnalytics(),
            dateRange: new  DateRange()
        }
    },
    async mounted() {
        const analytics = await CustomerService.getCustomerAnalytics(this.dateRange)
        merge(this.analytic, analytics)
    }
}
</script>

<style scoped>

</style>
