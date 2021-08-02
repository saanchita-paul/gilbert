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
                            v-for="item in agency"
                            :key="item.id"
                        >
                            <td>{{ item.agencyname }}</td>
                            <td>{{ item.totalleads }}</td>
                            <td>{{ item.lastupdated }}</td>
                            <td>{{ item.Offices }}</td>
                        </tr>
                        </tbody>
                    </template>
                </v-simple-table>
            </v-col>
        </v-row>
        <CreateIndeOfficeModal v-if="isCreatingOffice" :dialog="isCreatingOffice" @openSuccessfulModal="openCreationSuccModal" @cancelDialog="cancelIndOfficeModal">
        </CreateIndeOfficeModal>
        <CreateSuccessfulModal v-if="isCreatedSuccessfully" :dialog="isCreatedSuccessfully" :title="officeTitle" @cancel="cancelSuccessfulModal">
        </CreateSuccessfulModal>
    </div>
</template>


<script>
import Search from "@scripts/components/crm/Search";
import CreateSuccessfulModal from "@scripts/components/crm/modals/CreateSuccessfulModal";
import CreateIndeOfficeModal from "@scripts/components/crm/modals/CreateIndeOfficeModal";
export default {
name: "CrmOfficeDataTable",
    components: {CreateIndeOfficeModal, CreateSuccessfulModal, Search},
    data () {
        return {
            agency: [
                {
                    agencyname: 'Barry Plant',
                    totalleads: 500,
                    lastupdated: '00/00/2021',
                    Offices: 30,
                },
                {
                    agencyname: 'Raine & Horne',
                    totalleads: 100,
                    lastupdated: '06/00/2021',
                    Offices: 100,
                },
                {
                    agencyname: '[EA Homes]_(Independent)',
                    totalleads: 300,
                    lastupdated: '07/00/2021',
                    Offices: 30,
                },
                {
                    agencyname: 'Raine & Horne',
                    totalleads: 200,
                    lastupdated: '08/00/2021',
                    Offices: 10,
                },
            ],
            isCreatingOffice: false,
            isCreatedSuccessfully: false,
            officeTitle: ''
        }
    },
    methods: {
        addOffice() {
            console.log('is creating office');
           this.isCreatingOffice = true;
        },
        openCreationSuccModal() {
            this.isCreatingOffice = false;
            this.isCreatedSuccessfully = true;
        },
        cancelIndOfficeModal() {
          this.isCreatingOffice = false;
        },

        cancelSuccessfulModal() {
            this.isCreatedSuccessfully = false;
        }
    }
}
</script>

<style scoped>

</style>

