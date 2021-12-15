<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" class="py-0">
                <SalesFilter/>
            </v-col>
            <v-col cols="12">
                <SalesSummary type='energy'/>
            </v-col>
            <div class="d-flex  justify-space-around" style="width: 100%;" v-if="is_laod">
                <ApplicationDashboardStatisticsEnergy :chart-data="chartData.submitted"/>
                <ApplicationDashboardStatisticsEnergy :chart-data="chartData.converted"/>
                <ApplicationDashboardStatisticsEnergy :chart-data="chartData.rejected"/>
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

    mounted() {
        this.load();
    },

    methods: {
        async load() {
            this.chartData = await SalesDashboardService.loadDashboardEnergyData();
            console.log('chartData12', this.chartData);
            this.is_laod = true;
        }
    }
}
</script>

