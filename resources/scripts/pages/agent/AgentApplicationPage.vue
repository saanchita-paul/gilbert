<template>
    <v-container fluid>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-card  class="hood-card">
                    <h3 v-if="user" class="page-title">Hi {{ user.profile.first_name }}, <small class="font-weight">here is a
                        summary of your applications.</small>
                    </h3>
                    <span v-if="!pm_connected" class="link-prop">
                        <v-btn @click="onLinkPropertyMe" small>🔗 Link PropertyMe</v-btn>
                    </span>
                    <span v-else class="link-prop">
                        <v-btn disabled x-small>🔗 PropertyMe is linked</v-btn>
                    </span>
                    <AgentLeadMetrics></AgentLeadMetrics>
                </v-card>
                <AgentApplicationTable v-if="isLoaded"
                                       :applications="applicationList"
                                       :totalItem="totalItem"
                                       :selectedAppId="selected_application_id"
                                       @refreshDataTable="refreshDataTable"
                                       @openApplicationSummary="openApplicationSummary"></AgentApplicationTable>
            </v-col>
            <!-- <v-col cols="4">
                <AgentApplicationSummary :application="applicationSummary"></AgentApplicationSummary>
            </v-col> -->
        </v-row>
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-container>
                    <v-row>
                        <v-col class="section-dialogs" cols="12">
                            <div class="dialogs-title d-flex justify-center">
                                <p>Linking PropertyMe account</p>
                            </div>
                            <div class="d-flex justify-center">
                                <p > PropertyMe account successfully linked!</p>
                            </div>
                            <div class="d-flex justify-center">
                                <v-btn @click="dismiss"
                                       color="primary"
                                >Done
                                </v-btn>
                            </div>
                        </v-col>

                    </v-row>
                </v-container>
            </v-card>
        </v-dialog>

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
            dialog: null,
            applicationMetrics: null,
            applicationList: null,
            applicationSummary: null,
            selected_application_id: null,
            sort_search_meta: null,

            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalItem: null,
            options: {},
            isLoaded: false,
        }
    },

    computed: {
        pm_connected() {
            return !! this.office?.property_me_refresh_token
        },
        office() {
            return AuthService.getUserOffice();
        }
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
            // console.log('summary', this.selected_application_id, this.applicationSummary);
        },
        openApplicationSummary(id) {
            this.selected_application_id = id;
            this.getApplicationSummary();
        },

        refreshDataTable(meta) {
            this.sort_search_meta = meta;
            this.getApplicationList();
        },
        onLinkPropertyMe() {
            window.location = `/property-me/authorize?office_id=${this.office?.id}`;
        },
        dismiss() {
            this.dialog = false;
            this.$router.push({name: this.$route.name, query: {}})
        }
    },

    mounted() {
        this.getApplicationMetrics();
        this.getApplicationList();
        if (this.$route.query.d === '1') {
            this.dialog = true;
        }
        this.user = AuthService.getAuthUser();
        setInterval(AuthService.authUser, 300000);
    },

}
</script>
<style scoped>
.row-pointer >>> tbody tr :hover {
    cursor: pointer;
}
.link-prop {
    float: right;
    margin-top: -10px
}

.intro-message {
    font-weight: 400 !important;
}
</style>
