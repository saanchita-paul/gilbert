<template>
    <div>
        <CrmOfficeListHeader dynamicComponent="AgentListTable"
                             :edit-mode="editMode"
                             :selected="selected"
                             @changeEditMode="changeEditMode"
                             @addNewUser="addNewUser"
                             @changeComponent="changeComponent"
        > </CrmOfficeListHeader>
        <v-row>
            <v-col cols="12" class="crm-table">
                <v-data-table
                    dense
                    :headers="headers"
                    :items="usersList"
                    :options.sync="options"
                    :server-items-length="totalItem"
                    :loading="loading"
                    class="elevation-1"
                    v-model="selected"
                    show-select
                >
                    <template v-slot:item.first_name="{ item }">
                        <ValidationProvider v-if="editMode" name="Firstname" rules="required"  v-slot="{ errors }">
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
                        <p v-else class="mt-3">{{ item.first_name }}</p>
                    </template>

                    <template v-slot:item.last_name="{ item }">
                        <ValidationProvider v-if="editMode" name="Lastname" rules="required"  v-slot="{ errors }">
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
                        <p v-else class="mt-3">{{ item.last_name }}</p>
                    </template>

                    <template v-slot:item.role="{ item }">
                        <ValidationProvider v-if="editMode" name="Role" rules="required"  v-slot="{ errors }">
                            <v-select outlined dense
                                class="mt-6"
                                v-model="item.role"
                                :items="roles.AGENCY"
                                :error-messages=" errors[0]"
                                :ref="'inputRefRole'+item.id"
                                @blur="updateUserData(item , 'Role')"
                                placeholder="Please select an role">
                            </v-select>
                        </ValidationProvider>
                        <p v-else class="mt-3">{{ item.role | filterRole }}</p>
                    </template>

                    <template v-slot:item.submitted_lead="{ item }">
                        <p class="mt-3">{{ item.submitted_lead }}</p>
                    </template>

                    <template v-slot:item.last_submitted="{ item }">
                        <p class="mt-3">{{ item.last_submitted }}</p>
                    </template>

                    <template v-slot:item.email="{ item }">
                        <ValidationProvider
                            v-if="editMode"
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
                        <p v-else class="mt-3">{{ item.email }}</p>
                        <ValidationProvider name="h_id">
                            <v-text-field v-model="item.id" v-show="false" />
                        </ValidationProvider>
                    </template>

                    <template v-slot:item.phone="{ item }">
                        <ValidationProvider v-if="editMode" name="Mobile number" rules="required|cv-phone|length:10"  v-slot="{ errors }">
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
                        <p v-else class="mt-3">{{ item.phone }}</p>
                    </template>

                    <template v-slot:item.cvr="{ item }">
                        <p class="mt-3">{{ item.conversion_rate }}%</p>
                    </template>

                    <template v-slot:item.visa="{ item }">
                        <ValidationProvider v-if="editMode" name="Visa" rules="required"  v-slot="{ errors }">
                            <v-select outlined dense
                                class="mt-6"
                                v-model="item.visa"
                                :items="visaItems"
                                :error-messages=" errors[0]"
                                :ref="'inputRefVisa'+item.id"
                                @blur="updateUserData(item , 'Visa')"
                                placeholder="Please Select">
                            </v-select>
                        </ValidationProvider>
                        <p v-else class="mt-3">{{ item.visa === 1 ? 'Yes' : 'No' }}</p>
                    </template>

                    <template v-slot:item.active="{ item }">
                        <v-switch
                            v-model="item.is_active"
                            @change="updateUserData(item , 'Active')">
                        </v-switch>
                    </template>

                    <template v-slot:item.action="{ item }">
                        <v-tooltip bottom>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn
                                    v-bind="attrs"
                                    @click="sendMailToUser(item)"
                                    :loading="isLoading(item)"
                                    v-on="on" icon
                                >
                                    <v-icon color="primary">mdi-send</v-icon>
                                </v-btn>
                            </template>
                            <span>Invite</span>
                        </v-tooltip>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
    </div>
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
    import AppMatric from "@scripts/modules/realestate/components/AppMatric";
    import CrmOfficeListHeader from "@scripts/modules/realestate/components/CrmOfficeListHeader";
    export default {
        name: "AgentListTable",
        components: {
            CrmOfficeListHeader,
            AppMatric,
            UserCreatedSuccessfulModal,
            UserCreationConfirmationModal,
            CreateUserModal,
            Search,
            LeadMetrics
        },
        data () {
            return {
                matrics : {
                    value: 1000,
                    title: 'Application Created'
                },
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
                        text: 'Role',
                        align: 'start',
                        value: 'role',
                        sortable: true,
                    },
                    {
                        text: 'Apps',
                        align: 'start',
                        value: 'submitted_lead',
                        sortable: false,
                    },
                    {
                        text: 'Last Submitted',
                        align: 'start',
                        value: 'last_submitted',
                        sortable: false,
                    },
                    {
                        text: 'Email',
                        align: 'start',
                        value: 'email',
                        sortable: false,
                    },
                    {
                        text: 'Contact',
                        align: 'start',
                        value: 'phone',
                        sortable: false,
                    },
                    {
                        text: 'CVR%',
                        align: 'start',
                        value: 'cvr',
                        sortable: false,
                    },
                    {
                        text: 'Visa',
                        align: 'start',
                        value: 'visa',
                        sortable: false,
                    },
                    {
                        text: 'Active',
                        align: 'start',
                        value: 'active',
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
                isLoaded: false,
                loadingEmail: [],
                snackbar: false,
                timeout: 2000,
                visaItems: [
                    { text: 'Yes', value: 1 },
                    { text: 'No', value: 0 },
                ],
                selected: [],
                editMode: false,
            }
        },
        methods: {
            async emailUpdateValidationRule(item){
                if(!item.email) item.errorMsg = 'Email is required'
                else if( !( /.+@.+\..+/.test(item.email) ) ) item.errorMsg = 'E-mail must be valid'
                else item.errorMsg = [];
            },

            addNewUser() {
                this.$emit('createAgent');
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
                if(this.$refs[`inputRef`+type+agency.id]?.hasError) return;
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
                this.usersList = data?.usersAgency;
                this.page = data.pagination.current_page;
                this.itemsPerPage = data.pagination.per_page;
                this.totalItem = data.pagination.total;
                this.loading = false;
            },

            async loadOffice() {
                this.data = await OfficeService.loadOfficeById(this.activeOffice);
                this.office.name = this.data.office.name;
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
                this.$router.push({
                    name:'real.state.office.profile',
                    params: {'id': agencyId, 'officeId': officeId}}
                );
            },

            backToOffice() {
                let officeId = this.$route.params?.officeId;
                let agencyId = this.$route.params?.id;
                this.$router.push({
                    name:'real.state.agency.office',
                    params: {'id': agencyId}}
                );
            },

            backToAgency() {
                let agencyId = this.$route.params?.id;
                this.$router.push({
                    name:'real.state.agency.home'}
                );
            },

            async loadAgencyById() {
                let agencyId = this.$route.params?.id;
                let officeId = this.$route.params?.officeId;
                this.agency = await AgencyService.loadAgencyById(agencyId);
                const officeData  = await OfficeService.loadOfficeById(officeId);
                this.office = officeData.office;
                this.agency = officeData.office.agency;
                this.isLoaded = true;
            },

            async sendMailToUser(item) {
                this.loadingEmail.push(item.id);
                const index = this.loadingEmail.indexOf(item.id);
                const response = await AgencyService.sendMail(item);
                this.loadingEmail.splice(index, 1);
                this.snackbar = true;
            },

            isLoading(item) {
                if(this.loadingEmail.includes(item.id))
                    return true;
                return false;
            },
            changeEditMode()
            {
                this.editMode = !this.editMode;
            },

            changeComponent(name) {
                this.$emit('changeComponent', name);
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
            selected: {
                handler () {
                    this.$emit(
                        'changeAgentCount',
                        this.selected.length
                    );
                },
                deep: true,
            },
        },
        filters: {
            filterRole: function (value) {
                const role = Roles.AGENCY.find(item => item.value === value);
                return role?.text;
            }
        }
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
