<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="350px"
            transition="dialog-bottom-transition"
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
                    <v-toolbar-title>{{ selectedOfficeName }}</v-toolbar-title>
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
                        <div class="assigneesearch">
                            <v-text-field
                                label="Agent Search"
                                outlined
                                dense
                                prepend-inner-icon="mdi-magnify"
                                hide-details="auto"
                                v-model="search"
                                @input="getAgentsList"
                            ></v-text-field>
                        </div>
                        <v-virtual-scroll v-if="agents.length"
                                          :items="agents"
                                          height="300"
                                          item-height="64"
                        >
                            <template v-slot:default="{ item, index }">
                                <v-list-item :key="index" class="cursor-pointer list-tile"
                                             @click="selectAgent(index)">
                                    <v-list-item-title>{{ item.first_name + ' ' + item.last_name }}</v-list-item-title>
                                </v-list-item>
                                <v-divider></v-divider>
                            </template>
                        </v-virtual-scroll>

                        <p class="text-center mt-3" v-if="!isLoading && dataLoaded && !agents.length">
                            No agents found!
                        </p>
                        <p class="text-center mt-3" v-if="isLoading && !agents.length">
                            <v-progress-circular indeterminate
                                                 color="primary">
                            </v-progress-circular>
                        </p>
                    </v-list>
                </v-container>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import AssignApplicationService from "@scripts/services/crm/AssignApplicationService";
import {debounce} from "lodash-es";

export default {
    name: "AgentsModal",
    props: {
        dialog: {
            require: true,
        },
        selectedOfficeId: {
            type: Number,
            required: true
        },
        selectedOfficeName: {
            type: String,
            required: true
        },
        selectedApplication: {
            type: Object,
            required: true,
        }
    },
    data: () => ({
        agents: [],
        selectedAgent: null,
        search: null,
        isLoading: false,
        dataLoaded: false,
    }),
    methods: {
        // Reset data
        resetData() {
            this.agents = [];
            this.selectedAgent = null;
            this.search = null;
        },
        // Close modal
        closeModal() {
            this.resetData();
            this.$emit('closeAgentsModal');
        },
        // Select agent which will assign to the application
        selectAgent(index) {
            this.selectedAgent = this.agents[index];
            this.selectedApplication.office_id = this.selectedOfficeId;
            this.selectedApplication.agency_office = this.selectedOfficeName;
            this.selectedApplication.agent_name = this.selectedAgent.first_name + ' ' + this.selectedAgent.last_name;
            this.selectedApplication.created_by = this.selectedAgent.id;
            this.selectedApplication.is_selected = true;
            this.$emit('selectApplication');
            this.closeModal();
        },
        // Get & search agents
        getAgentsList: debounce(async function (val) {
            this.isLoading = true;
            let data = await AssignApplicationService.getAgents(val, this.selectedOfficeId).finally(() => {
                this.isLoading = false
            });
            this.agents = data.data;
        }, 500),
    },
    async mounted() {
        this.isLoading = true;
        await this.getAgentsList();
        this.dataLoaded = true;
    }
}
</script>
