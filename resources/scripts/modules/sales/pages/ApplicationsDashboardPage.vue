<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" class="py-0">
                <SalesFilter/>
            </v-col>
            <v-col cols="12">
                <SalesSummary/>
            </v-col>
            <v-col>

                <SalesSummaryChart  v-if="is_laod" :data="data"
                                   title="Gas"
                >
                </SalesSummaryChart>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import SalesFilter from "@scripts/modules/sales/components/SalesFilter";
import SalesSummary from "@scripts/modules/sales/components/SalesSummary";
import SalesDashboardService from "@scripts/modules/sales/services/SalesDashboardService";
import SalesSummaryChart from "@scripts/modules/sales/components/SalesSummaryChart";

export default {
    name: "ApplicationsDashboardPage",
    components: {
        SalesSummaryChart,
        SalesFilter,
        SalesSummary
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
                    'Energy Australia',
                    'Sumo',
                    'Yellow'
                ],
                toolTips: [
                    [
                        {
                            key: 100, value: 'Ea',
                        },
                        {
                            key: 100, value: 'Ea',
                        }
                    ],
                    [
                        {
                            key: 100, value: 'Ea',
                        },
                        {
                            key: 100, value: 'Ea',
                        }
                    ],
                    [
                        {
                            key: 100, value: 'Ea',
                        },
                        {
                            key: 100, value: 'Ea',
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

