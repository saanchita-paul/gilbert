<template>
    <v-container fluid>
        <v-row>
            <v-col>
                <v-card class="hood-card">
                    <h3 class="page-title">Chatbot Applications & CAF Files</h3>
                </v-card>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <h5>Filters</h5>
                <ApplicationCafFileFilter></ApplicationCafFileFilter>
                <v-card>

                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import ApplicantTable from "@scripts/components/crm/leadmanagement/ApplicantTable";
import ApplicationCafFileFilter from '@scripts/pages/ApplicationCafFileFilter';
import {LeadSearchFilterModel} from "@scripts/models/LeadSearchFilterModel";

export default {
    name: "ApplicationCafFilePage",
    components: {
        ApplicantTable,
        ApplicationCafFileFilter
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
            },
            advanceSearch: new LeadSearchFilterModel()
        }
    },

    // methods: {
    //     async loadMetricTypes() {
    //       this.leadTypesFlag = false;
    //         this.leadTypes = await LeadApplicationService.loadUserLeadMetrics();
    //         if(this.$route.query?.type) {
    //             this.activeLeadType = this.$route.query?.type
    //         }
    //         this.leadTypesFlag = true;
    //     },
    //
    //     async fetchLeads () {
    //         this.isSearching = true;
    //         let data = await LeadApplicationService.loadUserLeads(
    //             {...this.sort_search_meta, ...{page: this.page}},
    //             this.activeLeadType,
    //             this.selectedSrc, this.advanceSearch,
    //         );
    //         this.leads = data.applications;
    //         this.isLoaded = true;
    //         this.isSearching = false;
    //         this.page = data.pagination.current_page;
    //         this.itemsPerPage = data.pagination.per_page;
    //         this.totalItem = data.pagination.total;
    //         this.selected_lead_id = this.leads[0]?.id;
    //         this.leads.length ? await this.loadLeadSummary() : "";
    //         // console.log('lead list', this.leads);
    //     },
    //
    //     async loadLeadSummary() {
    //         // this.activeLead = this.$route.query?.lead;
    //         this.leadDetails = await LeadApplicationService.loadUserLead(this.selected_lead_id);
    //     },
    //
    //     updateTotal(total) {
    //         this.total_leads = total
    //     },
    //
    //     openLeadSummary(id) {
    //         this.selected_lead_id = id;
    //         this.loadLeadSummary();
    //     },
    //
    //     refreshDataTable(meta) {
    //         this.page = meta.page
    //         this.sort_search_meta = omit({...meta}, 'page');
    //         this.loadLeads();
    //         // this.loadMetricTypes();
    //     },
    //   updateLeadAndatrics(leadId,userId) {
    //     this.leads.find(ld=>ld.id==leadId).assigned_to = userId;
    //     this.loadMetricTypes();
    //     },
    //     clearSearch(){
    //         this.advanceSearch = this.advanceSearchBluePrint;
    //         this.$router.push({
    //                 name: "application.list",
    //                 query: this.advanceSearch,
    //             });
    //     },
    //     resetPage() {
    //         this.page = 1;
    //     }
    // },
    //
    // created() {
    //     this.loadLeads = debounce(() => {
    //         this.fetchLeads()
    //     }, 400);
    // },
    //
    // mounted() {
    //     this.loadMetricTypes();
    //     this.advanceSearch = new LeadSearchFilterModel(this.$route.query);
    //     this.loadLeads();
    //
    // },
    // watch: {
    //     '$route': {
    //         handler() {
    //             let reload = this.activeLeadType !== this.$route.query?.type
    //                 || this.selectedSrc !== this.$route.query?.source;
    //
    //             this.activeLeadType = this.$route.query?.type;
    //             this.selectedSrc = this.$route.query?.source
    //             // console.log("watch", reload)
    //             // if (reload) {
    //             //     this.loadLeads();
    //             // }
    //         }
    //     },
    //     activeLeadType: {
    //         handler(){
    //             this.loadLeads();
    //         }
    //     },
    //     advanceSearch:{
    //         handler(value) {
    //             let params = { ...this.$route.query, ...value }
    //             if(isEqual(this.$route.query , value)) return;
    //             this.$router.push({
    //                 name: "application.list",
    //                 query: params,
    //             });
    //             this.resetPage();
    //             this.loadLeads();
    //
    //         },
    //         deep: true
    //     }
    // },

}
</script>

<style scoped>
</style>

