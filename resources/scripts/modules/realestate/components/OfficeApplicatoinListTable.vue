<template>
    <div>
        <CrmOfficeListHeader
            dynamicComponent="ApplicatoinListTable"
            @updateSearch="search"
            @changeComponent="changeComponent"
            @openAssignApplicationModal="openAssignApplicationModal"
            :assignApplicationsDisabled="assignApplicationsDisabled"
        >
        </CrmOfficeListHeader>
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
                >
                    <template v-slot:item.services="{ item }">
                        <v-icon
                            small
                            :disabled="
                                isServiceAllowed(item.services, 'power')
                            "
                            color="yellow"
                        >mdi-flash
                        </v-icon
                        >
                        <v-icon
                            small
                            :disabled="
                                isServiceAllowed(item.services, 'gas')
                            "
                            color="red"
                        >mdi-fire
                        </v-icon
                        >
                        <v-icon
                            small
                            :disabled="
                                isServiceAllowed(item.services, 'internet')
                            "
                            color="green"
                        >mdi-wifi
                        </v-icon
                        >
                        <v-icon
                            small
                            :disabled="
                                isServiceAllowed(item.services, 'water')
                            "
                            color="blue"
                        >mdi-water
                        </v-icon
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

                    <!-- remove select all checkbox from header start-->
                    <template class="text-center" v-slot:[`header.data-table-select`]>
                        <v-simple-checkbox
                            v-model="selectAllApplications"
                            @input="selectAllApplicationsHandler"
                            :ripple="false"
                        ></v-simple-checkbox>
                    </template>
                    <!-- remove select all checkbox from header end-->
                    <template v-slot:item.data-table-select="{ item, isSelected, select }">
                        <v-simple-checkbox
                            v-model="item.is_selected"
                            @input="onSelectChange(item)"
                            :ripple="false"
                        ></v-simple-checkbox>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>

        <AssignApplicationsModal v-if="showAssignApplicationModal"
                                 :dialog="showAssignApplicationModal"
                                 :applications="formattedSelectedApplications"
                                 @completeAssignApplications="completeAssignApplications"
                                 @closeModalAssignApplicationModal="closeModalAssignApplicationModal"
        />
    </div>
</template>

<script>
import Search from "@scripts/components/crm/Search";
import AssigneeDropdown from "@scripts/components/crm/leadmanagement/AssigneeDropdown";
import AssignedtoPopUp from "@scripts/components/crm/leadmanagement/AssignedtoPopUp";
import ReassignModal from "@scripts/components/crm/modals/ReassignModal";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import AuthService from "@scripts/services/AuthService";
import {LeadSearchFilterModel} from "@scripts/models/LeadSearchFilterModel";
import {leadSourceMapFromNumber} from "@scripts/data/LeadSourceMap";
import CrmOfficeListHeader from "@scripts/modules/realestate/components/CrmOfficeListHeader";
import AssignApplicationsModal from "@scripts/components/crm/modals/assign-application/AssignApplicationsModal";

export default {
    name: "OfficeApplicatoinListTable",
    components: {
        CrmOfficeListHeader,
        ReassignModal,
        Search,
        AssigneeDropdown,
        AssignedtoPopUp,
        AssignApplicationsModal
    },

    props: {
        officeId: {required: true}
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
                    text: "Creation date",
                    align: "start",
                    sortable: true,
                    value: "created_at"
                },
                {
                    text: "Created By",
                    align: "start",
                    sortable: true,
                    value: "created_by"
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
                    value: "application_status"
                },
                {
                    text: '',
                    value: 'data-table-select',
                    sortable: false
                }
            ],
            applications: [],
            selectedApplications: [],
            totalItem: 0,
            advanceSearch: new LeadSearchFilterModel(),
            showAssignApplicationModal: false,
            selectAllApplications: false,
        };
    },
    computed: {
        leadSourceMapFromNumber() {
            return leadSourceMapFromNumber;
        },
        // Format selected applications to showing into assign application modal
        formattedSelectedApplications() {
            return this.selectedApplications.map(application => {
                return {
                    id: application.id,
                    tenant_name: application.first_name + ' ' + application.last_name,
                    address_text: application.address_text,
                    agency_office: application.agency_office,
                    agent_name: application.agent_name,
                    office_id: '',
                    created_by: '',
                    is_selected: false,
                }
            });
        },
        // Assign office & agent button disabled by condition
        assignApplicationsDisabled() {
            return this.selectedApplications.length <= 0;
        }
    },
    methods: {
        tenantName(item) {
            return item.first_name + " " + item.last_name;
        },

        async loadLeads(meta) {
            this.loading = true;
            let data = await LeadApplicationService.loadUserLeadsForAgents(
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
                per_page: this.options.itemsPerPage === -1 ? this.totalItem : this.options.itemsPerPage,
                is_descending:
                    this.options.sortDesc.length != 0
                        ? this.options.sortDesc[0]
                        : false,
                sort_by:
                    this.options.sortBy.length != 0
                        ? this.options.sortBy[0]
                        : "",
            };
            this.loadLeads(meta);
        },

        changeComponent(name) {

            this.$router.push({
                name: 'real.state.agency.users',
                params: {id: this.$route.params.id, office_id: this.$route.params.officeId},
                query: {type: name}
            })
            this.$emit("changeComponent", name);
        },

        search(searchText) {
            this.advanceSearch.tenant_name = searchText;
            this.advanceSearch.office_id = this.officeId;
            this.loadLeadList();
        },
        // Open assign application modal
        openAssignApplicationModal() {
            this.showAssignApplicationModal = true;
        },
        // Close assign application modal
        closeModalAssignApplicationModal() {
            this.showAssignApplicationModal = false;
        },
        // After complete assign application reset data and refresh component
        completeAssignApplications() {
            this.selectAllApplications = false;
            this.selectedApplications = [];
            this.loadLeadList();
            this.$emit('reloadComponent');
        },
        // Single application select handler
        onSelectChange(item) {
            let index = this.selectedApplications.findIndex(dt => dt.id === item.id);
            if (index === -1) {
                this.selectedApplications.push(item);
            } else {
                this.selectedApplications.splice(index, 1);
            }
            this.selectAllApplications = this.selectedApplications.length === this.applications.length;
        },
        // Select all applications handler
        selectAllApplicationsHandler() {
            this.applications.forEach(application => {
                application.is_selected = this.selectAllApplications;
            });
            this.selectedApplications = this.selectedApplications.length === this.applications.length ? [] : [...this.applications];
        },
        // Check if application is selected
        isSelectedClass(item) {
            return item.is_selected ? 'selectedRow' : '';
        },
    },
    mounted() {
        this.currentUser = AuthService.getAuthUser();
        this.advanceSearch.office_id = this.officeId;
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

<style scoped>
.selectedRow {
    background-color: lightgray !important;
}

.crm-table thead tr th {
    font-size: 3em !important;
}

.crm-table tr td {
    font-size: 5.4em !important;
}


</style>
