<template>
    <v-container>
        <PageHeader :breadcrumbs="getBreadcrumbs" title="Customer insight">
            <date-range-picker v-model="dateRange"/>
        </PageHeader>
        <div>
            <v-row class="customer-insight">
                <v-col md="3" sm="12" class="box">
                    <InfoCard
                        icon="mdi-email-receive"
                        title="Total customer"
                        :value="'21,45'"
                    ></InfoCard>
                </v-col>
                <v-col md="3" sm="12" class="box">
                    <InfoCard
                        icon="mdi-email-receive"
                        title="New customer"
                        :value="'97'"
                    ></InfoCard>
                </v-col>
                <v-col md="3" sm="12" class="box">
                    <InfoCard
                        icon="mdi-email-receive"
                        title="Active customer"
                        :value="'1,253'"
                    ></InfoCard>
                </v-col>
                <v-col md="3" sm="12" class="box">
                    <InfoCard
                        icon="mdi-email-receive"
                        title="Engaged customer"
                        :value="'120'"
                    ></InfoCard>
                </v-col>
            </v-row>
            <v-row>
                <v-col md="4">
                    <info-chart-card chartId="c-01"/>
                </v-col>
                <v-col md="4">
                    <info-chart-card chartId="c-02"/>
                </v-col>
                <v-col md="4">
                    <info-chart-card chartId="c-03"/>
                </v-col>
            </v-row>
        </div>
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
import InfoCard from "@scripts/components/customer/analytics/InfoCard";
import DateRangePicker from "@scripts/components/customer/analytics/DateRangePicker"
import PageHeader from "@scripts/components/common/PageHeader"
import CustomerAnalytics from "@scripts/models/CustomerAnalytics";
import CustomerService from "@scripts/services/CustomerService";
import DateRange from "@scripts/models/DateRange";
import merge from "lodash-es/merge";
import InfoChartCard from "@scripts/components/customer/analytics/InfoChartCard";
import CustomerInfos from "@scripts/components/customer/analytics/CustomerInfos";
import ConversationInfos from "@scripts/components/customer/analytics/ConversationInfos";

export default {
    name: "CustomerAnalyticsPage",
    components: {
        EmotionMeter,
        ActiveCustomer,
        AnalyticContainer,
        InfoCard,
        GenderChart,
        DateRangePicker,
        PageHeader,
        CustomerInfos,
        ConversationInfos,
        InfoChartCard
    },
    data() {
        return {
            analytic: new CustomerAnalytics(),
            dateRange: new DateRange()
        }
    },
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Customer',
                    disabled: false,
                    route_name: 'dashboard',
                },
                // {
                //     text: 'Customer Dashboard',
                //     disabled: false,
                //     route_name: 'breadcrumbs_link_1',
                // },
            ]
        }
    },
    async mounted() {
        const analytics = await CustomerService.getCustomerAnalytics(this.dateRange)
        merge(this.analytic, analytics)
    }

}
</script>

<style scoped>
.box {
}
</style>
