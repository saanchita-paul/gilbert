<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" class="py-0">
                <SalesFilter @updateDate="updateDate"/>
            </v-col>
            <v-col cols="12" v-if="is_laod">
                <SalesSummary type='energy' :summaryData="chartData"/>
            </v-col>
            <div class="d-flex  justify-space-around" style="width: 100%;" v-if="is_laod">
                <ApplicationDashboardStatisticsEnergy title="Submissions to Retailer" type='submission' :chart-data="chartData.submitted"/>
                <ApplicationDashboardStatisticsEnergy title="Connected" type='conversion' :chart-data="chartData.converted"/>
                <ApplicationDashboardStatisticsEnergy title="Rejected" type='rejected' :chart-data="chartData.rejected"/>
            </div>
        </v-row>
    </v-container>
</template>

<script>
import SalesFilter from "@scripts/modules/sales/components/SalesFilter";
import SalesSummary from "@scripts/modules/sales/components/SalesSummary";
import SalesDashboardService from "@scripts/modules/sales/services/SalesDashboardService";
import SalesSummaryChart from "@scripts/modules/sales/components/SalesSummaryChart";
import ApplicationDashboardStatisticsEnergy from "@scripts/modules/sales/pages/ApplicationDashboardStatisticsEnergy";

export default {
    name: "SalesEnergyPage",
    components: {
        SalesSummaryChart,
        SalesFilter,
        SalesSummary,
        ApplicationDashboardStatisticsEnergy
    },
    data() {
        return {
            utilityDashboardData: null,
            is_laod: false,
            data: null,
            chartData: null
        }
    },


    methods: {
        async load(dateRange) {
            this.chartData = await SalesDashboardService.loadDashboardEnergyData(dateRange);
            this.is_laod = true;
        },

        updateDate(dateRange) {
            this.load(dateRange)
        }
    },

}
</script>

