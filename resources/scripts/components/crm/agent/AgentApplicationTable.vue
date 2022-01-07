<template>
    <div>
        <v-row no-gutters class="mt-5">
            <v-col cols="8" class="search-bg">
                <v-btn
                    class="ma-2"
                    outlined
                    color="indigo"
                    @click="advanceSearchDialog">
                        Advanced Search
                    </v-btn>
            </v-col>
            <v-col cols="4" class="text-right">
                <v-btn color="primary" @click="addNewApplication"
                ><v-icon left>add
                </v-icon> Add New Application
                </v-btn>
            </v-col>
        </v-row>
        <AgentFilterChip
          :searchFilterModel="searchFilterModel"
          @removeFilters="removeFilters"
        />
        <v-card class="mt-2 hood-card">
            <v-row>
                <v-col cols="12" class="crm-table">
                  <v-data-table
                      :headers="headers"
                      :items="applications"
                      :options.sync="options"
                      :server-items-length="totalItem"
                      :loading="loading"
                      :item-class="isSelectedClass"
                      class="row-pointer"
                      @click:row="openApplicationSummary"
                  >
                    <template v-slot:item.first_name="{ item }">
                      {{ item.first_name + ' ' + item.last_name }}
                    </template>
                      <template v-slot:item.status="{ item }">
                          {{['UnAssigned','Assigned', 'Escalated'].includes(item.status)?'In Progress': item.status }}
                      </template>
                    <template v-slot:item.services="{ item }">
                      <v-icon small  :disabled="isServiceAllowed(item.services, 'power')" color="yellow">mdi-flash</v-icon>
                      <v-icon small :disabled="isServiceAllowed(item.services, 'gas')" color="red">mdi-fire</v-icon>
                      <v-icon small  :disabled="isServiceAllowed(item.services, 'internet')" color="green">mdi-wifi</v-icon>
                      <v-icon small :disabled="isServiceAllowed(item.services, 'water')" color="blue" >mdi-water</v-icon>
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
import { LeadSearchFilterModel } from '@scripts/models/LeadSearchFilterModel';
import AgentFilterChip from '@scripts/components/crm/agent/AgentFilterChip';
export default {
    name: "AgentApplicationTable",
    props: ["applications","totalItem", 'selectedAppId'],
    components: {
        AdvanceSearchModal,
        AgentFilterChip
    },
    data() {
      return {
        page: 1,
        pageCount: 0,
        itemsPerPage: 10,
        loading: false,
        options: {},
        headers:  [
          {
            text: 'Name',
            align: 'start',
            sortable: true,
            value: 'first_name'
          },
          {
            text: 'Moving date',
            align: 'start',
            sortable: true,
            value: 'moving_date'
          },
          {
            text: 'Mobile',
            align: 'start',
            sortable: true,
            value: 'phone'
          },
          {
            text: 'Preference',
            align: 'start',
            sortable: true,
            value: 'services'
          },
          {
              text: 'Status',
              align: 'start',
              sortable: true,
              value: 'status'
          }
        ],
        search: '',
        advanceSearchModal: false,
        searchFilterModel: new LeadSearchFilterModel(),
        filterItems: []
      }
    },
    methods: {
        isSelectedClass(item) {
            if(item.id === this.selectedAppId) {
                return 'selectedRow';
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
          this.searchFilterModel = {...filteredData};
          // this.loadApplication();
      },
      removeFilters(){
         this.searchFilterModel = new LeadSearchFilterModel();
         this.$router.push({name: 'agent.application.dashboard', query: {} })
        //  this.loadApplication();
      }
    },
  mounted(){
    this.searchFilterModel =  new LeadSearchFilterModel(this.$route.query);
    this.loadApplication();
  },
  watch: {
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
  }
}
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}
</style>
