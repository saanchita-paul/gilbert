<template>
    <v-select
        v-model="selectedAgent"
        :items="filteredAgents"
        item-text="full_name"
        item-value="id"
        placeholder="Select Agent"
        outlined
        hide-details="auto"
        @change="onChangeAgent"
    >
        <template v-slot:prepend-item>
            <v-list-item ripple>
                <v-text-field
                    label="Search Agent"
                    outlined
                    dense
                    prepend-inner-icon="mdi-magnify"
                    hide-details="auto"
                    v-model="searchText"
                    @input="changeText"
                ></v-text-field>
            </v-list-item>
            <v-divider class="mt-2"></v-divider>
        </template>
    </v-select>
</template>

<script>
import AgentApplicationService from "@scripts/services/crm/AgentApplicationService";
import AuthService from "@scripts/services/AuthService";

export default {
    name: "AgentDropdown",
    props: {
        selectedAgentId: {
            required: false
        },
    },
    data() {
        return {
            agents: [],
            officeId: null,
            agentId: null,
            selectedAgent: null,
            searchText: null,
            current_page: 1,
            itemsPerPage: 15,
            totalItems: null,
            currentUserName: '',
        };
    },
    methods: {
        async loadAgentList() {
            const meta = {
                search: this.searchText,
                per_page: this.itemsPerPage,
                is_descending: false,
                sort_by: '',
            }
            const data = await AgentApplicationService.loadAgentList(meta, this.agentId, this.officeId);
            this.agents = data?.agents;
            console.log('agents', data);
            this.current_page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalItems = data.pagination.total;
        },
        changeText() {
            this.loadAgentList();
        },
        onChangeAgent(agent) {
            this.$emit('onChangeAgent', agent);
        },
        setDefaultData() {
            const user = AuthService.getAuthUser();
            console.log('user', user);
            this.agentId = user?.profile?.id;
            this.officeId = user?.profile?.office?.id;
            this.currentUserName = user?.profile?.first_name + ' ' + user?.profile?.last_name;
        },
    },
    mounted() {
        this.setDefaultData();
        this.selectedAgent = this.selectedAgentId;
        this.loadAgentList();
    },
    computed: {
        filteredAgents() {
            const filteredAgents = this.agents.filter(agent => agent.id !== this.agentId);
            filteredAgents.unshift({
                id: this.agentId,
                full_name: `Me (${this.currentUserName})`,
            });
            return filteredAgents;
        },
    },
};
</script>

<style scoped></style>
