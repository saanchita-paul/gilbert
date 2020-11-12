<template>
    <v-container v-if="loaded">
        <PageHeader :breadcrumbs="getBreadcrumbs" title="Dashboard">
            <date-range-picker v-model="dateRange" />
        </PageHeader>
            <div class="customer-insight">
                <p class="box title primary--text font-weight-bold mb-0 mt-3 px-3">User Insights</p>
            </div>

            <div class="customer-insight custom-row">
                <div class="box custom-col-2">
                    <InfoCard
                        icon="mdi-clipboard-text-outline"
                        actionIcon="mdi-dots-vertical"
                        user_title="Total Users"
                        :user_value="dashboardSummary.customerSummary.total_user"
                        customer_title="Total Customers"
                        :customer_value="dashboardSummary.customerSummary.total_customer"
                    ></InfoCard>
                </div>
                <div  class="box custom-col-2">
                    <info-chart-card
                        :colors="['rgba(92, 34, 154, .9)', 'rgba(92, 34, 154, .5)']"
                        chartId="c-01"
                        title="New User"
                        value="14"
                    />
                </div>
                <div class="box custom-col-2">
                    <info-chart-card
                        :colors="['rgba(92, 34, 154, .9)', 'rgba(92, 34, 154, .5)']"
                        chartId="c-02"
                        title="New Customer"
                        value="4"
                    />
                </div>
                <div class="box custom-col-4">
                    <EmotionMeter :sentiment="sentimentSummary" />
                </div>
            </div>
            <div class="customer-insight">
                 <p class="box title primary--text font-weight-bold mb-0 mt-5 px-3">USER MESSAGES AND ENGAGEMENT</p>
            </div>
            <div class="custom-row">
                <div class="custom-col-2">
                    <info-chart-card
                        :colors="['rgba(92, 34, 154, .9)', 'rgba(92, 34, 154, .5)']"
                        chartId="c-03"
                        title="Total Messages"
                        value="7935"
                    />
                </div>
                <div class="custom-col-2">
                    <info-chart-card
                        :colors="['rgba(53, 99, 19, .9)', 'rgba(53, 99, 19, .5)']"
                        chartId="c-04"
                        title="Total Conversation"
                        value="4928"
                    />
                </div>
                <div class="custom-col-2">
                    <info-chart-card
                        :colors="['rgba(159,106,38,0.9)', 'rgba(159,106,38, .5)']"
                        chartId="c-05"
                        title="Avg. Conv. Steps/User"
                        value="14"
                    />
                </div>
                <div class="custom-col-2">
                    <info-chart-card
                        :colors="['rgba(159,106,38,0.9)', 'rgba(159,106,38, .5)']"
                        chartId="c-06"
                        title="Live Conversation Count"
                        value="13987"
                    />
                </div>
                <div class="custom-col-2">
                    <info-chart-card
                        :colors="['rgba(159,106,38,0.9)', 'rgba(159,106,38, .5)']"
                        title="Average Time"
                        value="02.35 min"
                        chartId="c-07"
                    />
                </div>
            </div>
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
            dateRange: new DateRange(),
            loaded: false,
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
        // const sentimentSummary = await SentimentService.getSentimentSummary(this.dateRange);
        this.dashboardSummary = await DashboardService.getDashboardSummary(this.dateRange);
        merge(this.sentimentSummary, this.dashboardSummary.sentimentSummary);
        this.loaded = true;
    },
    watch: {
        dateRange: {
            handler: async function (newVal) {
                this.dashboardSummary = await DashboardService.getDashboardSummary(newVal);
                merge(this.sentimentSummary, this.dashboardSummary.sentimentSummary);
            },
            deep: true
        },
    }
}
</script>

<style scoped>
.box {
}
</style>
