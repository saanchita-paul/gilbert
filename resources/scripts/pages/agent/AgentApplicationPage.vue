<template>
    <v-container>
        <v-row class="mt-0">
            <v-col cols="8" class="grey lighten-4 pa-5">
                <v-card class="pa-4">
                    <h3 class="page-title">Hi Dada,  <small class="font-weight-thin">heres a summary of your applications.</small></h3>

                    <AgentLeadMetrics></AgentLeadMetrics>
                </v-card>
                <AgentApplicationTable :applications="applicationList"></AgentApplicationTable>
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
import AuthService from "@scripts/services/AuthService";
import AgentApplicationService from "@scripts/services/crm/AgentApplicationService";
export default {
    name: "AgentApplicationPage",
    components: {
        AgentApplicationTable,
        AgentLeadMetrics,
        AgentApplicationSummary
    },
    data() {
        return {
            user: null,
            applicationMetrics: null,
            applicationList: null,
            applicationSummary: null,
        }
    },
    mounted() {
        this.getApplicationMetrics();
        this.getApplicationList();
        this.user = AuthService.getAuthUser();
        setInterval(AuthService.authUser, 300000);
    },
    methods: {
        getApplicationMetrics() {
            this.applicationMetrics = AgentApplicationService.getApplicationMetrics();
        },
        getApplicationList() {
            this.applicationList = AgentApplicationService.getApplicationList();
        }
    },
}
</script>
