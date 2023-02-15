<template>
    <div v-if="dataLoaded">
        <v-form ref="form" autocomplete="off">
            <v-row>
                <v-col cols="12" class="pb-0">
                    <div class="d-flex justify-end pt-2">
                        <v-btn v-show="!isSearchEmpty" x-small tile color="#e0e0e0" @click="clearSearch">
                            <v-icon small left> mdi mdi-close</v-icon>
                            Clear Filter
                        </v-btn>
                    </div>
                    <div class="d-flex">
                        <v-text-field
                            autocomplete="off"
                            v-model="$attrs.value.tenant_name"
                            full-width
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Name"
                            class="my-1 mr-1 width-25"
                        />
                        <v-text-field
                            v-model="$attrs.value.address"
                            full-width
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Address"
                            class="my-1 mr-1 width-25"
                        />
                        <v-text-field
                            v-model="$attrs.value.phone"
                            full-width
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Mobile"
                            class="my-1 mr-1 width-25"
                        />
                        <v-text-field
                            v-model="$attrs.value.tenant_email"
                            full-width
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Email"
                            class="my-1 mr-1 width-25"
                        />
                        <v-text-field
                            hidden
                            v-model="$attrs.value.duplication_group_id"
                        />
                    </div>
                </v-col>
                <v-col cols="12" class="pt-0">
                    <div class="d-flex">
                        <!-- lead source start -->
                        <v-select
                            placeholder="Source"
                            v-model="$attrs.value.source"
                            item-text="text"
                            item-value="value"
                            :items="srcOptions"
                            hide-details="auto"
                            class="my-1 mr-1 width-25"
                            outlined
                            dense
                        >
                            <template v-slot:item="{ item, attrs, on }">
                                <v-list-item link v-bind="attrs" v-on="on">
                                    <v-list-item-avatar>
                                        <v-img :src="item.icon" width="20px"/>
                                    </v-list-item-avatar>
                                    <v-list-item-content>
                                        <v-list-item-title>{{ item.text }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </v-select>
                        <!-- lead source ends -->
                        <!-- tenancy type starts -->
                        <v-select
                            placeholder="Tenancy"
                            v-model="$attrs.value.tenancy_type"
                            item-text="text"
                            item-value="value"
                            :items="tanancyTypeOptions"
                            hide-details="auto"
                            class="my-1 mr-1 width-25"
                            outlined
                            dense
                        >
                            <template v-slot:item="{ item, attrs, on }">
                                <v-list-item link v-bind="attrs" v-on="on">
                                    <v-list-item-content>
                                        <v-list-item-title>{{ item.text }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </v-select>
                        <!-- tenancy type ends -->
                        <!-- triage starts -->
                        <v-select
                            placeholder="Triage"
                            v-model="$attrs.value.triage"
                            item-text="text"
                            item-value="value"
                            :items="triageOptions"
                            hide-details="auto"
                            class="my-1 mr-1 width-25"
                            outlined
                            dense
                        >
                            <template v-slot:item="{ item, attrs, on }">
                                <v-list-item link v-bind="attrs" v-on="on">
                                    <v-list-item-content>
                                        <v-list-item-title>{{ item.text }}</v-list-item-title>
                                    </v-list-item-content>
                                </v-list-item>
                            </template>
                        </v-select>
                        <!-- triage ends -->
                        <!-- assignee start -->
                        <v-select
                            placeholder="Assignee"
                            v-model="selectedAssignee"
                            item-text="proerty_manager_name"
                            item-value="id"
                            :items="users"
                            hide-details="auto"
                            class="my-1 mr-1 width-25"
                            outlined
                            dense
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
                                    <img v-if="item.profile_img" :src="item.profile_img" alt="Image">
                                    <v-icon v-else large>mdi-account-circle</v-icon>
                                </v-avatar>
                                <small class="pl-2">{{ item.proerty_manager_name }}</small>
                            </template>
                        </v-select>
                    </div>
                </v-col>
            </v-row>
        </v-form>
    </div>
</template>

<script>
import CrmUserService from "@scripts/services/crm/CrmUserService";

export default {
    name: "ApplicationFilter",
    props: ["isSearchEmpty"],
    data() {
        return {
            tenant_name: "",
            name: "",
            address: "",
            mobile: "",
            srcOptions: [
                {text: "All Lead Source", value: "", icon: ""},
                {
                    text: "Hood Agent Portal",
                    value: "hood",
                    icon: "/assets/images/icons/company/hood.png",
                },
                {
                    text: "Hood.AI",
                    value: "hood_ai",
                    icon: "/assets/images/icons/company/hood.png",
                },
                {
                    text: "Foxie CRM",
                    value: "foxie",
                    icon: "/assets/images/icons/company/foxie.png",
                },
                {
                    text: "Ignite ",
                    value: "ignite",
                    icon: "/assets/images/icons/company/ignite.png",
                },
                {
                    text: "Our Property",
                    value: "our-property",
                    icon: "/assets/images/icons/company/our-property.png",
                },
                {
                    text: "PropertyMe ",
                    value: "property_me",
                    icon: "/assets/images/icons/company/propertyMe.png",
                },
                {
                    text: 'TApp',
                    value: 't_app',
                    icon: '/assets/images/icons/company/tapp.png'
                },
                {
                    text: 'MRI',
                    value: 'mri',
                    icon: '/assets/images/icons/company/mri.png'
                },

            ],
            tanancyTypeOptions: [
                {text: "Renter", value: "renter"},
                {
                    text: "Owner",
                    value: "home_owner",
                },
                {text: "All", value: ""},
            ],
            triageOptions: [
                {text: "All", value: ""},
                {text: "Triage", value: "triage"},
            ],
            leadSrc: {default: "all"},
            tenancy_Type: {default: "all"},
            triage: {default: "all"},
            dataLoaded: false,

            assignee: "",
            search: "",
            users: [],
            userSearch: "",
            page: 1,
            pageCount: 0,
            itemsPerPage: 10,
            totalUserItem: null,
            options: {
                itemsPerPage: 10
            },
        };
    },
    computed: {
        selectedAssignee: {
            get: function () {
                let assignee = this.$attrs.value.assignee ?? this.$route.query.assignee;
                return this.users.find((user) => user.id == assignee);
            },
            set: function (newValue) {
                this.$attrs.value.assignee = newValue;
            }
        },
    },
    async mounted() {
        await this.loadUserList();
        this.dataLoaded = true;
    },
    methods: {
        clearSearch() {
            this.$refs.form.reset();
        },
        changeInput() {
            this.$attrs.value.assignee_search = this.search;
            this.loadUserList();
        },
        async loadUserList() {
            this.search = this.$attrs.value.assignee_search ? this.$attrs.value.assignee_search : '';
            const meta = {
                search: this.$attrs.value.assignee_search || this.$route.query.assignee_search,
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
        },
    },
    watch: {
        isSearchEmpty(val) {
            // console.log("feea" , val)
        }
    },

};
</script>

<style lang="scss" scoped>
.clearButton {
    background: #e0e0e0;
    color: black;
    border-radius: 1px;

    &:hover {
        cursor: pointer;
    }
}

.assigneeSearch {
    max-width: 350px;
    padding: 10px;
}

.width-25 {
    width: 25%;
    background-color: #FFFFFF;
}
</style>
