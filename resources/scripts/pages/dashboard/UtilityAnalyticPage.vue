<template>
    <div class="analytic-bg hood-gradiant" v-if="leadsData">
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
        </v-container>
    </div>
</template>

<script>
import LeadWidget from "@scripts/components/widgets/LeadWidget";
import BarChart from "@scripts/components/charts/BarChart";
import LineChart from "@scripts/components/charts/LineChart";
import COLOR from "@scripts/data/constants/COLOR";
import UtilityDashboardService from "@scripts/services/UtilityDashboardService";

export default {
    name: "UtilityAnalyticPage",
    components: { LeadWidget, BarChart, LineChart },
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
