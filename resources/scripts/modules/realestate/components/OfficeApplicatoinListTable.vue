<template>
    <div>
        <CrmOfficeListHeader
            dynamicComponent="ApplicatoinListTable"
            @updateSearch="search"
            @changeComponent="changeComponent"
        >
        </CrmOfficeListHeader>
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
                    >
                        <template v-slot:item.services="{ item }">
                            <v-icon
                                small
                                :disabled="
                                    isServiceAllowed(item.services, 'power')
                                "
                                color="yellow"
                                >mdi-flash</v-icon
                            >
                            <v-icon
                                small
                                :disabled="
                                    isServiceAllowed(item.services, 'gas')
                                "
                                color="red"
                                >mdi-fire</v-icon
                            >
                            <v-icon
                                small
                                :disabled="
                                    isServiceAllowed(item.services, 'internet')
                                "
                                color="green"
                                >mdi-wifi</v-icon
                            >
                            <v-icon
                                small
                                :disabled="
                                    isServiceAllowed(item.services, 'water')
                                "
                                color="blue"
                                >mdi-water</v-icon
                            >
                        </template>
                        <template v-slot:item.source="{ item }">
                            <div
                                v-text="leadSourceMapFromNumber[item.source]"
                            ></div>
                        </template>
                        <template v-slot:item.tenant_name="{ item }">
                            <div v-text="tenantName(item)"></div>
                        </template>
                    </v-data-table>
                </v-col>
            </v-row>
        </v-card>
    </div>
</template>

<script>
import Search from "@scripts/components/crm/Search";
import AssigneeDropdown from "@scripts/components/crm/leadmanagement/AssigneeDropdown";
import AssignedtoPopUp from "@scripts/components/crm/leadmanagement/AssignedtoPopUp";
import ReassignModal from "@scripts/components/crm/modals/ReassignModal";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import AuthService from "@scripts/services/AuthService";
import { LeadSearchFilterModel } from "@scripts/models/LeadSearchFilterModel";
import { leadSourceMapFromNumber } from "@scripts/data/LeadSourceMap";
import CrmOfficeListHeader from "@scripts/modules/realestate/components/CrmOfficeListHeader";
export default {
    name: "OfficeApplicatoinListTable",
    components: {
        CrmOfficeListHeader,
        ReassignModal,
        Search,
        AssigneeDropdown,
        AssignedtoPopUp
    },

    props: {
        officeId: { required: true }
    },

    data() {
        return {
            currentUser: null,
            leadSearch: "",
            options: {},
            loading: false,
            page: 1,
            itemsPerPage: 10,
            headers: [
                {
                    text: "Tenant Name",
                    align: "start",
                    sortable: true,
                    value: "tenant_name"
                },
                {
                    text: "Property Address",
                    align: "start",
                    sortable: true,
                    value: "address_text"
                },
                {
                    text: "Connection Date",
                    align: "start",
                    sortable: true,
                    value: "moving_date"
                },
                {
                    text: "Submission Date",
                    align: "start",
                    sortable: true,
                    value: "submitted_at"
                },
                {
                    text: "Submitted By",
                    align: "start",
                    sortable: true,
                    value: "submitted_by"
                },
                {
                    text: "Created At",
                    align: "start",
                    sortable: true,
                    value: "created_at"
                },
                {
                    text: "Source",
                    align: "start",
                    sortable: true,
                    value: "source"
                },
                {
                    text: "Services",
                    align: "start",
                    sortable: true,
                    value: "services"
                },
                {
                    text: "Status",
                    align: "start",
                    sortable: true,
                    value: "status"
                }
            ],
            applications: [],
            totalItem: 0,
            advanceSearch: new LeadSearchFilterModel()
        };
    },
    computed: {
        leadSourceMapFromNumber() {
            return leadSourceMapFromNumber;
        }
    },
    methods: {
        tenantName(item) {
            return item.first_name + " " + item.last_name;
        },
        
        async loadLeads(meta) {
            this.loading = true;
            let data = await LeadApplicationService.loadUserLeads(
                meta,
                "",
                "",
                this.advanceSearch
            );
            this.loading = false;
            this.applications = data.applications;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
        },
        isServiceAllowed(services, type) {
            return !services.includes(type);
        },

        loadLeadList() {
            const meta = {
                search: this.leadSearch,
                page: this.options.page,
                per_page: this.options.itemsPerPage,
                is_descending:
                    this.options.sortDesc.length != 0
                        ? this.options.sortDesc[0]
                        : false,
                sort_by:
                    this.options.sortBy.length != 0
                        ? this.options.sortBy[0]
                        : "",
                ...new LeadSearchFilterModel({ office_id: this.officeId })
            };
            this.loadLeads(meta);
        },

        changeComponent(name) {
            this.$emit("changeComponent", name);
        },

        search(searchText) {
            this.advanceSearch.tenancy_name = searchText;
            this.loadLeadList();
        }
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
            deep: true
        }
    }
};
</script>

<style>
.selectedRow {
    background-color: lightgray !important;
}
</style>
