<template>
    <div class="px-2 analytic-bg hood-gradiant" v-if="leadsData">
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
                <LeadWidget
                    :tooltip="leadsData.lead.tooltip"
                    title="Total Lead"
                    :value="leadsData.lead.value"
                >
                    <BarChart :data="leadsData.lead.chartData" chartId="totalLead"/>
                </LeadWidget>

<!--                Qualified-->
                <LeadWidget
                    :tooltip="leadsData.qualified.tooltip"
                    title="Qualified Lead"
                    :value="leadsData.qualified.value"
                >
                    <LineChart  :data="leadsData.qualified.chartData" chartId="qualifiedLead"/>
                </LeadWidget>

<!--                Energy connection-->
                <LeadWidget
                    :tooltip="leadsData.lead.tooltip"
                    title="Total Energy Connection"
                    :value="leadsData.lead.value"
                >
                    <BarChart :data="leadsData.lead.chartData"  chartId="totalLead3"/>
                </LeadWidget>

<!--                Conversation rate-->
                <LeadWidget
                    :tooltip="leadsData.conversion.tooltip"
                    title="Conversion Rate"
                    :value="leadsData.conversion.value"
                >
                    <LineChart :data="leadsData.conversion.chartData"  chartId="totalLead4"/>
                </LeadWidget>

<!--                Automation rate-->
                <LeadWidget
                    :tooltip="leadsData.automation.tooltip"
                    title="Full Automation Rate"
                    :value="leadsData.automation.value"
                >
                    <LineChart :data="leadsData.automation.chartData"  chartId="totalLead5"/>
                </LeadWidget>
            </div>

            <div style="margin-top: 50px;">
                <h3 class="section-title">My leads overview</h3>
                <v-row>
                    <v-col md="7" sm="12" style="height: 466px;">
                        <ConnectionLeadWidget/>
                    </v-col>
                    <v-col md="5" sm="12"></v-col>
                </v-row>
            </div>
        </v-container>
    </div>
</template>

<script>
import LeadWidget from "@scripts/components/widgets/LeadWidget";
import ConnectionLeadWidget from "@scripts/components/widgets/ConnectionLeadWidget";
import BarChart from "@scripts/components/charts/BarChart";
import LineChart from "@scripts/components/charts/LineChart";
import COLOR from "@scripts/data/constants/COLOR";
import UtilityDashboardService from "@scripts/services/UtilityDashboardService";

export default {
    name: "UtilityAnalyticPage",
    components: {ConnectionLeadWidget, LeadWidget, BarChart, LineChart },
    data() {
        return {
            leadsData: null
        }
    },
    mounted() {
        this.load()
    },
    methods:{
        async load() {
            this.leadsData = await UtilityDashboardService.getLeadAnalytics()
        }
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
