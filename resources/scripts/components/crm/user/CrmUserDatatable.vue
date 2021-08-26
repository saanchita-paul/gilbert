<template>
    <v-container fluid>

<!--        <v-card class="hood-card">-->
<!--            <h2>All Application Metrics</h2>-->
<!--            <LeadMetrics></LeadMetrics>-->
<!--        </v-card>-->

        <div v-if="isLoaded">
            <v-btn v-if="agency.type == 0"  class="back-button" @click="backToAgency"><v-icon>mdi-arrow-left</v-icon> Back to Agencies</v-btn>
            <v-btn  v-else @click="backToOffice" class="back-button"><v-icon>mdi-arrow-left</v-icon> Back to {{agency.title}} Offices</v-btn>
            <v-card class="hood-card  mt-4">
                <v-row>
                    <v-col cols="8">
                        <span>{{office.name}} Office</span>
                    </v-col>
                    <v-col cols="4" class="text-right">
                        <v-btn color="primary" @click="viewOfficeProfile">View Office Profile</v-btn>
                    </v-col>
                </v-row>
            </v-card>

            <v-row class="mt-5">
                <v-col cols="8" class="search-bg">
                    <Search @updateSearch="updateSearch"></Search>
                </v-col>
                <v-col cols="4" class="text-right">
                    <v-btn color="primary" @click="addNewUser"><v-icon left>add</v-icon> Add New User</v-btn>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" class="crm-table">
                    <v-data-table
                        :headers="headers"
                        :items="usersList"
                        :options.sync="options"
                        :server-items-length="totalItem"
                        :loading="loading"
                        class="elevation-1 row-pointer"
                    >
                    </v-data-table>
                </v-col>
            </v-row>

            <CreateUserModal :dialog="isCreatingUser" @goToNext="goToNextConfirmationModal" @cancelUserDialog="cancleUserDialog"></CreateUserModal>
            <UserCreationConfirmationModal v-if="dataVerificationFlag" :dialog="dataVerificationFlag" :user="user" @backToEdit="backToEdit" @confirmData="confirmedData"></UserCreationConfirmationModal>
            <UserCreatedSuccessfulModal v-if="creationDoneFlag" :dialog="creationDoneFlag" :user="user" @done="done"></UserCreatedSuccessfulModal>
        </div>
    </v-container>

</template>
<script>
import Search from "@scripts/components/crm/Search";
import CreateUserModal from "@scripts/components/crm/modals/CreateUserModal";
import UserCreationConfirmationModal from "@scripts/components/crm/modals/UserCreationConfirmationModal";
import UserCreatedSuccessfulModal from "@scripts/components/crm/modals/UserCreatedSuccessfulModal";
import CrmUserService from "@scripts/services/crm/CrmUserService";
import LeadMetrics from "@scripts/components/crm/LeadMetrics";
import OfficeService from "@scripts/services/crm/OfficeService";
import AgencyService from "@scripts/services/crm/AgencyService";
export default {
name: "CrmUserDatatable",
    components: {UserCreatedSuccessfulModal, UserCreationConfirmationModal, CreateUserModal, Search, LeadMetrics},
    data () {
        return {
            isCreatingUser: false,
            dataVerificationFlag: false,
            creationDoneFlag: false,
            user: null,
            usersList: [],

            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalItem: null,
            loading: true,
            options: {},
            headers:  [
                {
                    text: 'Property Manager Name',
                    align: 'start',
                    sortable: true,
                    value: 'proerty_manager_name'
                },
                {
                    text: 'Submitted Lead',
                    align: 'start',
                    sortable: true,
                    value: 'submitted_lead'
                },
                {
                    text: 'Role',
                    align: 'start',
                    sortable: true,
                    value: 'role'
                },
                {
                    text: 'Mobile',
                    align: 'start',
                    value: 'phone'
                },
                {
                    text: 'Email',
                    align: 'start',
                    value: 'email'
                }
            ],
            search: '',
            agency: '',
            office: '',
            isLoaded: false
        }
    },
    methods: {
        addNewUser() {
            this.isCreatingUser= true;
        },

        cancleUserDialog() {
            this.isCreatingUser= false;
        },

        goToNextConfirmationModal(user) {
            this.isCreatingUser= false;
            this.dataVerificationFlag = true;
            this.user = user;
        },

        backToEdit() {
            this.isCreatingUser= true;
            this.dataVerificationFlag = false;
        },

        async confirmedData() {
            await this.saveUser();
            this.dataVerificationFlag = false;
            this.creationDoneFlag = true;
        },

        done() {
            this.creationDoneFlag = false
        },

        async loadUserData() {
            const meta = {
                search: this.search,
                page: this.options.page,
                per_page: this.options.itemsPerPage,
                is_descending: this.options.sortDesc.length != 0? this.options.sortDesc[0]: false,
                sort_by: this.options.sortBy.length != 0? this.options.sortBy[0]: '',
            }
            const data = await CrmUserService.loadUserData(meta, this.$route.params.id, this.$route.params.officeId);
            this.usersList = data?.users;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
            this.loading = false;
        },

        async saveUser() {
          let officeId = this.$route.params?.officeId;
          await CrmUserService.saveUser(this.user, officeId);
          this.loadUserData();
        },
        updateSearch(search) {
            this.search = search;
            this.loadUserData();
        },
        viewOfficeProfile() {
            let officeId = this.$route.params?.officeId;
            let agencyId = this.$route.params?.id;
            this.$router.push(
                {
                    name:'real.state.office.profile',params: {'id': agencyId, 'officeId': officeId}

                });
        },

        backToOffice() {
            let officeId = this.$route.params?.officeId;
            let agencyId = this.$route.params?.id;
            this.$router.push(
                {
                    name:'real.state.agency.office',params: {'id': agencyId}

                });
        },

        backToAgency() {
            let agencyId = this.$route.params?.id;
            this.$router.push(
                {
                    name:'real.state.agency.home'

                });
        },

        async loadAgencyById() {

            let agencyId = this.$route.params?.id;
            let officeId = this.$route.params?.officeId;
            this.agency = await AgencyService.loadAgencyById(agencyId);
            const officeData  = await OfficeService.loadOfficeById(agencyId);
            this.office = officeData.office;
            this.agency = officeData.office.agency;
            this.isLoaded = true;
            // console.log('office data', p.office.name);
            // console.log(this.agency);
        }



    },
    async mounted() {
        await this.loadAgencyById();
        await this.loadUserData();
    },
    watch: {
        options: {
            handler () {
                this.loadUserData();
            },
            deep: true,
        },
    },
}
</script>

<style scoped>
    .row-pointer >>> tbody tr :hover {
        cursor: pointer;
    }
    .back-button{
        background: #E0E0E0 !important;
    }
</style>
