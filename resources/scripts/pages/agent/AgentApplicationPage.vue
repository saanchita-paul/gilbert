<template>
    <v-container>
        <v-row class="mt-0">
            <v-col cols="8">
                <v-card class="pa-4">
                    <div>
                        <span class="user-details">Hi Dimuthusathrasinghe, </span>
                        <span>heres a summary of your applications.</span>
                    </div>
                    <AgentLeadMetrics></AgentLeadMetrics>
                </v-card>
                <AgentApplicationTable></AgentApplicationTable>
            </v-col>
            <v-col cols="4">
                <AgentApplicationSummary ></AgentApplicationSummary>
            </v-col>
        </v-row>

    </v-container>
</template>

<script>
import AgentLeadMetrics from "@scripts/components/crm/agent/AgentLeadMetrics";
import AgentApplicationTable from "@scripts/components/crm/agent/AgentApplicationTable";
import AgentApplicationSummary from "@scripts/components/crm/agent/AgentApplicationSummary";
export default {
    name: "AgentApplicationPage",
    components: {
        AgentApplicationTable,
        AgentLeadMetrics,
        AgentApplicationSummary
    },
}
</script>

<style scoped>
.user-details {
    font-size: 25px !important;
    font-Weight: 700 !important;
}

</style>
