<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" class="py-0">
                <SalesFilter @updateDate="updateDate"/>
            </v-col>
            <v-col cols="12" v-if="is_laod">
                <SalesSummary type='water' :summaryData="chartData"/>
            </v-col>
            <div class="d-flex  justify-space-around" style="width: 100%;" v-if="is_laod">
                <ApplicationDashboardStatisticsWater title="Submissions to Retailer" type='submission' :chart-data="chartData.submitted"/>
                <ApplicationDashboardStatisticsWater title="Conversions" type='conversion' :chart-data="chartData.converted"/>
                <ApplicationDashboardStatisticsWater title="Rejected" type='rejected' :chart-data="chartData.rejected"/>
            </div>
        </v-row>
    </v-container>
</template>

<script>
import SalesFilter from "@scripts/modules/sales/components/SalesFilter";
import SalesSummary from "@scripts/modules/sales/components/SalesSummary";
import SalesDashboardService from "@scripts/modules/sales/services/SalesDashboardService";
import SalesSummaryChart from "@scripts/modules/sales/components/SalesSummaryChart";
import ApplicationDashboardStatisticsWater from "@scripts/modules/sales/pages/ApplicationDashboardStatisticsWater";

export default {
    name: "SalesWaterPage",
    components: {
        SalesSummaryChart,
        SalesFilter,
        SalesSummary,
        ApplicationDashboardStatisticsWater
    },
    data() {
        return{
            utilityDashboardData: null,
            is_laod: false,
            data: null,
            chartData: null
        }
    },

    methods : {
       async load(dateRange) {
            this.chartData = await SalesDashboardService.loadDashboardWaterData(dateRange);
            this.is_laod = true;
        },

        updateDate(dateRange) {
            this.load(dateRange)
        }
    }
}
</script>

