<template>
    <div class="analytic-bg hood-gradiant">
        <v-container>
            <div class="my-lead-head my-3">
                <h3 class="section-title white--text">My leads overview</h3>
                <div>
                    <v-btn>filter
                        <v-icon>mdi-filter-outline</v-icon>
                    </v-btn>
                </div>
            </div>
            <div class="widgets">
<!--                Total-->
                <LeadWidget>
                    <BarChart :data="leadsData.lead.chartData" chartId="totalLead"/>
                </LeadWidget>

<!--                Qualified-->
                <LeadWidget>
                    <LineChart  :data="leadsData.qualified.chartData" chartId="qualifiedLead"/>
                </LeadWidget>

<!--                Energy connection-->
                <LeadWidget>
                    <BarChart :data="leadsData.lead.chartData"  chartId="totalLead3"/>
                </LeadWidget>

<!--                Conversation rate-->
                <LeadWidget>
                    <LineChart :data="leadsData.conversion.chartData"  chartId="totalLead4"/>
                </LeadWidget>

<!--                Automation rate-->
                <LeadWidget :tooltip="true">
                    <LineChart :data="leadsData.automation.chartData"  chartId="totalLead5"/>
                </LeadWidget>
            </div>
        </v-container>
    </div>
</template>

<script>
import LeadWidget from "@scripts/components/widgets/LeadWidget";
import BarChart from "@scripts/components/charts/BarChart";
import LineChart from "@scripts/components/charts/LineChart";
import COLOR from "@scripts/data/constants/COLOR";

export default {
    name: "UtilityAnalyticPage",
    components: { LeadWidget, BarChart, LineChart },
    data() {
        return {
            leadsData: {
                lead: {
                    chartData: {
                        labels: ['green', 'blue', 'red', 'orange', 'yellow', 'gray', 'black'],
                        datasets: [{
                            borderWidth: 1,
                            data: [12, 24, 30, 18, 24, 36, 24],
                            fill: false,
                            maxBarThickness: 13,
                            backgroundColor: COLOR.themes.light.secondary,
                        }]
                    }},
                qualified: {
                    chartData: {
                        labels: ['green', 'blue', 'red', 'orange', 'yellow', 'gray', 'black'],
                        datasets: [{
                            borderWidth: 1,
                            data: [12, 24, 30, 18, 24, 36, 24],
                            fill: false,
                            backgroundColor: COLOR.themes.light.secondary
                        }]
                    },
                },
                conversion: {
                    chartData: {
                        labels: ['green', 'blue', 'red', 'orange', 'yellow', 'gray', 'black'],
                        datasets: [
                            {
                                borderWidth: 0,
                                data: [12, 24, 45, 18, 24, 36, 24],
                                fill: true,
                                backgroundColor: COLOR.themes.light.primary + '80'
                            },
                            {
                                borderWidth: 0,
                                data: [32, 17, 15, 18, 22, 10, 12],
                                fill: true,
                                backgroundColor: COLOR.themes.light.secondary + '80'
                            },
                        ]
                    },
                },
                automation: {
                    chartData: {
                        labels: ['green', 'blue', 'red', 'orange', 'yellow', 'gray', 'black'],
                        datasets: [
                            {
                                borderWidth: 1,
                                data: [24, 20, 15, 25, 30, 20, 40],
                                fill: false,
                                backgroundColor: COLOR.themes.light.primary
                            },
                            {
                                borderWidth: 1,
                                data: [12, 24, 30, 18, 24, 36, 24],
                                fill: false,
                                backgroundColor: COLOR.themes.light.secondary
                            }
                        ]
                    },
                },
            }
        }
    },
    methods:{
    }
}
</script>

<style scoped lang="scss">
.my-lead-head {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}

.widgets {
    display: flex;
    flex-direction: row;
}
</style>
