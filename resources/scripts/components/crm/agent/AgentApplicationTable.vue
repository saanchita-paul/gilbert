<template>
    <div v-if="user">
        <v-row class="mt-5">
            <v-col cols="8" class="search-bg">
                <v-btn
                    class="ma-2"
                    outlined
                    color="#542E89"
                    style="background: white;"
                    @click="advanceSearchDialog">
                        Advanced Search
                    </v-btn>
            </v-col>
            <v-col cols="4" class="text-right">
                <v-btn :disabled="isUserActive" color="primary" @click="addNewApplication"
                ><v-icon left>add
                </v-icon> Add New Application
                </v-btn>
            </v-col>
        </v-row>
        <AgentFilterChip
          :searchFilterModel="searchFilterModel"
          @removeFilters="removeFilters"
        />
        <v-card class="mt-2 hood-card-for-agency">
            <v-row class="crmTableRowDesign">
                <v-col cols="12" class="crm-table">
                  <v-data-table
                      :headers="headers"
                      :items="applications"
                      :options.sync="options"
                      :server-items-length="totalItem"
                      :loading="loading"
                      :item-class="isSelectedClass"
                      :single-expand="singleExpand"
                      :expanded.sync="expanded"
                      item-key="id"
                      show-expand
                      class="row-pointer"
                      @click:row="onRowSelect"
                  >
                    <template v-slot:item.first_name="{ item }">
                      {{ item.first_name + ' ' + item.last_name }}
                    </template>
                    <template v-slot:item.source="{ item }">
                      {{ sourcesNumberToName[item.source] }}
                    </template>

                      <template v-slot:item.status="{ item }">
                          {{item.status}}
                      </template>
                    <template v-slot:item.services="{ item }">
                      <v-icon small  :disabled="isServiceAllowed(item.services, 'power')" color="yellow">mdi-flash</v-icon>
                      <v-icon small :disabled="isServiceAllowed(item.services, 'gas')" color="red">mdi-fire</v-icon>
                      <v-icon small  :disabled="isServiceAllowed(item.services, 'internet')" color="green">mdi-wifi</v-icon>
                      <v-icon small :disabled="isServiceAllowed(item.services, 'water')" color="blue" >mdi-water</v-icon>
                    </template>

                    <template v-slot:expanded-item="{ headers, item }">
                      <td :colspan="headers.length" >
                         <AgentApplicationDetails :application='item'/>
                      </td>
                    </template>
                  </v-data-table>
                </v-col>
            </v-row>
        </v-card>
        <AdvanceSearchModal @filteredData="getFilteredData" v-if="advanceSearchModal" :dialog="advanceSearchModal" :title="''" @cancelDialog="cancelAdvanceSearchModal">
        </AdvanceSearchModal>
    </div>
</template>

<script>
import AdvanceSearchModal from '@scripts/components/crm/modals/AdvanceSearchModal.vue';
import {LeadSearchFilterModel} from '@scripts/models/LeadSearchFilterModel';
import AgentFilterChip from '@scripts/components/crm/agent/AgentFilterChip';
import {sourcesNumberToName} from '@scripts/data/LeadSourceMap';
import Search from "@scripts/components/crm/Search";
import AuthService from "@scripts/services/AuthService";
import AgentApplicationDetails from "@scripts/components/crm/agent/AgentApplicationDetails";

export default {
    name: "AgentApplicationTable",
    props: ["applications","totalItem", 'selectedAppId'],
    components: {
        Search,
        AgentApplicationDetails,
        AdvanceSearchModal,
        AgentFilterChip
    },
    data() {
      return {
        expanded: [],
        singleExpand: true,
        user: null,
        page: 1,
        pageCount: 0,
        itemsPerPage: 10,
        loading: false,
        options: {
          sortDesc: [],
          sortBy: [],
        },
        headers:  [
          {
            text: 'App Id',
            align: 'start',
            // sortable: true,
            value: 'id'
          },
          {
            text: 'Tenant Name',
            align: 'start',
            // sortable: true,
            value: 'first_name'
          },
          {
            text: 'Property Address',
            align: 'start',
            sortable: true,
            value: 'address_text'
          },
          {
            text: 'Created Date',
            align: 'start',
            sortable: true,
            value: 'created_at'
          },
          {

              text: 'Agent Name',
              align: 'start',
              sortable: true,
              value: 'agent_name'
          },
          {
              text: 'Agency',
              align: 'start',
              sortable: true,
              value: 'agency_office'
          },
          {
              text: 'Moving Date',
              align: 'start',
              sortable: true,
              value: 'moving_date'
          },
          {
              text: 'Source',
              align: 'start',
              sortable: true,
              value: 'source'
          },
          {
            text: 'Status',
            align: 'start',
            sortable: true,
            value: 'status'
          },
          {
            text: '',
            value: 'data-table-expand',
            align: 'start',
            sortable: true,
          },
        ],
        search: '',
        advanceSearchModal: false,
        searchFilterModel: new LeadSearchFilterModel(),
        filterItems: [],
        selectedRowId: 0,
      }
    },
    computed:{
      sourcesNumberToName(){
        return sourcesNumberToName;
      },
      isUserActive() {
        return this.user.is_active === 0 ? true : false;
      },
    },
    methods: {
       onRowSelect(item, slot){
         this.selectedRowId = item.id;
         slot.expand(!slot.isExpanded)
       },
        isSelectedClass(item) {
            if(item.id === this.selectedRowId) {
                return 'selectedRowForAgentTable';
            }
        },
        addNewApplication() {
            this.$router.push({name: 'agent.create.application'});
        },
        openApplicationSummary(application) {
            this.$emit("openApplicationSummary", application.id);
        },
        isServiceAllowed(services, type) {
           return !services.includes(type);
        },
      loadApplication() {
        const meta = {
          ...this.searchFilterModel,
          page: this.options.page,
          per_page: this.options.itemsPerPage,
          is_descending: this.options.sortDesc.length != 0? this.options.sortDesc[0]: false,
          sort_by: this.options.sortBy.length != 0? this.options.sortBy[0]: '',
        }
        this.$emit('refreshDataTable', meta);
      },

      updateSearch(search) {
          this.search = search;
          // this.loadApplication();
      },
      cancelAdvanceSearchModal(){
            this.advanceSearchModal = false;
      },
      advanceSearchDialog(){
          this.advanceSearchModal = true;
      },
      getFilteredData(filteredData){
          this.options.page = 1;
          this.searchFilterModel = {...filteredData};
          // this.loadApplication();
      },
      removeFilters(){
         this.searchFilterModel = new LeadSearchFilterModel();
         this.$router.push({name: 'agent.application.dashboard', query: {} })
        //  this.loadApplication();
      }
    },
  watch: {
    applications(val){
      // console.log(val)
    },
    options: {
      handler () {
        this.loadApplication();
      },
      deep: true,
    },
    '$route': {
      handler() {
        this.loadApplication();
    }
  },
  },
  async mounted() {
    this.user = await AuthService.getAuthUser();
    this.searchFilterModel =  new LeadSearchFilterModel(this.$route.query);
    this.loadApplication();
  }
}
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}

</style>
