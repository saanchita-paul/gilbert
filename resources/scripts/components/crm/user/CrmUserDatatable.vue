<template>
    <div>
        <v-row class="mt-5">
            <v-col cols="8" class="search-bg">
                <Search></Search>
            </v-col>
            <v-col cols="4" class="text-right">
                <v-btn color="primary" @click="addNewUser"><v-icon left>add</v-icon> Add New User</v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" class="crm-table">
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                        <tr>
                            <th class="text-left">
                                Property Manager Name
                            </th>
                            <th class="text-left">
                                Submitted Lead
                            </th>
                            <th class="text-left">
                                Role
                            </th>
                            <th class="text-left">
                                Mobile
                            </th>
                            <th class="text-left">
                               Email
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="item in  crmUsers"
                            :key="item.name"
                        >
                            <td>{{ item.proerty_manager_name }}</td>
                            <td>{{ item.submitted_lead }}</td>
                            <td>{{ item.role }}</td>
                            <td>{{ item.mobile }}</td>
                            <td>{{ item.email }}</td>
                        </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </v-col>
        </v-row>
        <CreateUserModal :dialog="isCreatingUser" @goToNext="goToNextConfirmationModal" @cancelUserDialog="cancleUserDialog"></CreateUserModal>
        <UserCreationConfirmationModal :dialog="dataVerificationFlag" :user="user" @backToEdit="backToEdit" @confirmData="confirmedData"></UserCreationConfirmationModal>
        <UserCreatedSuccessfulModal :dialog="creationDoneFlag" :user="user" @done="done"></UserCreatedSuccessfulModal>
    </div>
</template>
<script>
import Search from "@scripts/components/crm/Search";
import CreateUserModal from "@scripts/components/crm/modals/CreateUserModal";
import UserCreationConfirmationModal from "@scripts/components/crm/modals/UserCreationConfirmationModal";
import UserCreatedSuccessfulModal from "@scripts/components/crm/modals/UserCreatedSuccessfulModal";
import CrmUserService from "@scripts/services/crm/CrmUserService";
export default {
name: "CrmUserDatatable",
    components: {UserCreatedSuccessfulModal, UserCreationConfirmationModal, CreateUserModal, Search},
    data () {
        return {
            isCreatingUser: false,
            dataVerificationFlag: false,
            creationDoneFlag: false,
            user: null,
            crmUsers: [],
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

        confirmedData() {
            this.dataVerificationFlag = false;
            this.creationDoneFlag = true;
            this.saveUser();

        },

        done() {
            this.creationDoneFlag = false
        },

        loadUserData()
        {
           this.crmUsers = CrmUserService.loadUserData();
        },

        saveUser() {
            let officeId = this.$route.params?.id;
          let newUser = CrmUserService.saveUser(this.user, officeId);
          this.crmUsers.push(newUser);
        }
    },
    mounted() {
        this.loadUserData();
    }
}
</script>

<style scoped>

</style>
