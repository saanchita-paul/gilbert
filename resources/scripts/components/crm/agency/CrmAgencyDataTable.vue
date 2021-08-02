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
                            >
                                <td>{{ item.agency_name }}</td>
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
        <CreateIndeOfficeModal v-if="independenceAgencyModal" :dialog="independenceAgencyModal" @openSuccessfulModal="openCreationSuccModal" @cancelDialog="cancelIndOfficeModal">
        </CreateIndeOfficeModal>
        <CreateSuccessfulModal v-if="agencyCreateSuccessFullModal" :dialog="agencyCreateSuccessFullModal" :title="officeTitle" @cancel="cancelSuccessfulModal">
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

        }
    },

    methods: {
        addAgency() {
            this.agencyCreateModal = true;
        },

        cancelAgencyModal() {
            this.agencyCreateModal = false;
        },

        openSuccessfulModal(title) {

            this.independenceAgencyModal = true;
            this.agencyCreateModal = false;
        },

        cancelIndOfficeModal() {
            this.independenceAgencyModal = false;
        },

        cancelSuccessfulModal() {
            this.agencyCreateSuccessFullModal = false;

        },

        openCreationSuccModal() {
            this.independenceAgencyModal = false;
            this.agencyCreateSuccessFullModal = true;
        },

        loadAgencyData() {
            this.agencies =  AgencyService.loadAgencyData();
            console.log('agency', this.agencies);
        }
    },

    mounted() {
        this.loadAgencyData();
    }

}
</script>

<style scoped>

</style>
