<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" class="py-0s">
                <SalesFilter @updateDate="updateDate"/>
            </v-col>
            <div style="width: 100%;" class="mb-4" v-if="is_laod">
<!--                <SalesSummary type="energy"  :summaryData="chartData" :dateRange='dateRange'/>-->
                <EnergyApplicationSummary  :summaryData="chartData" :dateRange='dateRange'/>
            </div>
            <v-card class="summary-container" v-if="is_laod">
                <p class="summary-title-text">Summary of Energy Utilities</p>
                <div style="flex-basis: 30%;">
                    <ApplicationDashboardStatisticsEnergy title="Utility Submissions" type='submission' :chart-data="chartData.submitted"/>
                </div>
                <div style="flex-basis: 30%;">
                    <ApplicationDashboardStatisticsEnergy title="Connected" type='conversion' :chart-data="chartData.connected"/>
                </div>
                <div style="flex-basis: 30%;">
                    <ApplicationDashboardStatisticsEnergy title="Rejected" type='rejected' :chart-data="chartData.rejected"/>
                </div>
            </v-card>
        </v-row>
    </v-container>
</template>

<script>
import SalesFilter from "@scripts/modules/sales/components/SalesFilter";
import SalesSummary from "@scripts/modules/sales/components/SalesSummary";
import EnergyApplicationSummary from "@scripts/modules/sales/components/EnergyApplicationSummary";
import SalesDashboardService from "@scripts/modules/sales/services/SalesDashboardService";
import SalesSummaryChart from "@scripts/modules/sales/components/SalesSummaryChart";
import ApplicationDashboardStatisticsEnergy from "@scripts/modules/sales/pages/ApplicationDashboardStatisticsEnergy";

export default {
    name: "SalesEnergyPage",
    components: {
        SalesSummaryChart,
        SalesFilter,
        SalesSummary,
        EnergyApplicationSummary,
        ApplicationDashboardStatisticsEnergy
    },
    data() {
        return {
            utilityDashboardData: null,
            is_laod: false,
            data: null,
            chartData: null,
            dateRange: null
        }
    },


    methods: {
        async load(dateRange) {
            this.chartData = await SalesDashboardService.loadDashboardEnergyData(dateRange);
            console.log("data" , this.chartData)
            this.is_laod = true;
        },

        updateDate(dateRange) {
            this.dateRange = dateRange;
            this.load(dateRange)
        }
    },

}
</script>

<style scoped lang="scss">
.summary-container{
    display: flex;
    justify-content: space-around;
    width: 100%;
    flex-wrap: wrap;
    padding: 24px;
}

.title-style{
    font-family: Roboto;
    font-size: 32px;
    font-style: normal;
    font-weight: 700;
    line-height: 42px;
    letter-spacing: 0em;
    text-align: left;
}

.summary-title-text{
    flex-basis: 100%;
    @extend .title-style; 
}
</style>

