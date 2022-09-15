<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="350px"
        >
            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-toolbar-title>Offices</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            icon
                            dark
                            @click="closeModal"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-container>
                    <v-list>
                        <div class="officesSearch">
                            <v-text-field
                                label="Search"
                                outlined
                                dense
                                prepend-inner-icon="mdi-magnify"
                                hide-details="auto"
                                v-model="search"
                                @input="getOfficesList"
                            ></v-text-field>
                        </div>
                        <v-virtual-scroll
                            :items="offices"
                            height="300"
                            item-height="64"
                        >
                            <template v-slot:default="{ item, index }">
                                <v-list-item :key="index" class="cursor-pointer list-tile active"
                                             @click="openAgentsModal(index)">
                                    <v-list-item-title>
                                        {{ item.name }}
                                        <br>
                                        <small>{{ item.agency.name }}</small>
                                    </v-list-item-title>

                                </v-list-item>
                                <v-divider></v-divider>
                            </template>
                        </v-virtual-scroll>
                    </v-list>
                </v-container>
            </v-card>
        </v-dialog>

        <AgentsModal v-if="showAgentsModal"
                     :dialog="showAgentsModal"
                     :selectedOfficeId="selectedOffice.id"
                     :selectedApplication="selectedApplication"
                     @selectApplication="selectApplication"
                     @closeAgentsModal="closeAgentsModal"/>
    </v-row>
</template>

<script>
import AgentsModal from "@scripts/components/crm/modals/assign-application/AgentsModal";
import AssignApplicationService from "@scripts/services/crm/AssignApplicationService";
import {debounce} from 'lodash-es';

export default {
    name: "OfficesModal",
    props: {
        dialog: {
            require: true,
        },
        selectedApplication: {
            type: Object,
            required: true,
        }
    },
    components: {
        AgentsModal
    },
    data: () => ({
        showAgentsModal: false,
        offices: [],
        selectedOffice: {},
        search: null,
    }),
    methods: {
        // Reset data
        resetData() {
            this.offices = [];
            this.selectedIndex = null;
            this.selectedOffice = {};
            this.search = null;
        },
        // Close modal
        closeModal() {
            this.resetData();
            this.$emit('closeOfficesModal');
        },
        // Open agents modal
        openAgentsModal(index) {
            this.selectedOffice = this.offices[index];
            this.selectedApplication.agency_office = this.selectedOffice.name;
            this.selectedApplication.office_id = this.selectedOffice.id;
            this.showAgentsModal = true;
        },
        // Close agents modal
        closeAgentsModal() {
            this.showAgentsModal = false;
        },
        // Select application which will be assigned
        selectApplication() {
            this.$emit('selectApplication');
            this.closeModal();
        },
        // Get office list and search office
        getOfficesList: debounce(async function (val) {
            let data = await AssignApplicationService.getOffices(val);
            this.offices = data.data;
        }, 500),
    },
    mounted() {
        this.getOfficesList();
    }
}
</script>
