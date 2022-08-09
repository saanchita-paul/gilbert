<template>
    <v-container fluid>
        <v-card class="hood-card">
<!--            <h2>All Application Metrics</h2>-->
            <LeadMetrics ></LeadMetrics>
        </v-card>
        <div>
            <v-row class="mt-5">
                <v-col cols="8" class="search-bg">
                    <Search @updateSearch="updateSearch"></Search>
                    <!-- <v-btn
                        class="ma-2"
                        outlined
                        color="indigo"
                        @click="advanceSearchDialog"
                    >
                        Advanced Search
                    </v-btn> -->
                </v-col>
                <v-col cols="4" class="text-right">
                    <v-btn  class="hood-btn" color="primary" @click="addAgency"
                    ><v-icon left>add
                    </v-icon> Add New Agency
                    </v-btn>
                </v-col>
            </v-row>

            <v-card class="hood-card mt-5">
                <v-row>
                    <v-col cols="12" class="crm-table">
                        <v-data-table
                            :headers="headers"
                            :items="agencies"
                            :options.sync="options"
                            :server-items-length="totalItem"
                            :loading="loading"
                            class="row-pointer"
                            @click:row="openAgency"
                        >
                            <template v-slot:item.action="{ item }">
                                <v-btn>
                                    <v-icon
                                        small
                                        @click.stop="editItem(item)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                </v-btn>
                            </template>
                        </v-data-table>
                    </v-col>
                </v-row>
            </v-card>
            <AgencyDetailsModal v-if="agencyCreateModal" :dialog="agencyCreateModal" @openSuccessfulModal="openSuccessfulModal" @cancelDialog="cancelAgencyModal">
            </AgencyDetailsModal>
            <CreateIndeOfficeModal v-if="independenceAgencyModal" :dialog="independenceAgencyModal" @goToNext="openCreationSuccModal" @cancelDialog="cancelIndOfficeModal">
            </CreateIndeOfficeModal>
            <CreateSuccessfulModal v-if="agencyCreateSuccessFullModal" :dialog="agencyCreateSuccessFullModal" :title="title" @cancel="cancelSuccessfulModal">
            </CreateSuccessfulModal>
            <AdvanceSearchModal v-if="advanceSearchModal" :dialog="advanceSearchModal" :title="title" @cancelDialog="cancelAdvanceSearchModal">
            </AdvanceSearchModal>
        </div>
    </v-container>
</template>

<script>
import CreateIndeOfficeModal from "@scripts/components/crm/modals/CreateIndeOfficeModal";
import CreateSuccessfulModal from "@scripts/components/crm/modals/CreateSuccessfulModal";
import AgencyDetailsModal from "@scripts/components/crm/modals/AgencyDetailsModal";
import Search from "@scripts/components/crm/Search";
import AgencyService from "@scripts/services/crm/AgencyService";
import LeadMetrics from "@scripts/components/crm/LeadMetrics";
import AdvanceSearchModal from '@scripts/components/crm/modals/AdvanceSearchModal';

export default {
name: "CrmAgencyDataTable",
    components: {
        CreateIndeOfficeModal,
        CreateSuccessfulModal,
        AgencyDetailsModal,
        Search,
        LeadMetrics,
        AdvanceSearchModal,
    },
    data(){
        return {
            snack: false,
            agencyCreateModal: false,
            agencyCreateSuccessFullModal: false,
            independenceAgencyModal: false,
            officeTitle : '',
            agencies:[],
            agency: null,
            title: '',
            newAgency: null,

            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalItem: null,
            loading: true,
            options: {},
            headers:  [
                {
                text: ' Agency',
                align: 'start',
                sortable: true,
                value: 'title'
                },
                {
                    text: 'Apps',
                    align: 'center',
                    sortable: true,
                    value: 'total_leads'
                },
                {
                    text: 'Last Application',
                    align: 'start',
                    sortable: true,
                    value: 'last_application'
                },
                {
                    text: 'CVR%',
                    align: 'center',
                    sortable: false,
                    value: 'conversion_rate'
                },
                {
                    text: 'Active Users',
                    align: 'center',
                    sortable: false,
                    value: 'active_user_count'
                },
                {
                    text: 'Rent Roll',
                    align: 'center',
                    sortable: false,
                    value: 'rent_roll_count'
                }
            ],
            search: '',
            advanceSearchModal: false,

        }
    },

    methods: {
        addAgency() {
            this.agencyCreateModal = true;
        },

        cancelAgencyModal() {
            this.agencyCreateModal = false;
        },

    async  openSuccessfulModal(agency) {
            this.agencyCreateModal = false;
            this.agency = agency;
            this.title = agency.title;
            if(agency && agency.type === 0)
            {
                this.independenceAgencyModal = true;
            } else {

               await this.saveAgencyData();
                this.agencyCreateSuccessFullModal = true;
            }

        },

        cancelIndOfficeModal() {
            this.independenceAgencyModal = false;
        },

        cancelSuccessfulModal() {
            this.agencyCreateSuccessFullModal = false;
            if(this.agency.type === 'Independent Agency') {
                this.$router.push({name: 'real.state.agency.users', params: {id: this.newAgency.id, officeId: 10}});
            } else {
                this.$router.push({name: 'real.state.agency.office', params: {id: this.newAgency.id}});
            }

        },

      async openCreationSuccModal(agency) {
            this.agency = {
                ...this.agency,
                ...agency
            };
            // this.title = this.agency.office.title;
            this.independenceAgencyModal = false;
            await this.saveAgencyData();
            this.agencyCreateSuccessFullModal = true;

        },

        async loadAgencyData() {

            const meta = {
                search: this.search,
                page: this.options.page,
                per_page: this.options.itemsPerPage === -1 ? this.totalItem : this.options.itemsPerPage,
                is_descending: this.options.sortDesc.length != 0? this.options.sortDesc[0]: false,
                sort_by: this.options.sortBy.length != 0? this.options.sortBy[0]: '',
            }
            const data =  await AgencyService.loadAgencyData(meta);
            this.agencies = data.agencies;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItem = data.pagination.total;
            this.loading = false;
        },

       async saveAgencyData() {
            if(this.agency.type)
            {
                this.newAgency = await AgencyService.saveAgency(this.agency);
            } else {
                this.newAgency = await AgencyService.saveIndependentAgency(this.agency);
            }


        },

        openAgency(agency) {
            this.$router.push({name: 'real.state.agency.office', params: {id : agency.id}});
        },

        updateSearch(search) {
            this.search = search;
            this.loadAgencyData();
        },

        editItem(item) {

        },
        cancelAdvanceSearchModal(){
            this.advanceSearchModal = false;
        },
        advanceSearchDialog(){
            this.advanceSearchModal = true;
        }
    },

    mounted() {
        this.loadAgencyData();
    },

    watch: {
        options: {
            handler () {
                this.loadAgencyData();
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
</style>
