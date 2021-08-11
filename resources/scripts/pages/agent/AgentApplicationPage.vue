<template>
    <v-container>
        <v-row class="mt-0">
            <v-col cols="8">
                <v-card class="pa-4">
                    <h3 v-if="user" class="page-title">Hi {{user.name}}, <small class="font-weight-thin">heres a summary of your applications.</small></h3>
                    <AgentLeadMetrics></AgentLeadMetrics>
                </v-card>
                <AgentApplicationTable :applications="applicationList" @openApplicationSummary="openApplicationSummary"></AgentApplicationTable>
            </v-col>
            <v-col cols="4">
                <AgentApplicationSummary :application="applicationSummary"></AgentApplicationSummary>
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
            selected_application_id: null,
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
            this.selected_application_id = this.applicationList[0].id;
            this.getApplicationSummary();
        },
        async getApplicationSummary() {
            this.applicationSummary = await AgentApplicationService.getApplicationSummary(this.selected_application_id);
            console.log('summary', this.selected_application_id, this.applicationSummary);
        },
        openApplicationSummary(id) {
            this.selected_application_id = id;
            this.getApplicationSummary();
        }
    },
}
</script>
