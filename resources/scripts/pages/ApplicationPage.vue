<template>
    <v-container fluid>
        <v-row>
            <v-col cols="8">
                <v-card class="hood-card">
                    <p>Your Metrics</p>
                    <div class="d-flex justify-space-between">
                        <h3 class="page-title">Total Applications: {{total_leads}}</h3>

                        <div class="d-flex">
                            <v-tooltip bottom content-class='custom-tooltip'>
                                <template v-slot:activator="{ on, attrs }">
                                    <span v-on="on">
                                        <p class="pt-2 pb-1 mb-0">
                                            <span class="ml-1">
                                                <v-icon>mdi-cart</v-icon>
                                            </span>
                                        </p>

                                    </span>
                                </template>
                                <div>
                                    <v-card
                                        max-width="400"
                                        outlined
                                        elevation="10"
                                    >
                                        <v-card-title>
                                            <span>Automatically assign leads to Chatbot.</span> &nbsp;
                                        </v-card-title>
                                        <v-card-text>
                                            <p>Gilbert will automatically assign every future applications from opt-in REA offices directly to the Chatbot.</p>
                                        </v-card-text>
                                    </v-card>
                                </div>
                            </v-tooltip>

                            <span class="mr-5">Automatically assign leads to Chatbot.</span>
                            <v-switch
                                inset
                                style="margin: 0 !important;"
                                v-model="assign_to_chatbot"
                            >
                            </v-switch>
                        </div>

                    </div>
                    <ApplicationsMetrics @resetPage="resetPage" v-if="leadTypesFlag" :activeLeadType="activeLeadType" :showDuplicate="showDuplicates" :leads="leadTypes" @updateTotal="updateTotal"></ApplicationsMetrics>
                </v-card>
                <ApplicationFilter v-model="advanceSearch" :isSearchEmpty="advanceSearch.isSearchEmpty()"></ApplicationFilter>
                <router-view
                    :leadSrc="selectedSrc"
                    v-if="isLoaded"
                    :applications="leads"
                    :totalItem="totalItem"
                    :currentLead="leadDetails"
                    @refreshDataTable="refreshDataTable"
                    @openLeadSummary="openLeadSummary"
                    @updateLeadAndatrics="updateLeadAndatrics"
                    :isSearching="isSearching"
                    :showDuplicates="showDuplicates"
                ></router-view>
            </v-col>
            <v-col cols="4">
                <ApplicationDetails :lead="leadDetails" @showDuplicateList="showDuplicateList"></ApplicationDetails>
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
import {isEqual, omit} from "lodash-es";
import {LeadSearchFilterModel} from '@scripts/models/LeadSearchFilterModel'
import ApplicationFilter from '@scripts/pages/ApplicationFilter';
import debounce from "lodash-es/debounce";
import DuplicateLeadService from "@scripts/services/crm/DuplicateLeadService";

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
            isSearching: false,
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
            search: "",
            advanceSearchBluePrint: {
                tenant_name:"",
                address: "",
                phone: "",
                source: "",
                tenancy_type: "",
                triage: "",
            },
            advanceSearch: new LeadSearchFilterModel(),
            showDuplicates: false,
            duplication_group_id: null,
            assign_to_chatbot: true
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

        async fetchLeads () {
            this.isSearching = true;
            let data = await LeadApplicationService.loadUserLeads(
                {...this.sort_search_meta, ...{page: this.page}, ...{
                    is_duplicate: this.showDuplicates,
                        duplication_group_id: this.duplication_group_id
                    }
                },
                this.activeLeadType,
                this.selectedSrc, this.advanceSearch,
            );
            this.leads = data.applications;
            this.isLoaded = true;
            this.isSearching = false;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
            this.selected_lead_id = this.leads[0]?.id;
            this.leads.length ? await this.loadLeadSummary() : "";
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
            this.page = meta.page
            this.sort_search_meta = omit({...meta}, 'page');
            this.loadLeads();
            // this.loadMetricTypes();
        },
      updateLeadAndatrics(leadId,userId) {
        this.leads.find(ld=>ld.id==leadId).assigned_to = userId;
        this.loadMetricTypes();
        },
        clearSearch(){
            this.advanceSearch = this.advanceSearchBluePrint;
            this.$router.push({
                    name: "application.list",
                    query: this.advanceSearch,
                });
        },
        resetPage() {
            this.page = 1;
        },

        async showDuplicateList(duplication_group_id) {
            let duplicatedData = await DuplicateLeadService.getDuplicateLeadData(duplication_group_id);
        }

    },

    created() {
        this.loadLeads = debounce(() => {
            this.fetchLeads()
        }, 400);
    },

    mounted() {
        this.loadMetricTypes();
        this.advanceSearch = new LeadSearchFilterModel(this.$route.query);
        this.loadLeads();

    },
    watch: {
        '$route': {
            handler() {
                let reload = this.activeLeadType !== this.$route.query?.type
                    || this.selectedSrc !== this.$route.query?.source;

                this.activeLeadType = this.$route.query?.type;
                this.selectedSrc = this.$route.query?.source;
                this.showDuplicates = Boolean(this.$route.query?.duplicates)? true: null;
                // this.duplication_group_id = this.$route.query?.duplication_group_id;
                this.advanceSearch.duplication_group_id = this.$route.query?.duplication_group_id;
            }
        },
        showDuplicates: {
            handler(){
                this.loadLeads();
            }
        },
        activeLeadType: {
            handler(){
                this.loadLeads();
            }
        },
        duplication_group_id: {

            handler(){
                this.page = 1;
                this.loadLeads();
            }
        },
        advanceSearch:{
            handler(value) {
                let params = { ...this.$route.query, ...value }
                if(isEqual(this.$route.query , value)) return;
                this.$router.push({
                    name: "application.list",
                    query: params,
                });
                this.resetPage();
                this.loadLeads();

            },
            deep: true
        }
    },

}
</script>

<style scoped>
.custom-tooltip {
    opacity: 1!important;
}
.v-tooltip__content {
    background-color: transparent;
}
</style>

