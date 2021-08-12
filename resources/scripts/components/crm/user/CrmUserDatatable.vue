<template>
    <div>
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
                    class="elevation-1"
                >
                </v-data-table>
            </v-col>
        </v-row>
        <CreateUserModal v-if="isCreatingUser" :dialog="isCreatingUser" @goToNext="goToNextConfirmationModal" @cancelUserDialog="cancleUserDialog"></CreateUserModal>
        <UserCreationConfirmationModal v-if="dataVerificationFlag" :dialog="dataVerificationFlag" :user="user" @backToEdit="backToEdit" @confirmData="confirmedData"></UserCreationConfirmationModal>
        <UserCreatedSuccessfulModal v-if="creationDoneFlag" :dialog="creationDoneFlag" :user="user" @done="done"></UserCreatedSuccessfulModal>
    </div>
</template>
<script>
import Search from "@scripts/components/crm/Search";
import CreateUserModal from "@scripts/components/crm/modals/CreateUserModal";
import UserCreationConfirmationModal from "@scripts/components/crm/modals/UserCreationConfirmationModal";
import UserCreatedSuccessfulModal from "@scripts/components/crm/modals/UserCreatedSuccessfulModal";
import CrmUserService from "@scripts/services/crm/CrmUserService";
import OfficeService from "@scripts/services/crm/OfficeService";
export default {
name: "CrmUserDatatable",
    components: {UserCreatedSuccessfulModal, UserCreationConfirmationModal, CreateUserModal, Search},
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
                sort_by: this.options.sortBy.length != 0? this.options.sortBy[0]: 'first_name',
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
    },
    mounted() {
        this.loadUserData();
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

</style>
