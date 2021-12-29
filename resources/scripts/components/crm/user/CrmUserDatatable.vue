<template>
    <v-container fluid>
        <div v-if="isLoaded">
            <REAMatrics
                :office="office"
                :agency="agency"
                :matrics="matrics"
            ></REAMatrics>
            <keep-alive>
                <component
                    :is="dynamicComponent"
                    :officeId="activeOffice"
                    @createAgent="addNewUser"
                    @changeComponent="changeComponent"
                >
                </component>
            </keep-alive>
            <CreateUserModal
                v-if="isCreateStart"
                :dialog="isCreatingUser"
                @goToNext="goToNextConfirmationModal"
                @cancelUserDialog="cancleUserDialog"
            ></CreateUserModal>
            <UserCreationConfirmationModal
                v-if="dataVerificationFlag"
                :dialog="dataVerificationFlag"
                :user="user"
                @backToEdit="backToEdit"
                @confirmData="confirmedData"
            ></UserCreationConfirmationModal>
            <UserCreatedSuccessfulModal
                v-if="creationDoneFlag"
                :dialog="creationDoneFlag"
                :user="user"
                @done="done"
            ></UserCreatedSuccessfulModal>
            <v-snackbar v-model="snackbar" :timeout="timeout" right>
                {{ "Invitation Mail Sent" }}
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
import Roles from "@scripts/data/UserRoles";
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
        UserCreatedSuccessfulModal,
        UserCreationConfirmationModal,
        CreateUserModal,
        Search,
        LeadMetrics
    },
    data() {
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
            search: "",
            agency: "",
            office: "",
            isLoaded: false,
            loadingEmail: [],
            snackbar: false,
            timeout: 2000,
            dynamicComponent: "ApplicatoinListTable",
            matrics: null
        };
    },
    methods: {
        async emailUpdateValidationRule(item) {
            if (!item.email) item.errorMsg = "Email is required";
            else if (!/.+@.+\..+/.test(item.email))
                item.errorMsg = "E-mail must be valid";
            else item.errorMsg = [];
        },

        addNewUser() {
            this.isCreatingUser = true;
            this.isCreateStart = true;
        },

        cancleUserDialog() {
            this.isCreatingUser = false;
        },

        goToNextConfirmationModal(user) {
            this.isCreatingUser = false;
            this.dataVerificationFlag = true;
            this.user = user;
        },

        backToEdit() {
            this.isCreatingUser = true;
            this.dataVerificationFlag = false;
        },

        async confirmedData() {
            await this.saveUser();
            this.dataVerificationFlag = false;
            this.creationDoneFlag = true;
        },

        async checkDataValidation(agency, type) {
            if (!this.$refs[`inputRef` + type + agency.id]?.hasError) {
                await AgencyService.updateUserData(agency, agency.id);
            }
        },

        done() {
            this.creationDoneFlag = false;
            this.isCreateStart = false;
        },

        async loadUserData() {},

        async loadOffice() {
            this.data = await OfficeService.loadOfficeById(this.activeOffice);
            this.isLoaded = true;
        },

        async saveUser() {
            let officeId = this.$route.params?.officeId;
            await CrmUserService.saveUser(this.user, officeId);
            this.loadUserData();
        },

        async loadAgencyById() {
            let agencyId = this.$route.params?.id;
            let officeId = this.$route.params?.officeId;
            this.agency = await AgencyService.loadAgencyById(agencyId);
            const officeData = await OfficeService.loadOfficeById(officeId);
            this.office = officeData.office;
            this.agency = officeData.office.agency;
            this.isLoaded = true;
        },

        changeComponent(name) {
            this.dynamicComponent = name;
        },

        async getREAMatrics() {
            let office_id = this.$route.params.officeId;
            this.matrics = await MartricServices.getMatrics(office_id);
        },

        getActiveComponent(type) {
            if(type === 'user')
            {
                this.dynamicComponent = 'AgentListTable';
            }
            else {
                this.dynamicComponent = 'ApplicatoinListTable';
            }
        }
    },

    watch: {
        '$route': {
            handler() {

                this.getActiveComponent(this.$route.query?.type);
            }
        }
    },

    async mounted() {
        await this.getREAMatrics();
        await this.loadAgencyById();
        await this.loadUserData();
        this.activeOffice = this.$route.params.officeId;
        this.loadOffice();
        this.getActiveComponent();


    }
};
</script>

<style scoped>
.row-pointer >>> tbody tr :hover {
    cursor: pointer;
}
.back-button {
    background: #e0e0e0 !important;
}
.office-title {
    font-size: 18px !important;
    font-weight: 700 !important;
    font-family: "Roboto" !important;
}
.matrics {
    display: flex;
    flex-direction: column;
}
.matrics-title {
    font-size: 1.5em;
    color: #542e89;
    font-weight: 700;
}
.matrics-subtitle {
    font-size: 0.75em;
    font-weight: normal;
    color: #7e8a8f;
}
.service {
    font-size: 0.875em;
    font-weight: 700;
}
.matrics-header {
    font-size: 1em;
    font-weight: 700;
}
.justifyBetween {
    justify-content: space-between !important;
}
.buttonLarge {
    width: 200px !important;
}
</style>
