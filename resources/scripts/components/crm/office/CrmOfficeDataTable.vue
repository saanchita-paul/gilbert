<template>
    <v-container fluid>
       
        <v-card v-if="isLoaded" class="hood-card  mt-4 ">
            <!-- <div class="d-flex justify-space-between pb-4">
                <h2>{{agency.title}} Offices</h2>
                <v-btn outlined @click="editAgencyName">Edit Agency</v-btn>
            </div> -->
            <LeadMetrics :agency_id="id">
                <template v-slot:editButton>
                    <v-btn style="height: 40px;" outlined @click="editAgencyName">Edit Agency</v-btn>
                </template>
                <template v-slot:backButton>
                    <v-btn class="px-0" text  @click="backToAgency"  style="font-size: 24px; font-weight: 700;">
                        <v-icon large>mdi-chevron-left</v-icon> {{ agency.title }}
                    </v-btn>
                </template>
            </LeadMetrics>
        </v-card>

        <div>
            <v-row class="mt-5">
                <v-col cols="8" class="search-bg">
                    <Search @updateSearch="updateSearch"></Search>
                </v-col>
                <v-col cols="4" class="text-right">
                    <v-btn @click="addOffice" color="primary"><v-icon left>add</v-icon> Add New Office</v-btn>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" class="crm-table">
                    <v-data-table
                        :headers="headers"
                        :items="officesList"
                        :options.sync="options"
                        :server-items-length="totalItem"
                        :loading="loading"
                        class="elevation-1 row-pointer"
                        @click:row="openOffice "
                    >
                    </v-data-table>
                </v-col>
            </v-row>

            <CreateIndeOfficeModal v-if="independenceAgencyModal" :dialog="independenceAgencyModal" @goToNext="openCreationSuccModal" @cancelDialog="cancelIndOfficeModal">
            </CreateIndeOfficeModal>

            <CreateSuccessfulModal v-if="isCreatedSuccessfully" :dialog="isCreatedSuccessfully" :title="officeTitle" @cancel="cancelSuccessfulModal">
            </CreateSuccessfulModal>

            <AgencyEditModal v-if="editAgencyNameFlag" :agencyId="id" :dialog="editAgencyNameFlag" @cancelDialog="cancelEditAgency"  @openSuccessfulModal="saveAgencyName">

            </AgencyEditModal>

        </div>
    </v-container>
</template>


<script>
import Search from "@scripts/components/crm/Search";
import CreateSuccessfulModal from "@scripts/components/crm/modals/CreateSuccessfulModal";
import CreateIndeOfficeModal from "@scripts/components/crm/modals/CreateIndeOfficeModal";
import OfficeService from "@scripts/services/crm/OfficeService";
import LeadMetrics from "@scripts/components/crm/LeadMetrics";
import AgencyService from "@scripts/services/crm/AgencyService";
import AgencyEditModal from "@scripts/components/crm/modals/AgencyEditModal";
import AuthService from "@scripts/services/AuthService";


export default {
name: "CrmOfficeDataTable",
    props: {
        id: {
            required: false,
        }
    },
    components: {AgencyEditModal, CreateIndeOfficeModal, CreateSuccessfulModal, Search, LeadMetrics},
    data () {
        return {
            editAgencyNameFlag: false,
            independenceAgencyModal: false,
            isCreatedSuccessfully: false,
            officeTitle: '',
            officeInfo: null,
            officesList: [],
            lastCreatedOffice: null,


            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalItem: null,
            loading: true,
            options: {},
            headers:  [
                {
                    text: 'Offices',
                    align: 'start',
                    sortable: true,
                    value: 'title'
                },
                {
                    text: 'Applicants',
                    align: 'start',
                    sortable: true,
                    value: 'total_leads'
                },
                {
                    text: 'Last updated',
                    align: 'start',
                    sortable: true,
                    value: 'last_updated'
                },
                {
                    text: 'User Count',
                    align: 'start',
                    sortable: true,
                    value: 'user_count'
                },
                {
                    text: 'Rent Roll',
                    align: 'start',
                    sortable: true,
                    value: 'rent_roll'
                }
            ],
            search: '',
            agency: {},
            isLoaded: false,

        }
    },
    methods: {
        addOffice() {
           this.independenceAgencyModal = true;
        },

      async openCreationSuccModal(officeInfo) {
            this.independenceAgencyModal = false;

            this.officeInfo = {
                ...this.agency,
                ...officeInfo
            };

            this.officeTitle = this.officeInfo.office.title;
            this.independenceAgencyModal = false;
            await this.saveOfficeData();
            this.isCreatedSuccessfully = true;
        },
        cancelIndOfficeModal() {
          this.independenceAgencyModal = false;
        },

        cancelSuccessfulModal() {
            this.isCreatedSuccessfully = false;
            this.openOffice(this.lastCreatedOffice);

        },

      async  saveOfficeData() {
            let agencyId = this.$route.params?.id
             this.lastCreatedOffice = await OfficeService.saveOfficeData(this.officeInfo, agencyId);
            // console.log('this.lastCreatedOffice', this.lastCreatedOffice);
             // this.officesList.push(this.lastCreatedOffice);
        },

      async  loadOffices() {

            const meta = {
                search: this.search,
                page: this.options.page,
                per_page: this.options.itemsPerPage,
                is_descending: this.options.sortDesc.length != 0? this.options.sortDesc[0]: false,
                sort_by: this.options.sortBy.length != 0? this.options.sortBy[0]: '',
            }
            const data = await OfficeService.loadOfficeData(meta, this.$route.params.id);

            if(data.offices.length > 0 )
            {
                if (data.offices[0].agency.type == 0) {
                    this.$router.push({name: 'real.state.agency.users', params: { id : this.id, officeId: data.offices[0].id}});
                }
            }
            this.officesList = data?.offices;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
            this.loading = false;

        },

        openOffice(office) {
            // console.log('agency', this.id, 'office', office);
             this.$router.push({name: 'real.state.agency.users', params: { id : this.id, officeId: office.id}});
        },

        updateSearch(search) {
            this.search = search;
            this.loadOffices();
        },

        editAgencyName()
        {
            this.editAgencyNameFlag = true;
        },

        cancelEditAgency() {
            this.editAgencyNameFlag = false;

        },

        async saveAgencyName(agency) {
            console.log('agency' , agency);
            let payload = {name: agency}
            this.agency.title = agency;
           await AgencyService.updateAgency(payload, this.$route.params.id)
            this.editAgencyNameFlag = false;
            AuthService.setBreadcrumbs(this.$route.meta.breadcrumbType, this.$route.params)

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
            this.agency = await AgencyService.loadAgencyById(agencyId);
            this.isLoaded = true;
            // console.log(this.agency);
        }

    },

   async mounted() {
        await this.loadAgencyById();
        await this.loadOffices();

    },
    watch: {
        options: {
            handler () {
                this.loadOffices();
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

