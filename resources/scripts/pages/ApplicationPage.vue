<template>
    <v-container fluid>
        <v-row>
            <v-col cols="8">
                <v-card class="hood-card">
                    <p>Your Metrics</p>
                    <h3 class="page-title">Total Applications: {{total_leads}}</h3>
                    <ApplicationsMetrics v-if="leadTypesFlag" :activeLeadType="activeLeadType" :leads="leadTypes" @updateTotal="updateTotal"></ApplicationsMetrics>
                </v-card>
                <!-- <ApplicantTable
                    :leadSrc="selectedSrc"
                    v-if="isLoaded"
                    :applications="leads"
                    :totalItem="totalItem"
                    :currentLead="leadDetails"
                    @refreshDataTable="refreshDataTable"
                    @openLeadSummary="openLeadSummary"
                    @updateLeadAndatrics="updateLeadAndatrics"
                >
                </ApplicantTable> -->

                <ApplicationFilter/>
                <!-- <v-text-field v-model="search" full-width /> -->
                <router-view
                    :leadSrc="selectedSrc"
                    :applications="leads"
                    :totalItem="totalItem"
                    :currentLead="leadDetails"
                ></router-view>
            </v-col>
            <v-col cols="4">
                <ApplicationDetails :lead="leadDetails"></ApplicationDetails>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import ApplicationsMetrics from "@scripts/components/crm/leadmanagement/ApplicationsMetrics";
import ApplicantTable from "@scripts/components/crm/leadmanagement/ApplicantTable";
import ApplicationDetails from "@scripts/components/crm/leadmanagement/ApplicationDetails";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import ApplicationDetailScreen from "@scripts/components/crm/leadmanagement/ApplicationDetailScreen";
import AgentApplicationService from "@scripts/services/crm/AgentApplicationService";
import ApplicationFilter from './ApplicationFilter';

export default {
    name: "ApplicationPage",
    components: {
        ApplicationDetailScreen,
        ApplicantTable,
        ApplicationDetails,
        ApplicationsMetrics,
        ApplicationFilter
    },

    data() {
        return {
            leadTypes:[],
            selectedSrc: this.$route.query.source || 'all',
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
            search: ""
        }
    },

    methods: {
        async loadMetricTypes() {
          this.leadTypesFlag = false;
            this.leadTypes = await LeadApplicationService.loadUserLeadMetrics();
            if(this.$route.query?.type) {
                this.activeLeadType = this.$route.query?.type
            }
            this.leadTypesFlag = true;
        },

        async loadLeads () {
            let data = await LeadApplicationService.loadUserLeads(this.sort_search_meta, this.activeLeadType, this.selectedSrc);
            this.leads = data.applications;
            this.isLoaded = true;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
            this.selected_lead_id = this.leads[0].id;
            this.loadLeadSummary();
            // console.log('lead list', this.leads);
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
            // this.loadMetricTypes();
        },
      updateLeadAndatrics(leadId,userId) {
        this.leads.find(ld=>ld.id==leadId).assigned_to = userId;
        this.loadMetricTypes();
        }
    },

    mounted() {
        this.loadMetricTypes();
        this.loadLeads();
    },
    watch: {
        '$route': {
            handler() {
                let reload = this.activeLeadType !== this.$route.query?.type
                    || this.selectedSrc !== this.$route.query?.source;

                this.activeLeadType = this.$route.query?.type;
                this.selectedSrc = this.$route.query?.source
                // console.log("watch", reload)
                if (reload) {
                    this.loadLeads();
                }
            }
        },
    },

}
</script>

<style scoped>
</style>

