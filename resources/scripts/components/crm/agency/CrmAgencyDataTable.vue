<template>
    <div>
        <v-row class="mt-5">
            <v-col cols="8" class="search-bg">
                <Search></Search>
            </v-col>
            <v-col cols="4" class="text-right">
                <v-btn color="primary" @click="addAgency"
                ><v-icon left>add
                </v-icon> Add New Agency
                </v-btn>
            </v-col>
        </v-row>
        <v-card class="pa-4">
            <v-row>
                <v-col cols="12" class="crm-table">
                    <v-simple-table>
                        <template v-slot:default>
                            <thead>
                            <tr>
                                <th class="text-left">
                                    Agency name
                                </th>
                                <th class="text-left">
                                    Total leads
                                </th>
                                <th class="text-left">
                                    Last updated
                                </th>
                                <th class="text-left">
                                    Offices
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr
                                v-for="item in agencies"
                                :key="item.id"
                                @click="openAgency(item)"
                            >
                                <td>{{ item.title }}</td>
                                <td>{{ item.total_leads }}</td>
                                <td>{{ item.last_updated }}</td>
                                <td>{{ item.offices }}</td>
                            </tr>
                            </tbody>
                        </template>
                    </v-simple-table>
                </v-col>
            </v-row>
        </v-card>
        <AgencyDetailsModal v-if="agencyCreateModal" :dialog="agencyCreateModal" @openSuccessfulModal="openSuccessfulModal" @cancelDialog="cancelAgencyModal">
        </AgencyDetailsModal>
        <CreateIndeOfficeModal v-if="independenceAgencyModal" :dialog="independenceAgencyModal" @goToNext="openCreationSuccModal" @cancelDialog="cancelIndOfficeModal">
        </CreateIndeOfficeModal>
        <CreateSuccessfulModal v-if="agencyCreateSuccessFullModal" :dialog="agencyCreateSuccessFullModal" :title="title" @cancel="cancelSuccessfulModal">
        </CreateSuccessfulModal>
    </div>
</template>

<script>
import CreateIndeOfficeModal from "@scripts/components/crm/modals/CreateIndeOfficeModal";
import CreateSuccessfulModal from "@scripts/components/crm/modals/CreateSuccessfulModal";
import AgencyDetailsModal from "@scripts/components/crm/modals/AgencyDetailsModal";
import Search from "@scripts/components/crm/Search";
import AgencyService from "@scripts/services/crm/AgencyService";

export default {
name: "CrmAgencyDataTable",
    components: {
        CreateIndeOfficeModal,
        CreateSuccessfulModal,
        AgencyDetailsModal,
        Search
    },
    data(){
        return {
            agencyCreateModal: false,
            agencyCreateSuccessFullModal: false,
            independenceAgencyModal: false,
            officeTitle : '',
            agencies:[],
            agency: null,
            title: '',
            newAgency: null,

        }
    },

    methods: {
        addAgency() {
            this.agencyCreateModal = true;
        },

        cancelAgencyModal() {
            this.agencyCreateModal = false;
        },

        openSuccessfulModal(agency) {
            this.agencyCreateModal = false;
            this.agency = agency;
            if(agency && agency.type === 'Independent Agency')
            {
                this.independenceAgencyModal = true;
            } else {
                this.title = agency.title;
                this.saveAgencyData();
                this.agencyCreateSuccessFullModal = true;
            }

        },

        cancelIndOfficeModal() {
            this.independenceAgencyModal = false;
        },

        cancelSuccessfulModal() {
            this.agencyCreateSuccessFullModal = false;
            console.log(this.newAgency);
            if(this.agency.type === 'Independent Agency') {
                this.$router.push({name: 'real.state.agency.users', params: {id: this.newAgency.id, officeId: 10}});
            } else {
                this.$router.push({name: 'real.state.agency.office', params: {id: this.newAgency.id}});
            }

        },

        openCreationSuccModal(agency) {
            this.agency = {
                ...this.agency,
                ...agency
            };
            this.title = this.agency.office.title;
            this.independenceAgencyModal = false;
            this.saveAgencyData();
            this.agencyCreateSuccessFullModal = true;

        },

        loadAgencyData() {
            this.agencies =  AgencyService.loadAgencyData();
        },

        saveAgencyData() {
            this.newAgency = AgencyService.saveAgency(this.agency);
            this.agencies.push(this.newAgency);

        },

        openAgency(agency) {
            this.$router.push({name: 'real.state.agency.office', params: {id : agency.id}});
        }

    },

    mounted() {
        this.loadAgencyData();
    }

}
</script>

<style scoped>

</style>
