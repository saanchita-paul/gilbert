<template>
    <v-container v-if="dashboardSummary">
        <PageHeader :breadcrumbs="getBreadcrumbs" title="Customer insight">
            <date-range-picker v-model="dateRange"/>
        </PageHeader>
            <v-row class="customer-insight">
                <v-col md="3" sm="12" class="box">
                    <InfoCard
                        icon="mdi-account-group"
                        title="Total customer"
                        :value="dashboardSummary.customerSummary.total_customer"
                    ></InfoCard>
                </v-col>
                <v-col md="3" sm="12" class="box">
                    <InfoCard
                        icon="mdi-account-plus"
                        title="New customer"
                        :value="dashboardSummary.customerSummary.new_customer"
                    ></InfoCard>
                </v-col>
                <v-col md="3" sm="12" class="box">
                    <InfoCard
                        icon="mdi-account-check"
                        title="Active customer"
                        :value="dashboardSummary.customerSummary.active_customer"
                    ></InfoCard>
                </v-col>
                <v-col md="3" sm="12" class="box">
                    <InfoCard
                        icon="mdi-account-star"
                        title="Engaged customer"
                        :value="dashboardSummary.customerSummary.engaged_customer"
                    ></InfoCard>
                </v-col>
                <v-col sm="12">
                    <EmotionMeter/>
                </v-col>
            </v-row>
            <v-row>
                <v-col md="4">
                    <info-chart-card
                        :colors="['rgba(92, 34, 154, .9)', 'rgba(92, 34, 154, .5)']"
                        chartId="c-01"
                        title="Conversations"
                        :value="dashboardSummary.conversationSummary.total_message"
                        :chart="dashboardSummary.infoChart.total_message"
                    />
                </v-col>
                <v-col md="4">
                    <info-chart-card
                        :colors="['rgba(53, 99, 19, .9)', 'rgba(53, 99, 19, .5)']"
                        title="Sent"
                        :value="dashboardSummary.conversationSummary.message_sent"
                        chartId="c-02"
                        :chart="dashboardSummary.infoChart.message_sent"
                    />
                </v-col>
                <v-col md="4">
                    <info-chart-card
                        :colors="['rgba(159,106,38,0.9)', 'rgba(159,106,38, .5)']"
                        title="Received"
                        :value="dashboardSummary.conversationSummary.message_received"
                        chartId="c-03"
                        :chart="dashboardSummary.infoChart.message_received"
                    />
                </v-col>
            </v-row>
<!--            <v-col md="6" sm="12">-->
<!--                <GenderChart/>-->
<!--            </v-col>-->
        <v-row>
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
import DateRange from "@scripts/models/DateRange";
import merge from "lodash-es/merge";
import InfoChartCard from "@scripts/components/customer/analytics/InfoChartCard";
import CustomerInfos from "@scripts/components/customer/analytics/CustomerInfos";
import ConversationInfos from "@scripts/components/customer/analytics/ConversationInfos";
import SentimentSummary from "@scripts/models/SentimentSummary";
import SentimentService from "@scripts/services/SentimentService";
import DashboardService from "@scripts/services/DashboardService";


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
            sentimentSummary: new SentimentSummary(),
            dashboardSummary: null,
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
        const sentimentSummary = await SentimentService.getSentimentSummary(this.dateRange);
        merge(this.sentimentSummary, sentimentSummary);
        this.dashboardSummary = await DashboardService.getDashboardSummary(this.dateRange);
        console.log('Data', this.dashboardSummary);
    }

}
</script>

<style scoped>
.box {
}
</style>
