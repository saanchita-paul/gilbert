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
                    <v-toolbar-title>Agents</v-toolbar-title>
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
                                label="Search"
                                outlined
                                dense
                                prepend-inner-icon="mdi-magnify"
                                hide-details="auto"
                                v-model="search"
                                @input="getAgentsList"
                            ></v-text-field>
                        </div>
                        <v-virtual-scroll
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
        selectedApplication: {
            type: Object,
            required: true,
        }
    },
    data: () => ({
        agents: [],
        selectedAgent: null,
        selectedIndex: null,
        search: null,
    }),
    methods: {
        resetData() {
            this.agents = [];
            this.selectedAgent = null;
            this.search = null;
        },
        closeModal() {
            this.resetData();
            this.$emit('closeAgentsModal');
        },
        selectAgent(index) {
            this.selectedAgent = this.agents[index];
            this.selectedApplication.agent_name = this.selectedAgent.first_name + ' ' + this.selectedAgent.last_name;
            this.selectedApplication.created_by = this.selectedAgent.id;
            this.selectedApplication.is_selected = true;
            this.$emit('selectApplication');
            this.closeModal();
        },
        getAgentsList: debounce(async function (val) {
            let data = await AssignApplicationService.getAgents(val, this.selectedOfficeId);
            this.agents = data.data;
        }, 500),
    },
    mounted() {
        this.getAgentsList();
    }
}
</script>
