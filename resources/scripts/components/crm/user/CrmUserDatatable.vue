<template>
    <v-container fluid>

        <!--        <v-card class="hood-card">-->
        <!--            <h2>All Application Metrics</h2>-->
        <!--            <LeadMetrics></LeadMetrics>-->
        <!--        </v-card>-->

        <div v-if="isLoaded">
<!--            <v-btn v-if="agency.type === 0"  class="back-button" @click="backToAgency"><v-icon>mdi-arrow-left</v-icon> Back to Agencies</v-btn>-->
<!--            <v-btn  v-else @click="backToOffice" class="back-button"><v-icon>mdi-arrow-left</v-icon> Back to {{agency.name}} Offices</v-btn>-->
<!--            <v-card class="hood-card  mt-4">-->
<!--                <v-row>-->
<!--                    <v-col cols="8">-->
<!--                        <span v-if="agency.type === 0" class="office-title">{{`${agency.name}, ${office.name}`}}</span>-->
<!--                        <span v-else class="office-title">{{office.name}} Office</span>-->
<!--                    </v-col>-->
<!--                    <v-col cols="4" class="text-right">-->
<!--                        <v-btn outlined @click="viewOfficeProfile">View Office Profile</v-btn>-->
<!--                    </v-col>-->
<!--                </v-row>-->
<!--            </v-card>-->

            <REAMatrics :agency="agency" :matrics="matrics" :office="office">

            </REAMatrics>



            <!-- <v-row class="mt-5">
                <v-col cols="4" class="search-bg">
                    <Search @updateSearch="updateSearch"></Search>
                </v-col>
                <v-col cols="4" class="text-right">
                    <v-btn color="primary" @click="addNewUser"><v-icon left>add</v-icon> Add New User</v-btn>
                </v-col>
            </v-row> -->

        <div class="d-flex justifyBetween">
            <div class="d-flex my-2">
                <div style="flex-basis: 40%;">
                    <Search @updateSearch="updateSearch"></Search>
                </div>

                <div class="mx-2 buttonLarge">
                    <v-btn @click="changeComponent('ApplicatoinListTable')" :class="getButtonClass('ApplicatoinListTable')"> Performance Operation </v-btn>
                </div>

                <div class="mx-2 buttonLarge">
                    <v-btn @click="changeComponent('AgentListTable')" :class="getButtonClass('AgentListTable')"> Backend of agency </v-btn>
                </div>
            </div>
            <div v-if="dynamicComponent === 'AgentListTable'" class="d-flex my-2">
                <v-btn class="mr-4" @click="setEditMode">
                    {{ editMode ? 'Cancel Edit' : 'Edit Staff' }}
                </v-btn>
                <v-btn color="primary" @click="addNewUser">
                    <v-icon left>add</v-icon> Add New Staff
                </v-btn>
            </div>
        </div>
        <!-- search button starts -->

        <!-- search button ends -->


        <!-- dynamic components starts -->
            <keep-alive>
                <component :is="dynamicComponent" :officeId="activeOffice" :editMode="editMode" ></component>
            </keep-alive>
        <!-- dynamic component ends -->

            <CreateUserModal v-if="isCreateStart" :dialog="isCreatingUser" @goToNext="goToNextConfirmationModal" @cancelUserDialog="cancleUserDialog"></CreateUserModal>
            <UserCreationConfirmationModal v-if="dataVerificationFlag" :dialog="dataVerificationFlag" :user="user" @backToEdit="backToEdit" @confirmData="confirmedData"></UserCreationConfirmationModal>
            <UserCreatedSuccessfulModal v-if="creationDoneFlag" :dialog="creationDoneFlag" :user="user" @done="done"></UserCreatedSuccessfulModal>
            <v-snackbar
                v-model="snackbar"
                :timeout="timeout"
                right
            >
                {{ 'Invitation Mail Sent' }}

                <template v-slot:action="{ attrs }">
                    <v-btn
                        color="red"
                        text
                        v-bind="attrs"
                        @click="snackbar = false"
                    >
                        Close
                    </v-btn>
                </template>
            </v-snackbar>
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
    import AppMatric from "@scripts/modules/realestate/components/AppMatric";
    import AgentListTable from "@scripts/modules/realestate/components/AgentListTable";
    import ApplicatoinListTable from "@scripts/modules/realestate/components/OfficeApplicatoinListTable";
    import REAMatrics from "@scripts/modules/realestate/components/REAMatrics";
    import MartricServices from "@scripts/modules/realestate/services/MartricServices";
    export default {
        name: "CrmUserDatatable",
        components: {
            REAMatrics,
            ApplicatoinListTable,
            AgentListTable,
            AppMatric,
            UserCreatedSuccessfulModal, UserCreationConfirmationModal, CreateUserModal, Search, LeadMetrics},
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
                editMode: false,
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
                isLoaded: false,
                loadingEmail: [],
                snackbar: false,
                timeout: 2000,
                dynamicComponent: "AgentListTable",
                matrics: null,
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
                if(this.$refs[`inputRef`+type+agency.id]?.hasError) return;
                await AgencyService.updateUserData(agency, agency.id);
            },

            done() {
                this.creationDoneFlag = false
                this.isCreateStart = false;
            },

            async loadUserData() {
                // const meta = {
                //     search: this.search,
                //     page: this.options.page,
                //     per_page: this.options.itemsPerPage,
                //     is_descending: this.options.sortDesc.length != 0? this.options.sortDesc[0]: false,
                //     sort_by: this.options.sortBy.length != 0? this.options.sortBy[0]: '',
                // }
                // const data = await CrmUserService.loadUserData(meta, this.$route.params.id, this.$route.params.officeId);
                // console.log(data)
                // this.usersList = data?.usersAgency;
                // console.log(this.usersList)
                // this.page = data.pagination.current_page;
                // this.itemsPerPage = data.pagination.per_page;
                // this.totalItem = data.pagination.total;
                // this.loading = false;
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
            },

          async sendMailToUser(item) {
                this.loadingEmail.push(item.id);
                const index = this.loadingEmail.indexOf(item.id);
                const response = await  AgencyService.sendMail(item);
                this.loadingEmail.splice(index,1);
                this.snackbar = true;
            },

            isLoading(item) {
                if(this.loadingEmail.includes(item.id))
                    return true;
                return false;
            },
            changeComponent(name){
                this.dynamicComponent = name;
            },
            getButtonClass(name){
                 return this.dynamicComponent === name ? 'buttonActive' : 'buttonInactive';
            },
            setEditMode(){
                this.editMode = !this.editMode;
            },
            async getREAMatrics()
            {
                let office_id = this.$route.params.officeId;
                this.matrics = await MartricServices.getMatrics(office_id)
            }
        },
        async mounted() {
            await this.getREAMatrics();
            await this.loadAgencyById();
            await this.loadUserData();
            this.activeOffice = this.$route.params.officeId;
            this.loadOffice();



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

    .buttonActive{
        background: #DDE2FF;
        color:  #542E89;
    }

    .buttonInactive{
        background: #C0C3C4;;
        color: #263238;
    }
    .matrics{
        display: flex;
        flex-direction: column;
    }
    .matrics-title {
        font-size: 1.5em;
        color: #542E89;
        font-weight: 700;
    }
    .matrics-subtitle{
        font-size:  0.75em;
        font-weight: normal;
        color: #7E8A8F;
    }
    .service {
        font-size: 0.875em;
        font-weight: 700;
    }
    .matrics-header {
        font-size: 1em;
        font-weight: 700;
    }
    .justifyBetween{
        justify-content: space-between !important;
    }
    .buttonLarge{
        width: 200px !important;
    }

</style>
