<template>
  <div>
    <div class="d-flex header justify-space-between align-center mb-6">
      <div class="font-weight-bold ml-6">Advanced Search</div>
      <div class="mr-6" @click="cancel">
        <v-icon color="white"> mdi-close </v-icon> Close
      </div>
    </div>

    <div class="d-flex justify-space-between align-center py-2">
      <div class="font-weight-bold ml-6" style="flex-basis: 15%">APP ID</div>
      <div style="flex-basis: 65%" class="mr-6">
        <v-text-field
          outlined
          dense
          full-width
          hide-details="auto"
          label="App Id"
          placeholder="App Id"
          v-model="search.app_id"
        ></v-text-field>
      </div>
    </div>

    <div class="d-flex justify-space-between align-center py-2">
      <div class="font-weight-bold ml-6" style="flex-basis: 15%">
        Tenant Name
      </div>
      <div style="flex-basis: 65%" class="mr-6">
        <v-text-field
          outlined
          dense
          full-width
          hide-details="auto"
          label="Tenant Name"
          v-model="search.tenant_name"
        ></v-text-field>
      </div>
    </div>

    <div class="d-flex justify-space-between align-center py-2">
      <div class="font-weight-bold ml-6" style="flex-basis: 15%">Address</div>
      <div style="flex-basis: 65%" class="mr-6">
        <v-text-field
          outlined
          dense
          full-width
          hide-details="auto"
          label="Address"
          v-model="search.address"
        ></v-text-field>
      </div>
    </div>

    <div class="d-flex justify-space-between align-center py-2">
      <div class="font-weight-bold ml-6" style="flex-basis: 15%">Email</div>
      <div style="flex-basis: 65%" class="mr-6">
        <v-text-field
          outlined
          dense
          full-width
          hide-details="auto"
          label="Email"
          v-model="search.email"
        ></v-text-field>
      </div>
    </div>

    <div class="d-flex justify-space-between align-center py-2">
      <div class="font-weight-bold ml-6" style="flex-basis: 15%">
        Moving Date
      </div>
      <div style="flex-basis: 65%" class="mr-6">
        <v-menu
          v-model="connection_date_menu"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
          <template v-slot:activator="{ on, attrs }">
            <ValidationProvider
              name="Connection Date"
              rules="valid-date"
              v-slot="{ errors }"
            >
              <v-text-field
                placeholder="DD/MM/YYYY"
                outlined
                dense
                v-bind="attrs"
                append-icon="mdi-calendar"
                v-model="search.moving_date"
                :error-messages="errors[0]"
                hide-details="auto"
              >
                <template slot="append">
                  <v-icon v-on="on">mdi-calendar</v-icon>
                </template>
              </v-text-field>
            </ValidationProvider>
          </template>
          <v-date-picker
            v-model="moving_date"
            @input="updateMovingDate"
          ></v-date-picker>
        </v-menu>
      </div>
    </div>

    <div class="d-flex justify-space-between align-center py-2">
      <div class="font-weight-bold ml-6" style="flex-basis: 15%">Source</div>
      <div style="flex-basis: 65%" class="mr-6">
        <v-select
          outlined
          dense
          full-width
          hide-details="auto"
          v-model="search.source"
          :items="sources"
          label="Source"
        >
        </v-select>
      </div>
    </div>

    <div class="d-flex justify-space-between align-center py-2">
      <div class="font-weight-bold ml-6" style="flex-basis: 15%">Status</div>
      <div style="flex-basis: 65%" class="mr-6">
        <v-select
          outlined
          dense
          full-width
          hide-details="auto"
          v-model="search.status"
          :items="statuses"
          label="Status"
        >
        </v-select>
      </div>
    </div>

    <div class="d-flex justify-center pb-2">
      <div style="flex-basis: 25%">
        <v-btn @click="clearFilter" small block color="#E0E0E0" class="black--text">
          <v-icon> mdi-close </v-icon> Clear Filter</v-btn
        >
      </div>
    </div>

    <div class="d-flex justify-center pb-4">
      <div style="flex-basis: 90%">
        <v-btn block color="#542E89" class="white--text">Search</v-btn>
      </div>
    </div>
  </div>
</template>

<script>
import { formatDate } from "@scripts/services/others/DateService"
import { LeadSearchFilterModel } from '@scripts/models/LeadSearchFilterModel';
export default {
  name: "AgencyAdvanceSearchDetails",
  props: [],
  data() {
    return {
      agency: {
        type: "",
        title: "",
      },
      agencyType: [
        {
          id: 0,
          title: "Independent Agency",
        },
        {
          id: 1,
          title: "Franchised Agency",
        },
      ],
      sources: ["Hood", "Foxie"],
      source: "",
      statuses: ["Active", "Processing"],
      status: "",
      connection_date_menu: false,
      modified_moving_date: null,
      moving_date: null,

      search : new LeadSearchFilterModel(),


    };
  },
  methods: {
    cancel() {
      this.$emit("cancelDialog");
    },
    async saveAgency() {
      let v = await this.$refs.create_agency.validate();
      if (v) {
        this.$emit("saveAgency", this.agency);
      }
      return v;
    },
    updateMovingDate(value) {
      this.search.moving_date = formatDate(value);
      this.connection_date_menu = false;
    },
    clearFilter(){
      this.search.clear();
    }
  },
};
</script>

<style lang="scss" scoped>
.header {
  background: #542e89;
  color: white;
  height: 50px;
  width: 500px;
}
</style>
