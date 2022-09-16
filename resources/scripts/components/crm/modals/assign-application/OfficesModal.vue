<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="350px"
            transition="dialog-top-transition"
        >
            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-toolbar-items>
                        <v-btn
                            icon
                            dark
                            @click="closeModal"
                        >
                            <v-icon>mdi-chevron-left</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                    <v-spacer></v-spacer>
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
                                label="Office Search"
                                outlined
                                dense
                                prepend-inner-icon="mdi-magnify"
                                hide-details="auto"
                                v-model="search"
                                @input="getOfficesList"
                            ></v-text-field>
                        </div>
                        <v-virtual-scroll v-if="offices.length"
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
                        <p class="text-center mt-3" v-if="!isLoading && dataLoaded && !offices.length">
                            No offices found!
                        </p>
                        <p class="text-center mt-3" v-if="isLoading && !offices.length">
                            <v-progress-circular indeterminate
                                                 color="primary">
                            </v-progress-circular>
                        </p>
                    </v-list>
                </v-container>
            </v-card>
        </v-dialog>

        <AgentsModal v-if="showAgentsModal"
                     :dialog="showAgentsModal"
                     :selectedOfficeId="selectedOffice.id"
                     :selectedOfficeName="selectedOffice.name"
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
        isLoading: false,
        dataLoaded: false,
    }),
    methods: {
        // Reset data
        resetData() {
            this.offices = [];
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
            this.isLoading = true;
            let data = await AssignApplicationService.getOffices(val).finally(() => {
                this.isLoading = false;
            });
            this.offices = data.data;
        }, 500),
    },
    async mounted() {
        this.isLoading = true;
        await this.getOfficesList();
        this.dataLoaded = true;
    }
}
</script>
