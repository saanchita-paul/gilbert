<template>
    <div>
        <PageHeader title="Customer Dashboard">
            <date-range-picker v-model="dateRange"/>
        </PageHeader>
        <div class="body-bg pa-4">
            <v-row>
                <v-col md="12">
                    <v-row class="customer-insight">
                        <v-col lg="4" md="6" sm="12" class="box">
                            <InfoCard
                                icon="mdi-account"
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
                                icon="mdi-email-receive"
                                title="Message Received"
                                :value="analytic.messages_received"
                            ></InfoCard>
                        </v-col>
                    </v-row>
                </v-col>

            </v-row>
        </div>
    </div>

</template>

<script>
import InfoCard from "@scripts/components/customer/analytics/InfoCard";
import GenderChart from "@scripts/components/customer/analytics/GenderChart";
import DateRangePicker from "@scripts/components/customer/analytics/DateRangePicker"
import PageHeader from "@scripts/components/common/PageHeader"
import CustomerAnalytics from "@scripts/models/CustomerAnalytics";
import CustomerService from "@scripts/services/CustomerService";
import DateRange from "@scripts/models/DateRange";
import merge from "lodash-es/merge";

export default {
    name: "AnalyticContainer",
    components: {
        InfoCard,
        GenderChart,
        DateRangePicker,
        PageHeader
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
