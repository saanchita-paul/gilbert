<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" class="py-0">
                <SalesFilter/>
            </v-col>
            <v-col cols="12">
                <SalesSummary type='water' />
            </v-col>
            <div class="d-flex  justify-space-around" style="width: 100%;">
                <ApplicationDashboardStatisticsWater/>
                <ApplicationDashboardStatisticsWater/>
                <ApplicationDashboardStatisticsWater/>
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
        }
    },

    mounted() {
        this.load();
    },

    methods : {
        async load() {
            this.data  = {
                labels: [
                    'Red',
                    'Blue',
                    'Yellow'
                ],
                toolTips: [
                    [
                        {
                            a: 100, type: 'Ea',
                        },
                        {
                            a: 100, type: 'Ea',
                        }
                    ],
                    [
                        {
                            a: 100, type: 'Ea',
                        },
                        {
                            a: 100, type: 'Ea',
                        }
                    ],
                    [
                        {
                            a: 100, type: 'Ea',
                        },
                        {
                            a: 100, type: 'Ea',
                        }
                    ],
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [300, 50, 100],
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                }]
            };
            this.is_laod = true;
        }
    }
}
</script>

