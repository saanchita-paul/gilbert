<template>
    <div class="px-2 analytic-bg hood-gradiant" v-if="isLoaded">
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
                    :tooltip="utilityDashboardData.lead_overview.lead.tooltip"
                    title="Total Lead"
                    :value="utilityDashboardData.lead_overview.lead.value"
                >
                    <BarChart :data="utilityDashboardData.lead_overview.lead.chartData" chartId="totalLead"/>
                </LeadWidget>

<!--                Qualified-->
                <LeadWidget
                    :tooltip="utilityDashboardData.lead_overview.qualified.tooltip"
                    title="Qualified Lead"
                    :value="utilityDashboardData.lead_overview.qualified.value"
                >
                    <LineChart  :data="utilityDashboardData.lead_overview.qualified.chartData" chartId="qualifiedLead"/>
                </LeadWidget>

<!--                Energy connection-->
                <LeadWidget
                    :tooltip="utilityDashboardData.lead_overview.lead.tooltip"
                    title="Total Energy Connection"
                    :value="utilityDashboardData.lead_overview.lead.value"
                >
                    <BarChart :data="utilityDashboardData.lead_overview.lead.chartData"  chartId="totalLead3"/>
                </LeadWidget>

<!--                Conversation rate-->
                <LeadWidget
                    :tooltip="utilityDashboardData.lead_overview.conversion.tooltip"
                    title="Conversion Rate"
                    :value="utilityDashboardData.lead_overview.conversion.value"
                >
                    <LineChart :data="utilityDashboardData.lead_overview.conversion.chartData"  chartId="totalLead4"/>
                </LeadWidget>

<!--                Automation rate-->
                <LeadWidget
                    :tooltip="utilityDashboardData.lead_overview.automation.tooltip"
                    title="Full Automation Rate"
                    :value="utilityDashboardData.lead_overview.automation.value"
                >
                    <LineChart :data="utilityDashboardData.lead_overview.automation.chartData"  chartId="totalLead5"/>
                </LeadWidget>
            </div>

            <div class="lead-connection-section">
                <h3 class="section-title">How are my lead is going?</h3>
                <v-row>
                    <v-col md="7" sm="12" style="height: 466px;">
                        <ConnectionLeadWidget/>
                    </v-col>
                    <v-col md="5" sm="12">
                        <div style="height: 270px; width: 100%; background: lightgreen">
                            <SentimentWidget/>
                        </div>
                        <div style="width: 100%; margin-top: 12px; height: 150px;">
                            <LocationInsight />
                        </div>
                    </v-col>
                </v-row>
            </div>

            <v-row class="mt-4">
                <v-col md="8">
                    <h3 class="section-title">Connection Summary</h3>
                    <v-row>
                        <v-col md="4">
                            <SummaryWidget
                                title="Utility Type"
                                :data="connectionSummary.connection_plan.chart_data"
                            />
                        </v-col>
                        <v-col md="4">
                            <SummaryWidget
                                title="Connection Type"
                                :data="connectionSummary.connection_type.chart_data"
                            />
                        </v-col>
                        <v-col md="4">
                            <SummaryWidget
                                title="Tenancy Type"
                                :data="connectionSummary.tenancy_type.chart_data"
                            />
                        </v-col>
                    </v-row>
                </v-col>
                <v-col md="4">
                    <h3  class="section-title mb-3">Age Summary</h3>
                    <AgeWidget/>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import LeadWidget from "@scripts/components/widgets/LeadWidget";
import SummaryWidget from "@scripts/components/widgets/SummaryWidget";
import AgeWidget from "@scripts/components/widgets/AgeWidget";
import SentimentWidget from "@scripts/components/widgets/SentimentWidget";
import LocationInsight from "@scripts/components/widgets/LocationInsight";
import ConnectionLeadWidget from "@scripts/components/widgets/ConnectionLeadWidget";
import BarChart from "@scripts/components/charts/BarChart";
import LineChart from "@scripts/components/charts/LineChart";
import COLOR from "@scripts/data/constants/COLOR";
import UtilityDashboardService from "@scripts/services/UtilityDashboardService";
import {ConnectionManager} from "pusher-js";
import ConnectionSummary from "@scripts/api/mappers/ConnectionSummary";
import LeadToConnectionMapper from "@scripts/api/mappers/LeadToConnectionMapper";

export default {
    name: "UtilityAnalyticPage",
    components: {
        ConnectionLeadWidget,
        LeadWidget,
        BarChart,
        LineChart,
        SummaryWidget,
        AgeWidget,
        LocationInsight,
        SentimentWidget
    },
    data() {
        return {
            isLoaded: null,
            utilityDashboardData: null,
            connectionSummary: null,
        }
    },
    mounted() {
        this.load()
    },
    methods:{
        async load() {
            this.utilityDashboardData = await UtilityDashboardService.getUtilityDashboardData();
            this.connectionSummary = await UtilityDashboardService.getConnectionSummary();
            this.isLoaded = true;
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
.lead-connection-section {
    margin-top: 50px;
}
.widgets {
    display: flex;
    flex-direction: row;
}
</style>
