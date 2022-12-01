<template>
    <div v-if="dataLoaded">
        <v-select
            :items="users"
            v-model="selectedItem"
            placeholder="Assignee"
            item-text="first_name"
            item-value="id"
            hide-details="auto"
            style="background-color: white"
            class="my-1 ml-1"
            outlined
            dense
            @change="onSelectAssignee"
        >
            <template v-slot:prepend-item>
                <div class="assigneeSearch">
                    <v-text-field
                        label="Search"
                        outlined
                        dense
                        prepend-inner-icon="mdi-magnify"
                        hide-details="auto"
                        v-model="search"
                        @input="changeInput"
                    ></v-text-field>
                </div>
            </template>

            <template v-slot:item="{ item, attrs, on }">
                <v-avatar size="30">
                    <img v-if="item.profile_img" v-bind:src="item.profile_img" alt="Image">
                    <v-icon v-else large>mdi-account-circle</v-icon>
                </v-avatar>
                <small class="pl-2">{{ item.first_name + ' ' + item.last_name }}</small>
            </template>
        </v-select>
    </div>
</template>

<script>
import CrmUserService from "@scripts/services/crm/CrmUserService";

export default {
    name: "AssigneeFilterDropdown",
    props: {},
    data() {
        return {
            selectedItem: '',
            search: '',
            users: [],
            userSearch: '',
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalUserItem: null,
            options: {
                itemsPerPage: 10
            },
            dataLoaded: false,
        }
    },
    computed: {
    },
    async mounted() {
        await this.loadUserList();
    },
    methods: {
        changeInput() {
            this.userSearch = this.search;
            this.loadUserList();
        },
        async loadUserList() {
            const meta = {
                search: this.userSearch,
                page: this.options.page,
                per_page: this.options.itemsPerPage,
                is_descending: false,
                sort_by: '',
            }
            const data = await CrmUserService.loadAllUser(meta);
            this.users = data?.users;
            this.page = data.pagination.current_page;
            this.itemsPerPage = data.pagination.per_page;
            this.totalUserItem = data.pagination.total;
            this.dataLoaded = true;
        },
        onSelectAssignee() {
            this.$emit('onSelectAssignee', this.selectedItem);
        },
    }
};
</script>

<style scoped>
.assigneeSearch {
    max-width: 350px;
    padding: 10px;
}
</style>
