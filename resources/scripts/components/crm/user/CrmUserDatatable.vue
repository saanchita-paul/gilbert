<template>
    <v-container fluid>

        <!--        <v-card class="hood-card">-->
        <!--            <h2>All Application Metrics</h2>-->
        <!--            <LeadMetrics></LeadMetrics>-->
        <!--        </v-card>-->

        <div v-if="isLoaded">
            <v-btn v-if="agency.type === 0"  class="back-button" @click="backToAgency"><v-icon>mdi-arrow-left</v-icon> Back to Agencies</v-btn>
            <v-btn  v-else @click="backToOffice" class="back-button"><v-icon>mdi-arrow-left</v-icon> Back to {{agency.name}} Offices</v-btn>
            <v-card class="hood-card  mt-4">
                <v-row>
                    <v-col cols="8">
                        <span v-if="agency.type === 0" class="office-title">{{`${agency.name}, ${office.name}`}}</span>
                        <span v-else class="office-title">{{office.name}} Office</span>
                    </v-col>
                    <v-col cols="4" class="text-right">
                        <v-btn outlined @click="viewOfficeProfile">View Office Profile</v-btn>
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
                            dense
                            :headers="headers"
                            :items="usersList"
                            :options.sync="options"
                            :server-items-length="totalItem"
                            :loading="loading"
                            class="elevation-1 row-pointer"
                    >

                        <template v-slot:item.first_name="{ item }">
                            <ValidationProvider name="Firstname" rules="required"  v-slot="{ errors }">
                                <v-text-field
                                    class="mt-6"
                                    outlined
                                    dense
                                    placeholder="Firstname"
                                    v-model="item.first_name"
                                    :ref="'inputRefFirstname'+item.id"
                                    @blur="updateUserData(item , 'Firstname')"
                                    :error-messages=" errors[0]"
                                ></v-text-field>
                            </ValidationProvider>
                        </template>
                        <template v-slot:item.last_name="{ item }">
                            <ValidationProvider name="Lastname" rules="required"  v-slot="{ errors }">
                                <v-text-field
                                    class="mt-6"
                                    outlined
                                    dense
                                    placeholder="Lastname"
                                    v-model="item.last_name"
                                    :ref="'inputRefLastname'+item.id"
                                    @blur="updateUserData(item , 'Lastname')"
                                    :error-messages=" errors[0]"
                                ></v-text-field>
                            </ValidationProvider>
                        </template>

                        <template v-slot:item.role="{ item }">
                                <ValidationProvider name="Role" rules="required"  v-slot="{ errors }">
                                    <v-select outlined dense
                                            class="mt-6"
                                            v-model="item.role"
                                            :items="roles.AGENCY"
                                            :error-messages=" errors[0]"
                                            :ref="'inputRefRole'+item.id"
                                            @blur="updateUserData(item , 'Role')"
                                            placeholder="Please Select">
                                    </v-select>
                                </ValidationProvider>
                        
                        </template>

                        <template v-slot:item.phone="{ item }">
                                <ValidationProvider name="Mobile number" rules="required|cv-phone|length:10"  v-slot="{ errors }">
                                    <v-text-field
                                        class="mt-6"
                                        :maxlength="10"
                                        outlined
                                        dense
                                        placeholder="04XX XXX XXX"
                                        v-model="item.phone"
                                        :ref="'inputRefPhone'+item.id"
                                        @blur="updateUserData(item, 'Phone')"
                                        :error-messages=" errors[0]"
                                    ></v-text-field>
                                </ValidationProvider>

                        </template>
                        <template v-slot:item.email="{ item }">
                                <ValidationProvider
                                    name="Email"
                                    rules="required|email|unique-email-update:@h_id"
                                    v-slot="{ errors }"
                                >
                                    <v-text-field
                                        class="mt-6"
                                        outlined
                                        v-model="item.email"
                                        dense
                                        :error-messages=" errors[0]"
                                        :ref="'inputRefEmail'+item.id"
                                        @blur="updateUserData(item, 'Email')"
                                    ></v-text-field>
                                </ValidationProvider>
                        <ValidationProvider name="h_id">
                            <v-text-field v-model="item.id" v-show="false" />
                        </ValidationProvider>
                        </template>
                        <template v-slot:item.action="{ item }">
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-btn
                                            v-bind="attrs"
                                            @click="sendMailToUser(item)"
                                            v-on="on"
                                                icon
                                                >
                                            <v-icon>mdi-send</v-icon>
                                        </v-btn>
                                </template>
                                <span>Invite</span>
                                </v-tooltip>
                        </template>

                    </v-data-table>
                </v-col>
            </v-row>

            <CreateUserModal v-if="isCreateStart" :dialog="isCreatingUser" @goToNext="goToNextConfirmationModal" @cancelUserDialog="cancleUserDialog"></CreateUserModal>
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
    import Roles from '@scripts/data/UserRoles'
    export default {
        name: "CrmUserDatatable",
        components: {UserCreatedSuccessfulModal, UserCreationConfirmationModal, CreateUserModal, Search, LeadMetrics},
        data () {
            return {
                isCreateStart: false,
                isCreatingUser: false,
                dataVerificationFlag: false,
                creationDoneFlag: false,
                user: null,
                usersList: [],
                activeOffice: null,
                roles: Roles,
                
                page: 1,
                pageCount: 0,
                itemsPerPage: 10,
                totalItem: null,
                loading: true,
                options: {},
                headers:  [
                    {
                        text: 'First Name',
                        align: 'start',
                        sortable: true,
                        value: 'first_name'
                    },
                    {
                        text: 'Last Name',
                        align: 'start',
                        value: 'last_name',
                        sortable: true,
                    },
                    {
                        text: 'Applications',
                        align: 'start',
                        value: 'submitted_lead',
                        sortable: true,
                    },
                    {
                        text: 'Role',
                        align: 'start',
                        value: 'role',
                        sortable: true,
                    },
                    {
                        text: 'Mobile',
                        align: 'start',
                        value: 'phone',
                        sortable: false,
                    },
                    {
                        text: 'Email',
                        align: 'start',
                        value: 'email',
                        sortable: false,
                    },
                    {
                        text: 'Action',
                        align: 'start',
                        value: 'action',
                        sortable: false,
                    }
                ],
                search: '',
                agency: '',
                office: '',
                isLoaded: false
            }
        },
        methods: {
            async emailUpdateValidationRule(item){
                if(!item.email) item.errorMsg = 'Email is required'
                else if( !( /.+@.+\..+/.test(item.email) ) ) item.errorMsg = 'E-mail must be valid'
                else item.errorMsg = [];
            },
            addNewUser() {
                this.isCreatingUser= true;
                this.isCreateStart = true;
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

            async checkDataValidation(agency , type){
                
                if(!this.$refs[`inputRef`+type+agency.id]?.hasError){
                    await AgencyService.updateUserData(agency, agency.id);
                }
            },

            async updateUserData(agency , type){
                if(!this.$refs[`inputRef`+type+agency.id]?.hasError) return;
                await AgencyService.updateUserData(agency, agency.id);
            },

            done() {
                this.creationDoneFlag = false
                this.isCreateStart = false;
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
                console.log(data)
                this.usersList = data?.usersAgency;
                console.log(this.usersList)
                this.page = data.pagination.current_page;
                this.itemsPerPage = data.pagination.per_page;
                this.totalItem = data.pagination.total;
                this.loading = false;
            },

            async loadOffice() {
                this.data = await OfficeService.loadOfficeById(this.activeOffice);
                this.office.name = this.data.office.name;
                //console.log(this.data.office.name);
                console.log(this.office.name);
                //await this.syncData();
                this.isLoaded = true;
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
                const officeData  = await OfficeService.loadOfficeById(officeId);
                this.office = officeData.office;
                this.agency = officeData.office.agency;
                this.isLoaded = true;
            }

        },
        async mounted() {
            await this.loadAgencyById();
            await this.loadUserData();
            this.activeOffice = this.$route.params.officeId;
            this.loadOffice();

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
    .office-title{
        font-size: 18px !important;
    font-weight: 700 !important;
    font-family: 'Roboto' !important;
    }
</style>
