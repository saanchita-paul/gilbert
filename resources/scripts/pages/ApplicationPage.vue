<template>
    <v-container>
        <v-row>
            <v-col cols="8">
                <v-card class="pa-4">
                    <p>Your Metrics</p>
                    <h3 class="page-title">Total Applications: {{total_leads}}</h3>
                    <AgentLeadMetrics v-if="leadTypesFlag" :activeLeadType="activeLeadType" :leads="leadTypes" @updateTotal="updateTotal"></AgentLeadMetrics>
                </v-card>
                <ApplicantTable
                    v-if="isLoaded"
                    :applications="leads"
                    :totalItem="totalItem"
                    :currentLead="leadDetails"
                    @refreshDataTable="refreshDataTable"
                    @openLeadSummary="openLeadSummary">
                </ApplicantTable>
            </v-col>
            <v-col cols="4">
                <ApplicationDetails :lead="leadDetails"></ApplicationDetails>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import AgentLeadMetrics from "@scripts/components/crm/leadmanagement/ApplicationsMetrics";
import ApplicantTable from "@scripts/components/crm/leadmanagement/ApplicantTable";
import ApplicationDetails from "@scripts/components/crm/leadmanagement/ApplicationDetails";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import ApplicationDetailScreen from "@scripts/components/crm/leadmanagement/ApplicationDetailScreen";
import AgentApplicationService from "@scripts/services/crm/AgentApplicationService";
export default {
    name: "ApplicationPage",
    components: {
        ApplicationDetailScreen,
        AgentLeadMetrics,
        ApplicantTable,
        ApplicationDetails
    },

    data() {
        return {
            leadTypes:[],
            activeLeadType: 'my_applications',
            leadTypesFlag: false,
            total_leads: 0,

            leads: null,
            leadDetails: null,
            selected_lead_id: null,
            isLoaded: false,
            sort_search_meta : null,
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalItem: null,
            options: {},
        }
    },

    methods: {
        async loadMetricTypes() {
            this.leadTypes = await LeadApplicationService.loadUserLeadMetrics();
            if(this.$route.query?.type) {
                this.activeLeadType = this.$route.query?.type
            }
            this.leadTypesFlag = true;
        },

        async loadLeads () {
            console.log('Type', this.activeLeadType);
            let data = await LeadApplicationService.loadUserLeads(this.sort_search_meta, this.activeLeadType);
            this.leads = data.applications;
            this.isLoaded = true;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
            this.selected_lead_id = this.leads[0].id;
            this.loadLeadSummary();
            console.log('lead list', this.leads);
        },

        async loadLeadSummary() {
            // this.activeLead = this.$route.query?.lead;
            this.leadDetails = await LeadApplicationService.loadUserLead(this.selected_lead_id);
        },

        updateTotal(total) {
            this.total_leads = total
        },

        openLeadSummary(id) {
            this.selected_lead_id = id;
            this.loadLeadSummary();
        },

        refreshDataTable(meta) {
            this.sort_search_meta = meta;
            this.loadLeads();
        }
    },

    mounted() {
        this.loadMetricTypes();
        this.loadLeads();
    },
    watch: {
        '$route': {
            handler() {
                this.activeLeadType = this.$route.query?.type;
                this.loadLeads();
            }
        },
    },

}
</script>

<style scoped>
</style>

