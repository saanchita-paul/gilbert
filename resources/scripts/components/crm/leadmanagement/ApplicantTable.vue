<template>
    <div>
        <v-row class="mt-5">
            <v-col cols="8" class="search-bg">
                <Search @updateSearch="updateLeadSearch"></Search>
            </v-col>
        </v-row>
        <v-card class="pa-4">
            <v-row>
                <v-col cols="12" class="crm-table">
                    <v-data-table
                        :headers="headers"
                        :items="applications"
                        :item-class="isSelectedClass"
                        :options.sync="options"
                        :server-items-length="totalItem"
                        :loading="loading"
                        class="elevation-1 row-pointer"
                        @click:row="openLeadSummary"
                    >
                        <template v-slot:item.first_name="{ item }">
                            {{ item.first_name + ' ' + item.last_name }}
                        </template>
                        <template v-slot:item.services="{ item }">
                            <v-icon small  :disabled="isServiceAllowed(item.services, 'power')" color="yellow">mdi-flash</v-icon>
                            <v-icon small :disabled="isServiceAllowed(item.services, 'gas')" color="red">mdi-fire</v-icon>
                            <v-icon small  :disabled="isServiceAllowed(item.services, 'internet')" color="green">mdi-wifi</v-icon>
                            <v-icon small :disabled="isServiceAllowed(item.services, 'water')" color="blue" >mdi-water</v-icon>
                        </template>
                        <template v-slot:item.assignee="{ item }">
                            <AssigneeDropdown
                                :lead="item" :users="users"
                                @assignUser="assignUser" @updateSearch="updateUserSearch">
                            </AssigneeDropdown>
                        </template>
                    </v-data-table>
                </v-col>
            </v-row>
            <ReassignModal
                :dialog="reassignFlag" :user="selectedUser"  :assignedText="assignedText"
                :lead="selectedLead" @cancelModal="cancelModal">
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

export default {
  name: "ApplicantTable",
  components: {
      ReassignModal,
        Search,
        AssigneeDropdown,
        AssignedtoPopUp
    },

    props: {
      applications: {
          required: true
      },
        totalItem: {
          required: true
      },
        currentLead: {
          required: true
      }
    },

    data() {
        return {
            isActive: true,
            users: null,
            reassignFlag: false,
            selectedLead: null,
            selectedUser: null,
            assignedText: null,

            userSearch: '',
            leadSearch: '',
            options: {},
            loading: false,
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalUserItem: null,
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
                    text: 'Service Type',
                    align: 'start',
                    sortable: true,
                    value: 'services'
                },
                {
                    text: 'Assignee',
                    align: 'start',
                    sortable: true,
                    value: 'assignee'
                }
            ],
        }
    },

    methods: {
        isSelectedClass(item) {
            if(item.id === this.currentLead?.id) {
                return 'selectedRow';
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
                sort_by: '',
            }
            const data = await CrmUserService.loadAllUser(meta);
            console.log('users', data);
            this.users = data?.users;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalUserItem = data.pagination.total;
        },

        async assignUser(user, lead, assignedText)
        {
            console.log(user, lead);
            this.selectedLead = lead;
            this.selectedUser = user;
            this.assignedText = assignedText;
            await LeadApplicationService.assignUser(lead.id, user.id)
                .then(res =>  {
                    console.log('Assigned Successfully');
                    this.loadLeadList();
                    this.reassignFlag = true;
                })
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
                is_descending: this.options.sortDesc.length != 0? this.options.sortDesc[0]: false,
                sort_by: this.options.sortBy.length != 0? this.options.sortBy[0]: '',
            }
            this.$emit('refreshDataTable',meta);
        }
    },
    mounted() {
      this.loadUserList();
    },
    watch: {
        options: {
            handler () {
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
