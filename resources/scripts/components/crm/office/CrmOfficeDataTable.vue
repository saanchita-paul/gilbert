<template>
    <div>
        <v-row class="mt-5">
            <v-col cols="8" class="search-bg">
                <Search></Search>
            </v-col>
            <v-col cols="4" class="text-right">
                <v-btn @click="addOffice" color="primary"><v-icon left>add</v-icon> Add New Office</v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="12" class="crm-table">
                <v-simple-table>
                    <template v-slot:default>
                        <thead>
                        <tr>
                            <th class="text-left">
                                Offices
                            </th>
                            <th class="text-left">
                                Total leads
                            </th>
                            <th class="text-left">
                                Last updated
                            </th>
                            <th class="text-left">
                                User Account
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="item in officesList"
                            :key="item.id" @click="openOffice"
                        >
                            <td>{{ item.title }}</td>
                            <td>{{ item.total_leads }}</td>
                            <td>{{ item.last_updated }}</td>
                            <td>{{ item.user_account }}</td>
                        </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </v-col>
        </v-row>

        <CreateIndeOfficeModal v-if="independenceAgencyModal" :dialog="independenceAgencyModal" @goToNext="openCreationSuccModal" @cancelDialog="cancelIndOfficeModal">
        </CreateIndeOfficeModal>

        <CreateSuccessfulModal v-if="isCreatedSuccessfully" :dialog="isCreatedSuccessfully" :title="officeTitle" @cancel="cancelSuccessfulModal">
        </CreateSuccessfulModal>
    </div>
</template>


<script>
import Search from "@scripts/components/crm/Search";
import CreateSuccessfulModal from "@scripts/components/crm/modals/CreateSuccessfulModal";
import CreateIndeOfficeModal from "@scripts/components/crm/modals/CreateIndeOfficeModal";
import OfficeService from "@scripts/services/crm/OfficeService";
import AgencyService from "@scripts/services/crm/AgencyService";
export default {
name: "CrmOfficeDataTable",
    props: ['agency'],
    components: {CreateIndeOfficeModal, CreateSuccessfulModal, Search},
    data () {
        return {
            independenceAgencyModal: false,
            isCreatedSuccessfully: false,
            officeTitle: '',
            officeInfo: null,
            officesList: [],
        }
    },
    methods: {
        addOffice() {
           this.independenceAgencyModal = true;
        },

        openCreationSuccModal(officeInfo) {
            this.independenceAgencyModal = false;

            this.officeInfo = {
                ...this.agency,
                ...officeInfo
            };

            this.officeTitle = this.officeInfo.office.title;
            this.independenceAgencyModal = false;
            this.saveOfficeData();


            this.isCreatedSuccessfully = true;
        },
        cancelIndOfficeModal() {
          this.independenceAgencyModal = false;
        },

        cancelSuccessfulModal() {
            this.isCreatedSuccessfully = false;
        },

        saveOfficeData() {
            console.log('agency', this.agency);
             let newOffice = OfficeService.saveOfficeData(this.officeInfo);
             this.officesList.push(newOffice);


        },

        loadOffices() {
            this.officesList = OfficeService.loadOfficeData();

        },

        openOffice(office) {
            this.$router.push({name: 'real.state.agency.users', params: {office:office}});
        }
    },

    mounted() {
        console.log(this.agency);
        this.loadOffices();
    }
}
</script>

<style scoped>

</style>

