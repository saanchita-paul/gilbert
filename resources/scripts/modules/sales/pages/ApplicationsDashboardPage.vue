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
                
                <!-- <div style="width: 30%; background: white;">
                    <p class="font-weight-bold pt-4 text-center" style="font-size: 24px;">
                        Submissions to Retailer
                    </p>
                    <div class="font-weight-bold text-center py-0 mb-n3" style="font-size: 14px; color: #7E8A8F;">
                        Conversion rate 27.5% <span><v-icon color="success">trending_up
                    </v-icon> </span>
                    </div>
                    <div class="font-weight-bold text-center py-0" style="font-size: 56px; color: #542E89;">
                        16,032
                    </div>
                     <div class="font-weight-bold text-center py-0 mb-2 mt-n2" style="font-size: 16px;">
                        Total converted Applications 
                    </div>

                    <div style="background-color: #7E8A8F; width: 40px; height: 1px; margin: auto; margin-bottom: 20px; margin-top: 20px;"></div>

                     <div class="font-weight-bold text-center py-0 mb-2 mt-n2" style="font-size: 18px;">
                        Retailer Segmentation 
                    </div>
                    <div class="d-flex">
                        <SalesSummaryChart  v-if="is_laod" :data="data"
                                       title="Gas"
                    >
                    </SalesSummaryChart>
                    <SalesSummaryChart  v-if="is_laod" :data="data"
                                       title="Gas"
                    >
                    </SalesSummaryChart>
                    </div>
                </div> -->

                <ApplicationDashboardStatistics/>

            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import SalesFilter from "@scripts/modules/sales/components/SalesFilter";
import SalesSummary from "@scripts/modules/sales/components/SalesSummary";
import SalesDashboardService from "@scripts/modules/sales/services/SalesDashboardService";
import SalesSummaryChart from "@scripts/modules/sales/components/SalesSummaryChart";
import ApplicationDashboardStatistics from "@scripts/modules/sales/pages/ApplicationDashboardStatistics";

export default {
    name: "ApplicationsDashboardPage",
    components: {
        SalesSummaryChart,
        SalesFilter,
        SalesSummary,
        ApplicationDashboardStatistics
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

