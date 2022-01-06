<template>
    <div>
        <v-row no-gutters class="mt-5">
            <v-col cols="8" class="search-bg">
                <!-- <Search @updateSearch="updateSearch"></Search> -->
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
        <v-row no-gutters>
            <v-col cols="12">
              <v-chip
                v-for="(item, index) in filterItems"
                :key="index"
                class="mx-2" color="#DDE2FF"
              >
                {{ item }}
              </v-chip>
            </v-col>
        </v-row>
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
        <AdvanceSearchModal @filteredData="getFilteredData" v-show="advanceSearchModal" :dialog="advanceSearchModal" :title="''" @cancelDialog="cancelAdvanceSearchModal">
        </AdvanceSearchModal>
    </div>
</template>

<script>
import Search from "@scripts/components/crm/Search";
import AdvanceSearchModal from '@scripts/components/crm/modals/AdvanceSearchModal.vue';
import { LeadSearchFilterModel } from '@scripts/models/LeadSearchFilterModel';
export default {
    name: "AgentApplicationTable",
    props: ["applications","totalItem", 'selectedAppId'],
    components: {
        Search,
        AdvanceSearchModal,
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
    mounted() {
      this.setFilterItems();
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
        this.$emit('refreshDataTable',meta);
      },

      updateSearch(search) {
          this.search = search;
          this.loadApplication();
      },
      cancelAdvanceSearchModal(){
            this.advanceSearchModal = false;
      },
      advanceSearchDialog(){
          this.advanceSearchModal = true;
      },
      getFilteredData(filteredData){
            console.log(filteredData);
            this.searchFilterModel = filteredData;
            this.loadApplication();
      },
      setFilterItems(){
          this.filterItems = [];
          if(this.searchFilterModel?.app_id && this.searchFilterModel?.app_id !== '') {
              this.filterItems.push('App ID: ' + this.searchFilterModel?.app_id);
          }
          if(this.searchFilterModel?.tenant_name && this.searchFilterModel?.tenant_name !== '') {
              this.filterItems.push('Tenant Name: ' + this.searchFilterModel?.tenant_name);
          }
          if(this.searchFilterModel?.address && this.searchFilterModel?.address !== '') {
              this.filterItems.push('Address: ' + this.searchFilterModel?.address);
          }
          if(this.searchFilterModel?.email && this.searchFilterModel?.email !== '') {
              this.filterItems.push('Email: ' + this.searchFilterModel?.email);
          }
          if(this.searchFilterModel?.agent_id && this.searchFilterModel?.agent_id !== '') {
              this.filterItems.push('Agent Name: ' + this.searchFilterModel?.agent_id);
          }
          if(this.searchFilterModel?.moving_date && this.searchFilterModel?.moving_date !== '') {
              this.filterItems.push('Moving Date: ' + this.searchFilterModel?.moving_date);
          }
          if(this.searchFilterModel?.source && this.searchFilterModel?.source !== '') {
              this.filterItems.push('Source: ' + this.searchFilterModel?.source);
          }
          if(this.searchFilterModel?.status && this.searchFilterModel?.status !== '') {
              this.filterItems.push('Status: ' + this.searchFilterModel?.status);
          }
      }
    },
  watch: {
    options: {
      handler () {
        this.loadApplication();
      },
      deep: true,
    },
    searchFilterModel: {
      handler () {
        this.setFilterItems();
      },
      deep: true,
    },
  }
}
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
  cursor: pointer;
}
</style>
