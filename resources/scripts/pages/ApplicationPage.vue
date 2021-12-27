<template>
    <v-container fluid>
        <v-row>
            <v-col cols="8">
                <v-card class="hood-card">
                    <p>Your Metrics</p>
                    <h3 class="page-title">Total Applications: {{total_leads}}</h3>
                    <ApplicationsMetrics v-if="leadTypesFlag" :activeLeadType="activeLeadType" :leads="leadTypes" @updateTotal="updateTotal"></ApplicationsMetrics>
                </v-card>
                <ApplicationFilter v-model="advanceSearch" ></ApplicationFilter>
                <router-view
                    :leadSrc="selectedSrc"
                    v-if="isLoaded"
                    :applications="leads"
                    :totalItem="totalItem"
                    :currentLead="leadDetails"
                    @refreshDataTable="refreshDataTable"
                    @openLeadSummary="openLeadSummary"
                    @updateLeadAndatrics="updateLeadAndatrics"
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
import {isEqual , pick} from "lodash-es";
import { LeadSearchFilterModel } from '@scripts/models/LeadSearchFilterModel'
import ApplicationFilter from '@scripts/pages/ApplicationFilter';

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
            search: "",
            advanceSearchBluePrint: {
                tenancy_name:"",
                address: "",
                mobile: "",
                source: "",
                tenancy_type: "",
            },
            advanceSearch: new LeadSearchFilterModel()
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
            console.log("api calling");
            let data = await LeadApplicationService.loadUserLeads(this.sort_search_meta, this.activeLeadType, this.selectedSrc, this.advanceSearch);
            this.leads = data.applications;
            this.isLoaded = true;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
            this.selected_lead_id = this.leads[0]?.id;
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
        },
        clearSearch(){
            console.log("clicking slot")
            this.advanceSearch = this.advanceSearchBluePrint;
            this.$router.push({
                    name: "application.list",
                    query: this.advanceSearch,
                });
        }
    },

    mounted() {
        this.loadMetricTypes();
        this.advanceSearch = new LeadSearchFilterModel(this.$route.query);
        this.loadLeads();

    },
    watch: {
        '$route': {
            handler() {
                console.log(this.$route.query.name)
                let reload = this.activeLeadType !== this.$route.query?.type
                    || this.selectedSrc !== this.$route.query?.source;

                this.activeLeadType = this.$route.query?.type;
                this.selectedSrc = this.$route.query?.source
                // console.log("watch", reload)
                // if (reload) {
                //     this.loadLeads();
                // }
            }
        },
        activeLeadType: {
            handler(){
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
                this.loadLeads();
            },
            deep: true
        }
    },

}
</script>

<style scoped>
</style>

