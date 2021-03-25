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

            <div class="lead-connection-section">
                <h3 class="section-title">How are my lead is going?</h3>
                <v-row>
                    <v-col md="7" sm="12" style="height: 466px;">
                        <ConnectionLeadWidget/>
                    </v-col>
                    <v-col md="5" sm="12"></v-col>
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
        AgeWidget
    },
    data() {
        return {
            isLoaded: null,
            leadsData: null,
            connectionSummary: null,
        }
    },
    mounted() {
        Math.round()
        this.load()
    },
    methods:{
        async load() {
             let response = ConnectionSummary.getSummaryData({"leadOverview":{"total_lead":{"total":8,"charts":[{"total":2,"date":"2021-03-21"}]},"qualified_lead":{"total":10,"charts":[{"total":2,"date":"2021-03-21"}]},"total_energy_connection":{"total":2,"charts":[{"total":1,"date":"2021-03-20"},{"total":1,"date":"2021-03-21"}]},"conversion_rate":{"total":25,"charts":{"2021-03-21":{"date":"2021-03-21","total_lead":2,"total_conversion":1}},"qualified_lead":125,"total_conversion":20},"automation_rate":{"total":745,"charts":[{"date":"","full_automation":90,"manual_intervention":10}],"full_automation":10,"manual_intervention":90}},"leadSentiment":[{"total":10,"sentiment_text":"NEGATIVE"},{"total":43,"sentiment_text":"NEUTRAL"},{"total":47,"sentiment_text":"POSITIVE"}],"leadToConnection":{"lead_by_channel":8,"service_connection":0,"connection_type":0,"connection_submitted":11,"connection_accepted":2},"locationInsight":[{"total":3,"state":"ACT"},{"total":2,"state":"NSW"},{"total":2,"state":"SA"},{"total":3,"state":"VIC"}],"connectionSummary":{ "plan":[{"total":10,"utility_plan":null},{"total":1,"utility_plan":"Total Plan (Home)"}],"connectionType":[{"total":9,"which_utility":"electricity_and_gas"},{"total":2,"which_utility":"gas"}],"tenancyType":[{"total":11,"rent":"1"}],"ageGroupSummary":{"18-24": 3,"25-34": 4,"35-44":4,"45-54":0,"45-64":0,"65+":0}}});
             let response1 = LeadToConnectionMapper.getSummaryData({"leadOverview":{"total_lead":{"total":8,"charts":[{"total":2,"date":"2021-03-21"}]},"qualified_lead":{"total":10,"charts":[{"total":2,"date":"2021-03-21"}]},"total_energy_connection":{"total":2,"charts":[{"total":1,"date":"2021-03-20"},{"total":1,"date":"2021-03-21"}]},"conversion_rate":{"total":25,"charts":{"2021-03-21":{"date":"2021-03-21","total_lead":2,"total_conversion":1}},"qualified_lead":125,"total_conversion":20},"automation_rate":{"total":745,"charts":[{"date":"","full_automation":90,"manual_intervention":10}],"full_automation":10,"manual_intervention":90}},"leadSentiment":[{"total":10,"sentiment_text":"NEGATIVE"},{"total":43,"sentiment_text":"NEUTRAL"},{"total":47,"sentiment_text":"POSITIVE"}],"leadToConnection":{"lead_by_channel":8,"service_connection":0,"connection_type":0,"connection_submitted":11,"connection_accepted":2},"locationInsight":[{"total":3,"state":"ACT"},{"total":2,"state":"NSW"},{"total":2,"state":"SA"},{"total":3,"state":"VIC"}],"connectionSummary":{"plan":[{"total":10,"utility_plan":null},{"total":1,"utility_plan":"Total Plan (Home)"}],"connectionType":[{"total":9,"which_utility":"electricity_and_gas"},{"total":2,"which_utility":"gas"}],"tenancyType":[{"total":11,"rent":"1"}]}});
             console.log(response, " ", response1 );
            this.leadsData = await UtilityDashboardService.getLeadAnalytics();
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
