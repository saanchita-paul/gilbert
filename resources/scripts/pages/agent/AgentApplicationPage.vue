<template>
    <v-container>
        <v-row class="mt-0">
            <v-col cols="8">
                <v-card class="pa-4">
                    <h3 v-if="user" class="page-title">Hi {{user.name}}, <small class="font-weight-thin">heres a summary of your applications.</small></h3>
                    <AgentLeadMetrics></AgentLeadMetrics>
                </v-card>
                <AgentApplicationTable v-if="isLoaded"
                    :applications="applicationList"
                    :totalItem="totalItem"
                    @refreshDataTable="refreshDataTable"
                    @openApplicationSummary="openApplicationSummary"></AgentApplicationTable>
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
            sort_search_meta : null,

            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalItem: null,
            options: {},
            isLoaded: false,
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
     async getApplicationList() {
            let data = await AgentApplicationService.getApplicationList(this.sort_search_meta);
            this.applicationList = data.applications;
            this.isLoaded = true;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
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
        },

        refreshDataTable(meta) {
            this.sort_search_meta = meta;
            console.log('meta', this.sort_search_meta);
            this.getApplicationList();
        }
    },
}
</script>
<style scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}
</style>
