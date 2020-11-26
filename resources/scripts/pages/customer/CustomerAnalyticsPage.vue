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
                        :user_value="!!dashboardSummary && dashboardSummary.customerSummary ? dashboardSummary.customerSummary.total_user : 0"
                        customer_title="Total Customers"
                        :customer_value="!!dashboardSummary && dashboardSummary.customerSummary ? dashboardSummary.customerSummary.total_customer : 0"
                    ></InfoCard>
                </div>
                <div  class="box custom-col-2">
                    <info-chart-card
                        :colors="['rgba(92, 34, 154, .9)', 'rgba(92, 34, 154, .5)']"
                        chartId="c-01"
                        title="New User"
                        :value="dashboardSummary.graphData.total_user"
                        :prevValue="dashboardSummary.graphData.total_previous_user"
                        :chartData="dashboardSummary.graphData.user_chart"
                    />
                </div>
                <div class="box custom-col-2">
                    <info-chart-card
                        :colors="['rgba(92, 34, 154, .9)', 'rgba(92, 34, 154, .5)']"
                        chartId="c-02"
                        title="New Customer"
                        :value="dashboardSummary.graphData.total_customer"
                        :prevValue="dashboardSummary.graphData.total_previous_user"
                        :chartData="dashboardSummary.graphData.customer_chart"
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
                        :chartData="fakeData"
                    />
                </div>
                <div class="custom-col-2">
                    <info-chart-card
                        :colors="['rgba(53, 99, 19, .9)', 'rgba(53, 99, 19, .5)']"
                        chartId="c-04"
                        title="Total Conversation"
                        value="4928"
                        :chartData="fakeData"
                    />
                </div>
                <div class="custom-col-2">
                    <info-chart-card
                        :colors="['rgba(159,106,38,0.9)', 'rgba(159,106,38, .5)']"
                        chartId="c-05"
                        title="Avg. Conv. Steps/User"
                        value="14"
                        :chartData="fakeData"
                    />
                </div>
                <div class="custom-col-2">
                    <info-chart-card
                        :colors="['rgba(159,106,38,0.9)', 'rgba(159,106,38, .5)']"
                        chartId="c-06"
                        title="Live Conversation Count"
                        value="13987"
                        :chartData="fakeData"
                    />
                </div>
                <div class="custom-col-2">
                    <info-chart-card
                        :colors="['rgba(159,106,38,0.9)', 'rgba(159,106,38, .5)']"
                        title="Average Time"
                        value="02.35 min"
                        chartId="c-07"
                        :chartData="fakeData"
                    />
                </div>
            </div>
<!--            <v-col md="6" sm="12">-->
<!--                <GenderChart/>-->
<!--            </v-col>-->
        <div class="customer-insight">
            <p class="box title primary--text font-weight-bold mb-0 mt-5 px-3">CONNECT ENERGY</p>
        </div>
        <v-row>
            <v-col sm="12" md="3">
                <EnergyUsageChart title="Energy Usage" :chartData="energyUsage" _id="energy" />
            </v-col>
            <v-col sm="12" md="3">
                <EnergyUsageChart title="Property Profile" :chartData="propertyProfile" _id="property"/>
            </v-col>
            <v-col sm="12" md="3">
                <EnergyUsageChart title="People live in household" :chartData="householdProfile" _id="household" />
            </v-col>
            <v-col sm="12" md="3">
                <UtilityUsageByCityGraph :cities="topDestinations" />
            </v-col>
        </v-row>
        <v-row>
            <v-col sm="12">
                <ActiveCustomer/>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import EnergyUsageChart from "@scripts/components/customer/analytics/EnergyUsageChart";
import EmotionMeter from "@scripts/components/customer/analytics/EmotionMeter";
import ActiveCustomer from "@scripts/components/customer/analytics/ActiveCustomer";
import AnalyticContainer from "@scripts/components/customer/analytics/AnalyticContainer";
import InfoCard from "@scripts/components/customer/analytics/InfoCard";
import DateRangePicker from "@scripts/components/customer/analytics/DateRangePicker"
import PageHeader from "@scripts/components/common/PageHeader"
import DateRange from "@scripts/models/DateRange";
import merge from "lodash-es/merge";
import isEmpty from "lodash-es/isEmpty";
import UtilityUsageByCityGraph from "@scripts/components/customer/analytics/UtilityUsageByCityGraph";
import InfoChartCard from "@scripts/components/customer/analytics/InfoChartCard";
import CustomerInfos from "@scripts/components/customer/analytics/CustomerInfos";
import ConversationInfos from "@scripts/components/customer/analytics/ConversationInfos";
import SentimentSummary from "@scripts/models/SentimentSummary";
import SentimentService from "@scripts/services/SentimentService";
import DashboardService from "@scripts/services/DashboardService";
import DayJS from 'dayjs';
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import AnalyticsService from "@scripts/services/AnalyticsService";

export default {
    name: "CustomerAnalyticsPage",
    components: {
        EmotionMeter,
        ActiveCustomer,
        AnalyticContainer,
        InfoCard,
        EnergyUsageChart,
        DateRangePicker,
        PageHeader,
        CustomerInfos,
        ConversationInfos,
        InfoChartCard,
        UtilityUsageByCityGraph
    },
    data() {
        return {
            sentimentSummary: new SentimentSummary(),
            dashboardSummary: null,
            utilitySummary: null,
            energyUsage: null,
            propertyProfile: null,
            householdProfile: null,
            topDestinations: null,
            citiesByUtilityUsages: [],
            dateRange: new DateRange(),
            loaded: false,
            intervalID: null,
            fakeData: {
                data: [90, 70, 62, 80, 50, 88, 80, 30, 40, 50],
                labels: ["0", "1", "2", '3', '4', '5', '6', '7', '8', '9']
            }
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
    methods: {
        async load(dateRange) {
            this.dashboardSummary = await DashboardService.getDashboardSummary(dateRange);
            merge(this.sentimentSummary, this.dashboardSummary.sentimentSummary);
            console.log('dashboardSummary',this.dashboardSummary)
            this.utilitySummary = await DashboardService.getUtilitySummary(this.dateRange); //REDUNDANT CODE
            this.energyUsage = await AnalyticsService.getEnergyUsageAnalytics({ ...this.dateRange });
            this.propertyProfile = await AnalyticsService.getPropertyProfileAnalytics({ ...this.dateRange });
            this.householdProfile = await AnalyticsService.getHouseholdProfileAnalytics({ ...this.dateRange });
            this.citiesByUtilityUsages = await DashboardService.getTopCitiesByUtilityUsages(this.dateRange); //REDUNDANT CODE
            this.topDestinations = await AnalyticsService.getTopDestinationAnalytics({ ...this.dateRange });
        },
        checkIfDateEndIsToday() {
            return this.dateRange.end === new DayJS().format(DATE_FORMAT.DB_DATE);
        },
        empty(item) {
            return isEmpty(item);
        }
    },
    async mounted() {

        //Set timeout to call Dashboard API every 5 minute if date_end is today
        this.intervalID = setInterval(() => this.checkIfDateEndIsToday() && this.load(this.dateRange), 300000);
        await this.load(this.dateRange);
        this.loaded = true;
    },
    beforeDestroy() {
        this.intervalID && clearInterval(this.intervalID);
    },
    watch: {
        dateRange: {
            handler: function (newVal) {
                this.load(newVal);
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
