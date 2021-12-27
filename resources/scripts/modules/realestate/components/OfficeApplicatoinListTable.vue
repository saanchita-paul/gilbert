<template>
  <div>
    <v-card class="hood-card">
      <v-row>
        <v-col cols="12" class="crm-table">
          <v-data-table
            :headers="headers"
            :items="applications"
            :options.sync="options"
            :server-items-length="totalItem"
            :loading="loading"
            class="row-pointer"
            @click:row="openLeadSummary"
          >
            <template v-slot:item.services="{ item }">
              <v-icon
                small
                :disabled="isServiceAllowed(item.services, 'power')"
                color="yellow"
                >mdi-flash</v-icon
              >
              <v-icon
                small
                :disabled="isServiceAllowed(item.services, 'gas')"
                color="red"
                >mdi-fire</v-icon
              >
              <v-icon
                small
                :disabled="isServiceAllowed(item.services, 'internet')"
                color="green"
                >mdi-wifi</v-icon
              >
              <v-icon
                small
                :disabled="isServiceAllowed(item.services, 'water')"
                color="blue"
                >mdi-water</v-icon
              >
            </template>
            <template v-slot:item.source="{ item }">
              <div v-text="leadSourceMapFromNumber[item.source]"></div>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
      <ReassignModal
        :dialog="reassignFlag"
        :user="selectedUser"
        :assignedText="assignedText"
        :lead="selectedLead"
        @cancelModal="cancelModal"
      >
      </ReassignModal>
    </v-card>
  </div>
</template>

<script>
import Search from "@scripts/components/crm/Search";
import AssigneeDropdown from "@scripts/components/crm/leadmanagement/AssigneeDropdown";
import CrmUserService from "@scripts/services/crm/CrmUserService";
import AssignedtoPopUp from "@scripts/components/crm/leadmanagement/AssignedtoPopUp";
import ReassignModal from "@scripts/components/crm/modals/ReassignModal";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import AuthService from "@scripts/services/AuthService";
import axios from "axios";
import { LeadSearchFilterModel } from "@scripts/models/LeadSearchFilterModel";
import { leadSourceMapFromNumber } from "@scripts/data/LeadSourceMap";
export default {
  name: "OfficeApplicatoinListTable",
  components: {
    ReassignModal,
    Search,
    AssigneeDropdown,
    AssignedtoPopUp,
  },

  props: {
    officeId: { required: true },
    leadSrc: { default: "all" },
    currentLead: {
      required: false,
    },
  },

  data() {
    return {
      isActive: true,
      users: null,
      reassignFlag: false,
      selectedLead: null,
      selectedUser: null,
      assignedText: null,
      currentUser: null,

      userSearch: "",
      leadSearch: "",
      options: {},
      loading: false,
      page: 1,
      pageCount: 0,
      itemsPerPage: 10,
      totalUserItem: null,
      selectedSrc: this.$route.query.source || "hood",
      srcOptions: [
        // {text: 'Select a lead', value: '', disabled: true},
        { text: "All Lead Source", value: "all", icon: "" },
        {
          text: "Hood Agent Portal",
          value: "hood",
          icon: "/assets/images/icons/company/hood.png",
        },
        {
          text: "Foxie CRM",
          value: "foxie",
          icon: "/assets/images/icons/company/foxie.png",
        },
        {
          text: "Ignite ",
          value: "ignite",
          icon: "/assets/images/icons/company/ignite.png",
        },
        {
          text: "Our Property",
          value: "our-property",
          icon: "/assets/images/icons/company/our-property.png",
        },
        {
          text: "PropertyMe ",
          value: "property_me",
          icon: "/assets/images/icons/company/propertyMe.png",
        },
      ],
      headers: [
        {
          text: "Tenant Name",
          align: "start",
          sortable: true,
          value: "tenant_name",
        },
        {
          text: "Property Address",
          align: "start",
          sortable: true,
          value: "address_text",
        },
        {
          text: "Connection Date",
          align: "start",
          sortable: true,
          value: "moving_date",
        },
        {
          text: "Submission Date",
          align: "start",
          sortable: true,
          value: "submitted_at",
        },
        {
          text: "Submitted By",
          align: "start",
          sortable: true,
          value: "submitted_by",
        },
        {
          text: "Created At",
          align: "start",
          sortable: true,
          value: "created_at",
        },
        {
          text: "Source",
          align: "start",
          sortable: true,
          value: "source",
        },
        {
          text: "Services",
          align: "start",
          sortable: true,
          value: "services",
        },
        {
          text: "Status",
          align: "start",
          sortable: true,
          value: "status",
        },
      ],
      applications: [],
      totalItem: 0,
      advanceSearch: "",
      isLoaded: false,
      sort_search_meta: {},
    };
  },
  computed: {
    leadSourceMapFromNumber() {
      return leadSourceMapFromNumber;
    },
  },
  methods: {
    async loadLeads(meta) {
      console.log("load lead");
      this.loading = true;
      let data = await LeadApplicationService.loadUserLeads(
        meta
      );
      this.loading = false;
      this.applications = data.applications;
      this.page = data.pagination.current_page;
      this.itemsPerPage = data.pagination.per_page;
      this.totalItem = data.pagination.total;
    },
    isSelectedClass(item) {
      if (item.id === this.currentLead?.id) {
        return "selectedRow";
      }
    },
    updateUserSearch(search) {
      this.userSearch = search;
      this.loadUserList();
    },
    updateLeadSearch(search) {
      this.leadSearch = search;
      this.loadLeadList();
    },
    async loadUserList() {
      const meta = {
        search: this.userSearch,
        page: this.options.page,
        per_page: this.options.itemsPerPage,
        is_descending: false,
        sort_by: "",
      };
      const data = await CrmUserService.loadAllUser(meta);
      this.users = data?.users;
      this.page = data.pagination.current_page;
      this.itemsPerPage = data.pagination.per_page;
      this.totalUserItem = data.pagination.total;
    },

    async assignUser(user, lead, assignedText) {
      this.selectedLead = lead;
      this.selectedUser = user;
      this.assignedText =
        assignedText == "Reassign" ? "reassigned" : "assigned";
      await LeadApplicationService.assignUser(lead.id, user.id).then((res) => {
        // this.loadLeadList();
        this.$emit("updateLeadAndatrics", lead.id, user.id);
        this.reassignFlag = true;
      });
    },

    isServiceAllowed(services, type) {
      return !services.includes(type);
    },

    cancelModal() {
      this.reassignFlag = false;
    },

    openLeadSummary(application) {
      this.$emit("openLeadSummary", application.id);
    },

    loadLeadList() {
      const meta = {
        search: this.leadSearch,
        page: this.options.page,
        per_page: this.options.itemsPerPage,
        is_descending:
          this.options.sortDesc.length != 0 ? this.options.sortDesc[0] : false,
        sort_by: this.options.sortBy.length != 0 ? this.options.sortBy[0] : "",
        ...new LeadSearchFilterModel({ office_id: this.officeId }),
      };
      this.loadLeads(meta);
    },
    onSrcChange(value) {
      this.$router.push({
        name: "applications",
        query: { ...this.$route.query, ...{ source: value } },
      });
    },
  },
  mounted() {
    this.currentUser = AuthService.getAuthUser();
    this.loadLeadList();
  },
  watch: {
    options: {
      handler() {
        this.loadLeadList();
      },
      deep: true,
    },
  },
};
</script>

<style>
.selectedRow {
  background-color: lightgray !important;
}
</style>
