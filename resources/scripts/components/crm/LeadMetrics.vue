<template>
    <div>
    <v-row>
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

        <v-col cols="3" class="py-0">
            <h3>All Application Metrics</h3>
        </v-col>
        <v-col cols="9"  class="py-0">
           <v-row>
               <v-col  class="py-0">
                   <v-text-field
                       class='date-select'
                       solo
                       dense
                       label="Calender"
                       placeholder="Today"
                       v-model="selectedDate"
                       append-icon="mdi-calendar-range"
                       readonly
                       hide-details
                       @click="showDatePickerModal = true"
                       @click:append="showDatePickerModal = true"
                   ></v-text-field>
               </v-col>
               <v-col  class="py-0">
                   <v-select
                       class='date-select'
                       solo
                       dense
                       label="State"
                       v-model="state"
                       @change="changeState"
                       item-text="text"
                       item-value="text"
                       :items="states"
                       hide-details
                   ></v-select>
               </v-col>
               <v-col  class="py-0">
                   <v-select
                       class='date-select'
                       solo
                       dense
                       label="Acct Manager HOOD"
                       v-model="hood_user"
                       :items="hood_users"
                       @change="changeHoodUser"
                       item-text="name"
                       item-value="id"
                       hide-details
                   ></v-select>
               </v-col>
               <v-col  class="py-0">
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
               </v-col>
               <v-col  class="py-0">
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
               </v-col>
           </v-row>
        </v-col>


    </v-row>
        <v-row class="my-4">
            <v-col cols="2">
                <div
                    class="font-weight-bold text-center py-0 count_font"
                >
                    {{'123456'}}
                </div>
                <div
                    class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitle_font"
                >
                    Application Created
                </div>
            </v-col>
            <v-col cols="2" class="border-left">
                <div
                    class="font-weight-bold text-center py-0 count_font"
                >
                    {{'123456'}}
                </div>
                <div
                    class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitle_font"
                >
                    Agent Portal
                </div>
            </v-col>
            <v-col cols="2" class="border-left">
                <div
                    class="font-weight-bold text-center py-0 count_font"
                >
                    {{'123456'}}
                </div>
                <div
                    class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitle_font"
                >
                   Digital Application Platform
                </div>
            </v-col>
            <v-col cols="2" class="border-left">
                <div
                    class="font-weight-bold text-center py-0 count_font"
                >
                    {{'123456'}}
                </div>
                <div
                    class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitle_font"
                >
                    Property Management System
                </div>
            </v-col>
            <v-col cols="2" class="border-left">
                <div
                    class="font-weight-bold text-center py-0 count_font"
                >
                    {{'123456'}}
                </div>
                <div
                    class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitle_font"
                >
                    Total connected applications
                </div>
            </v-col>
            <v-col cols="2" class="border-left">

                <p class="font-weight-bold text-center  subtitle_font">
                    12345 <span class="lead_span">Property Me</span>

                </p>
                <p class="font-weight-bold text-center subtitle_font">
                    12345 <span  class="lead_span">Our Property</span>
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
          appMetrics: [],
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
          selected_state: '',
          hood_user: '',
          hood_users: [],
          search_agency: '',
          selectedAgency: '',
          agencies: [],

          search_office: '',
          offices: [],
          selectedOffice: '',


      }
    },
    methods: {
      async loadMetrics() {
          const allMetric = await LeadApplicationService.loadMetrics({agency_id: this.agency_id});
          this.appMetrics = allMetric.mapData;

      },
        async loadHooaUser() {
            this.hood_users = await LeadApplicationService.loadHoodUser();

        },
        async loadAgencies() {
            this.agencies = await LeadApplicationService.loadAgencies();
            console.log('agencies', this.agencies);
        },
        async loadOffices() {
            this.offices = await LeadApplicationService.loadOffices(this.selectedAgency);
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
             this.$router.replace({ query: queries });
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
            this.updateRouteParams({agency: this.selectedAgency});
        },
        changeText() {

        },
        changeOffice() {

        },
        onChangeOffice() {
            this.updateRouteParams({office: this.selectedOffice});
        },
        changeState() {
          this.updateRouteParams({state: this.state});
        },

        changeHoodUser() {
          this.updateRouteParams({hood_user_id: this.hood_user})
        },

        updateRouteParams(q) {
            let queryParams = this.$route.query;
            queryParams = merge(queryParams, q);
            // this.$router.push({name:'real.state.agency', query: {...queryParams}});
            console.log('query Params', queryParams);
            this.$router.replace({ query: queryParams });
        },

        syncQueryParas() {
          console.log('query params', this.$route.query);
        }
    },

    watch: {
        selectedAgency() {
            this.loadOffices();
        },
        dateRange() {
            this.checkDate();
            this.updateDateRange();
        },
        '$route': {
            handler() {
                this.syncQueryParas();
            }
        },

    },

    async mounted() {
      await this.loadMetrics();
      await this.loadHooaUser();
      await this.loadAgencies();
    }
};
</script>

<style scoped>

.count_font{
    font-size: 32px;
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

</style>
