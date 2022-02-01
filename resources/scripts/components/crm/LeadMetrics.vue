<template>
    <div>
    <div class="d-flex justify-space-between">
<!--      <div class="section-leademetriics">-->
<!--        <div class="leade-badge" v-for=" appMetric in appMetrics" :key="appMetric.id">-->
<!--            <h3>{{appMetric.title}}</h3>-->
<!--            <div class="leade-icon">-->
<!--                <v-icon :color="appMetric.color">{{appMetric.icon}}</v-icon>-->
<!--                <span>{{appMetric.lead_count}}</span>-->
<!--            </div>-->
<!--            <p class="leade-text">{{appMetric.status}}</p>-->
<!--        </div>-->
<!--      </div>-->

        <div style="flex-basis: 20%;" class="py-0">
            <slot v-if="isModeEdit" name="backButton"/>
            <h3  v-else >All Application Metrics</h3>
        </div>
        <div style="flex-basis: 75%;"  class="py-0">
           <div class="d-flex justify-end" style="flex-wrap: wrap;" >
            <div class="py-2 mr-2"> <v-btn @click="clearFilter" color="#C0C3C4" small v-if="!isSearchEmpty"> <v-icon small> mdi-close </v-icon> Reset </v-btn> </div>
               <div  class="py-0 mr-2 LeadMatics123" style="flex-basis: 235px;">
                   <v-text-field
                       class='date-select'
                       dense
                       label="Calender"
                       placeholder="Today"
                       v-model="selectedDate"
                       append-icon="mdi-calendar-range"
                       readonly
                       outlined
                       hide-details
                       @click="showDatePickerModal = true"
                       @click:append="showDatePickerModal = true"
                   ></v-text-field>
               </div>
               <div v-if="isModeEdit" class="py-0 mr-2 LeadMatics123 justify-end" style="flex-basis: 123px;">
                   <!-- <v-btn outlined >Edit Agency</v-btn> -->
                   <slot name="editButton"/>
               </div>

               
               <div v-if="!isModeEdit" class="py-0 mr-2 LeadMatics123" style="flex-basis: 90px;">
                   <v-select
                       class='date-select'
                       dense
                       label="State"
                       v-model="state"
                       @change="changeState"
                       item-text="text"
                       outlined
                       item-value="text"
                       :items="states"
                       hide-details
                   ></v-select>
               </div>
               <div v-if="!isModeEdit" class="py-0 mr-2 LeadMatics123" style="flex-basis: 130px;">
                   <v-select
                       class='date-select'
                       dense
                       outlined
                       label="Acct Manager HOOD"
                       v-model="hood_user"
                       :items="hood_users"
                       @change="changeHoodUser"
                       item-text="name"
                       item-value="id"
                       hide-details
                   ></v-select>
               </div>
               <div v-if="!isModeEdit" class="py-0 mr-2 LeadMatics123" style="flex-basis: 130px;">
                   <v-select
                       v-model="selectedAgency"
                       :items="agencies"
                       item-text="name"
                       item-value="id"
                       placeholder="Select Agency"
                       outlined
                       dense
                       hide-details="auto"
                       @change="onChangeAgentAgency"
                   >
                       <template v-slot:prepend-item>
                           <v-list-item ripple>
                               <v-text-field
                                   label="Search Agency"
                                   outlined
                                   dense
                                   prepend-inner-icon="mdi-magnify"
                                   hide-details="auto"
                                   v-model="search_agency"
                                   @input="changeText"
                               ></v-text-field>
                           </v-list-item>
                           <v-divider class="mt-2"></v-divider>
                       </template>
                   </v-select>
               </div>
               <div v-if="!isModeEdit"  class="py-0 mr-1 LeadMatics123" style="flex-basis: 130px;">
                   <v-select
                       v-model="selectedOffice"
                       :items="offices"
                       item-text="name"
                       item-value="id"
                       placeholder="Select Office"
                       outlined
                       dense
                       hide-details="auto"
                       @change="onChangeOffice"
                   >
                       <template v-slot:prepend-item>
                           <v-list-item ripple>
                               <v-text-field
                                   label="Search Office"
                                   outlined
                                   dense
                                   prepend-inner-icon="mdi-magnify"
                                   hide-details="auto"
                                   v-model="search_office"
                                   @input="changeOffice"
                               ></v-text-field>
                           </v-list-item>
                           <v-divider class="mt-2"></v-divider>
                       </template>
                   </v-select>
               </div>
           </div>
        </div>


    </div>
        <v-row class="my-4">
            <v-col cols="2">
                <div class="font-weight-bold text-center py-2 count_font">
                    <h3>{{appMetrics.applications_created}} </h3>
                </div>
                <div
                    class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitle_font"
                >
                    Applications Created
                </div>
            </v-col>
            <v-col cols="2" class="border-left">
                <div class="font-weight-bold text-center py-2 count_font">
                    <h3>{{appMetrics.active_agents}} </h3>
                </div>
                <div
                    class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitle_font">
                    Active Agent
                </div>
            </v-col>
            <v-col cols="2" class="border-left">
                <div
                    class="font-weight-bold text-center py-2 count_font">
                    <h3>{{appMetrics.agent_portal}} </h3>
                </div>
                <div
                    class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitle_font"
                > Agent Portal

                </div>
            </v-col>
            <v-col cols="2" class="border-left">
                <div class="font-weight-bold text-center py-2 count_font">
                    <h3>{{appMetrics.digital_application_platform}} </h3>
                </div>
                <div
                    class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitle_font"
                >
                    Digital Application Platform
                </div>
            </v-col>
            <v-col cols="2" class="border-left pr-0">
                <div
                    class="font-weight-bold text-center py-2 count_font">
                    <h3>{{appMetrics.property_management_system}} </h3>
                </div>
                <div class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitle_font">
                    Property Management System
                </div>
            </v-col>
            <v-col cols="2" class="px-0 ">

                <p class="font-weight-bold text-left  subtitle_font mt-3">
                    {{appMetrics.connected_property_me}} <span class="lead_span">Property Me</span>

                </p>
                <p class="font-weight-bold text-left subtitle_font">
                    {{appMetrics.connected_our_property}} <span  class="lead_span">Our Property</span>
                </p>
            </v-col>
        </v-row>
        <DatePickerModal
            v-if="showDatePickerModal"
            :dialog="showDatePickerModal"
            :dateRange="dateRange"
            @select="onSelectDate"
            @close="onCloseModal"
        />
    </div>
</template>

<script>
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import SalesFilter from "@scripts/modules/sales/components/SalesFilter";
import {getFormattedDBDate, getToday, getTodayString, getYesterday, isSame} from "@scripts/services/DateRangeService";
import DatePickerModal from "@scripts/modules/sales/components/DatePickerModal";
import {STATES} from "@scripts/data/constants/STATES";
import {merge} from "lodash-es";
import {AgencyFilter} from "@scripts/models/crm/AgencyFilter";
import { isEqual } from 'lodash-es'
export default {
  name: "LeadMetrics",
    components: {DatePickerModal, SalesFilter},
    props:{
      agency_id:
          {
              require: false,
              dateRange: null
          }
  },
    data(){
      return {
          appMetrics: {
              active_agents: 0,
              agent_portal: 0,
              applications_created: 0,
              connected_our_property: 0,
              connected_property_me: 0,
              digital_application_platform: 0,
              property_management_system: 0
          },
          selectedDate: '',
          showDatePickerModal: false,
          dateRange: {
              start: this.$route.query?.start ?
                  this.$route.query?.start : getTodayString(),
              end:  this.$route.query?.end ?
                  this.$route.query?.end : getTodayString()
          },
          states : STATES,
          state: '',
          hood_user: '',
          hood_users: [],
          search_agency: '',
          selectedAgency: '',
          agencies: [],

          search_office: '',
          offices: [],
          selectedOffice: '',
          agencyFilter : new AgencyFilter(),
          isSearchEmpty: true,

      }
    },
    computed:{
        isModeEdit(){
            return this.$route.name == 'real.state.agency.office' ? true : false;
      }
    },
    methods: {
      async loadMetrics() {
          this.isSearchEmpty = (new AgencyFilter(this.$route.query)).isSearchEmpty();
          console.log("agency filter" , this.agencyFilter)
          const allMetrics = await LeadApplicationService.loadAgencyMetricsByApplication(this.agencyFilter);
          this.appMetrics = allMetrics

          // const allMetric = await LeadApplicationService.loadMetrics({agency_id: this.agency_id});
          // this.appMetrics = allMetric.mapData;

      },
        async loadHooaUser() {
            this.hood_users = await LeadApplicationService.loadHoodUser();

        },
        async loadAgencies() {
            this.agencies = await LeadApplicationService.loadAgencies(this.search_agency);
        },
        async loadOffices() {
            this.offices = await LeadApplicationService.loadOffices(this.selectedAgency, this.search_office);
        },
        updateDate(dateRange) {
            this.dateRange = dateRange;
        },
        onSelectDate(dateRange) {
            this.dateRange = dateRange;
            this.showDatePickerModal = false;
            let queries = JSON.parse(JSON.stringify(this.$route.query));
            queries.start = this.dateRange.start;
            queries.end = this.dateRange.end;
            this.agencyFilter.start = this.dateRange.start;
            this.agencyFilter.end = this.dateRange.end;
            this.updateRouteParams();
             // this.$router.replace({ query: queries });
        },

        checkDate() {
            let today = getToday();
            let yesterday = getYesterday();
            if(isSame(this.dateRange.start, today)) {
                this.selectedDate = 'Today';
            } else if(isSame(this.dateRange.start, yesterday)) {
                this.selectedDate = 'Yesterday';
            } else {
                this.selectedDate = `${getFormattedDBDate(this.dateRange.start)} - ${getFormattedDBDate(this.dateRange.end)}`;
            }
        },

        onCloseModal() {
            this.showDatePickerModal = false;
        },
        onChangeAgentAgency() {
            merge(this.agencyFilter, {agency_id: this.selectedAgency});
            this.updateRouteParams();
        },
        changeText() {

        },
        changeOffice() {

        },
        onChangeOffice() {
            merge(this.agencyFilter, {office_id: this.selectedOffice});
            this.updateRouteParams();
        },
        changeState() {
          merge(this.agencyFilter, {state: this.state});
          this.updateRouteParams();
        },

        changeHoodUser() {
            merge(this.agencyFilter, {account_manager_id: this.hood_user});
          this.updateRouteParams()
        },

        updateRouteParams() {
            let params = { ...this.$route.query, ...this.agencyFilter }
            if(isEqual(this.$route.query , this.agencyFilter)) return;
            this.$router.replace({ query: {...params} });
        },

        syncQueryParas() {
          this.loadMetrics();
        },
        clearFilter(){
            this.selectedDate = null;
            this.state = null;
            this.hood_user = null;
            this.selectedAgency = null;
            this.selectedOffice = null;
            this.agencyFilter.clear();
            if(isEqual(this.$route.query , {})) return;
            this.$router.replace({ query: {} });
        },
        fillData(){
            this.dateRange = {
                end: this.$route?.query?.end,
                start: this.$route?.query?.start,
            }
            this.state = this.$route?.query?.state;
            this.hood_user = parseInt(this.$route?.query?.account_manager_id);
            this.selectedOffice = parseInt(this.$route?.query?.office_id);
            this.selectedAgency = parseInt(this.$route?.query?.agency_id);
        }
    },

    watch: {
        selectedAgency() {
            this.loadOffices();
        },
        dateRange(val) {
            this.checkDate();
            // this.updateDateRange();
        },
        '$route': {
            handler() {
                this.syncQueryParas();
            }
        },
        search_agency(){
            this.loadAgencies();
        },
        search_office(){
            this.loadOffices();
        },

    },

    async mounted() {
      this.agencyFilter.agency_id = this.$route.params.id
      this.agencyFilter = merge(this.agencyFilter, this.$route.query);
      await this.loadMetrics();
      await this.loadHooaUser();
      await this.loadAgencies();
      await this.loadOffices();
      this.fillData();
    }
};
</script>

<style scoped>

.count_font{
    font-size: 1.5em;
    color: #542e89;
}
.subtitle_font{
    font-size: 14px;
}
.lead_span{
    font-size: 12px !important;
    font-weight: normal !important;
    color:  #7E8A8F !important;
}

.border-left{
    border-left: 1px solid #7E8A8F;
}


.v-text-field >>> input {
    font-size: 14px;
}

.v-select >>> input {
    font-size: 14px;
}

.v-text-field .v-input__control .v-input__slot {
    min-height: 32px !important;
  }

</style>
