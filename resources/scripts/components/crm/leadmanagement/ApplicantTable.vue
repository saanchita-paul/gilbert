<template>
    <div>
        <!-- <v-row class="mt-5">
            <v-col cols="12" md="4" class="search-bg">
                <Search @updateSearch="updateLeadSearch"></Search>
            </v-col>
            <v-col cols="12" md="4">
                    <v-select
                        placeholder="Select a lead source"
                        v-model="leadSrc"
                        item-text="text"
                        item-value="value"
                        :items="srcOptions"
                        outlined
                        dense
                    >
                        <template v-slot:item="{ item, attrs, on }">
                            <v-list-item
                                link
                                @change="onSrcChange(item.value)"
                                v-bind="attrs"
                                v-on="on"
                            >
                                <v-list-item-avatar>
                                     <v-img :src='item.icon' width="20px"/>
                                </v-list-item-avatar>
                                <v-list-item-content>
                                    <v-list-item-title>{{item.text}}</v-list-item-title>
                                </v-list-item-content>
                            </v-list-item>
                        </template>
                    </v-select>
            </v-col>
        </v-row> -->
        <v-card class="hood-card">
            <v-row>
                <v-col cols="12" class="crm-table">
                    <v-data-table
                        :headers="tableHeader"
                        :items="applications"
                        :item-class="isSelectedClass"
                        :options.sync="options"
                        :server-items-length="totalItem"
                        :loading="isSearching"
                        class="row-pointer"
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
                        <template v-slot:item.source="{ item }">
                            <div
                                v-text="leadSourceMapFromNumber[item.source]"
                            ></div>
                        </template>
                        <template v-slot:item.assignee="{ item }">
                            <AssigneeDropdown v-if="currentUser && users"
                                :lead="item" :users="users" :currentUser="currentUser.profile"
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
import AuthService from "@scripts/services/AuthService";
import {leadSourceMapFromNumber} from "@scripts/data/LeadSourceMap";

export default {
  name: "ApplicantTable",
  components: {
      ReassignModal,
        Search,
        AssigneeDropdown,
        AssignedtoPopUp
    },

    props: {
        leadSrc: {default: 'all'},
        applications: {
            required: true
        },
        totalItem: {
            required: true,
        },
        currentLead: {
            required: true
        },
        isSearching: {
            default: false
        },
      showDuplicates: {
            default: false
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
            currentUser: null,

            userSearch: '',
            leadSearch: '',
            options: {
                itemsPerPage: 10
            },
            loading: false,
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalUserItem: null,
            selectedSrc: this.$route.query.source || 'hood',
            srcOptions: [
                // {text: 'Select a lead', value: '', disabled: true},
                {text: 'All Lead Source', value: 'all', icon: ''},
                {text: 'Hood Agent Portal', value: 'hood', icon: '/assets/images/icons/company/hood.png'},
                {text: 'Hood.AI', value: 'hood_ai', icon: '/assets/images/icons/company/hood.png'},
                {text: 'Foxie CRM', value: 'foxie', icon: '/assets/images/icons/company/foxie.png'},
                {text: 'Ignite ', value: 'ignite', icon: '/assets/images/icons/company/ignite.png'},
                {text: 'Our Property', value: 'our-property', icon: '/assets/images/icons/company/our-property.png'},
                {text: 'PropertyMe ', value: 'property_me', icon: '/assets/images/icons/company/propertyMe.png'},
                {text: 'TApp', value: 't_app', icon: '/assets/images/icons/company/tapp.png'},
                {text: 'MRI', value: 'mri', icon: '/assets/images/icons/company/mri.png'},
            ],
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
                    text: 'Preference',
                    align: 'start',
                    sortable: true,
                    value: 'services'
                },
                {
                    text: 'Created At',
                    align: 'start',
                    sortable: true,
                    value: 'created_at'
                },
                {
                    text: 'Assignee',
                    align: 'start',
                    sortable: true,
                    value: 'assignee'
                }

            ],

          duplicatedHeader: [
            {
              text: 'AppId',
              align: 'start',
              sortable: true,
              value: 'id'
            },
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
              text: 'Lead Resource',
              align: 'start',
              sortable: true,
              value: 'source'
            },
            {
              text: 'Created At',
              align: 'start',
              sortable: true,
              value: 'created_at'
            },
            {
              text: 'Submitted At',
              align: 'start',
              sortable: true,
              value: 'submitted_at'
            },
            {
              text: 'Email Address',
              align: 'start',
              sortable: true,
              value: 'email'
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

  computed: {
    tableHeader() {
      console.log('showDuplicates', this.showDuplicates)
      return this.showDuplicates ? this.duplicatedHeader: this.headers ;
    },
      leadSourceMapFromNumber() {
          return leadSourceMapFromNumber;
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
                per_page: this.options.itemsPerPage,
                is_descending: false,
                sort_by: '',
            }
            const data = await CrmUserService.loadAllUser(meta);
            this.users = data?.users;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalUserItem = data.pagination.total;
        },

        async assignUser(user, lead, assignedText)
        {
            this.selectedLead = lead;
            this.selectedUser = user;
            this.assignedText = assignedText =='Reassign'?'reassigned':'assigned';
            await LeadApplicationService.assignUser(lead.id, user.id)
                .then(res =>  {
                    // this.loadLeadList();
                    this.$emit('updateLeadAndatrics',lead.id,user.id);
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
                per_page: this.options.itemsPerPage === -1 ? this.totalItem : this.options.itemsPerPage,
                is_descending: this.options.sortDesc.length != 0? this.options.sortDesc[0]: false,
                sort_by: this.options.sortBy.length != 0? this.options.sortBy[0]: '',
            }
            this.$emit('refreshDataTable',meta);
        },
        onSrcChange(value) {
            this.$router.push({name: 'applications', query: {...this.$route.query, ...{source: value}}})
        }
    },
    mounted() {
      this.loadUserList();
      this.currentUser = AuthService.getAuthUser();
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
