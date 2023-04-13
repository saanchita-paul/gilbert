<template>
    <v-row>
        <v-col cols="12">
            <h3>Filters</h3>
            <GilbertApplicationCafFileFilter
                :selectedCafFile="selectedCafFile"
                v-model="advanceSearchModelFilter"
                :gilbertApplications="applications"
                :isSearchEmpty="advanceSearchModelFilter.isSearchEmpty()"
                @updatePageOnFilterChange="updatePageOnFilterChange"
                @updateDates="updateDates">
            </GilbertApplicationCafFileFilter>
        </v-col>
        <v-col cols="12">
            <NbnApplicationCafFileTable
                v-model="selectedCafFile"
                :gilbertApplications="applications"
                :totalItems="totalItems"
                :pages="pages"
                @reloadDataTable="reloadDataTable"
                @selectRowCafFiles="selectRowCafFiles"
            >
            </NbnApplicationCafFileTable>
        </v-col>
    </v-row>
</template>

<script>

 import GilbertApplicationCafFileFilter from "@scripts/pages/GilbertApplicationCafFileFilter.vue";
 import GilbertApplicationCafFileTable from "@scripts/pages/GilbertApplicationCafFileTable.vue";
 import {CafFileSearchFilterModel} from "@scripts/models/CafFileSearchFilterModel";
 import {isEqual, omit} from "lodash-es";
 import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";
 import NbnApplicationCafFileTable from "@scripts/pages/NbnApplicationCafFileTable.vue";

 export default {
     name: "GilbertNbnFilterWrapper",
     props : ["filterFor"],
     components: {NbnApplicationCafFileTable, GilbertApplicationCafFileTable, GilbertApplicationCafFileFilter},
     data(){
         return {
             advanceSearchModelFilter :  new CafFileSearchFilterModel(),
             applications : [],
             pages : 1,
             totalItems : null,
             dateRange: null,
             sorts_search_meta : null,
             itemsPerPages : 10,
             selectedCafFile : []

         }
     },
     watch:{
         advanceSearchModelFilter: {
             handler(value) {
                 let params = {...this.$route.query, ...value}
                 if (isEqual(this.$route.query, value)) return;
                 this.$router.push({
                     name: "caf.files",
                     query: params,
                 });
                 this.resetPage();
                 this.fetchApplications();
             },
             deep: true
         },
     },
     methods:{
         updatePageOnFilterChange() {
             this.pages = 1;
         },
         updateDates(dateRange) {
             if (dateRange) {
                 this.advanceSearchModelFilter.start_date = dateRange.start
                 this.advanceSearchModelFilter.end_date = dateRange.end
             }

         },
         reloadDataTable(meta) {
             this.pages = meta.page;
             this.sorts_search_meta = omit({...meta}, 'page');
             this.fetchApplications();
         },
         resetPage() {
             this.page = 1;
         },
         async fetchApplications() {
             let data = ''
             if(this.$route.query.inner_tab === 'nbnGilbertApplication'){
                 data = await ApplicationCafFileService.getGilbertNBNApplicationData({...this.sorts_search_meta, ...{page: this.pages}}, this.advanceSearchModelFilter);
             }else{
                 // data = await ApplicationCafFileService.getGilbertApplicationData({...this.sorts_search_meta, ...{page: this.pages}}, this.advanceSearchModelFilter);
             }

             this.applications = data.data;
             this.pages = data.pagination.current_page;
             this.itemsPerPages = data.pagination.per_page;
             this.totalItems = data.pagination.total;


         },
         selectRowCafFiles(item) {
             let index = this.selectedCafFile.findIndex(dt => dt.id === item.id);
             if(index === -1) {
                 this.selectedCafFile.push(item);
             } else {
                 this.selectedCafFile.splice(index, 1);
             }
         },
     },
     mounted(){
         this.advanceSearchModelFilter.application_service_type = this.filterFor
     },

 }
</script>

<style scoped>

</style>
