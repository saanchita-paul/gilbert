<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            scrollable
            max-width="950px"
        >
            <v-card max-height="600px">
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-toolbar-title>This application has duplicates in Gilbert.</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            icon
                            dark
                            @click="cancelDuplicateLead"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-title>
                    <h3 class="text-large">What does this mean?</h3>
                </v-card-title>

                <v-card-subtitle>
                    <p class="text-small">This means that this lead may have duplicate application's in Gilbert.</p>
                </v-card-subtitle>

                <v-card-text>
                    <v-row>
                        <v-col>
                            <v-data-table
                                :headers="headers"
                                :items="applications"
                                hide-default-footer
                                :loading="loadTable"
                                :items-per-page="50"
                            ></v-data-table>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions class="justify-center">
                    <v-btn
                        color="deep-purple lighten-2"
                        text
                    >
                        View All Duplicates
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>

import DuplicateLeadService from "@scripts/services/crm/DuplicateLeadService";

export default {
    name: "DuplicateLeadModal",
    components: {},
    props: {
        dialog: {
            require: true,
        },
        duplicateGroupId: {
            require: true
        }
    },
    data() {
        return {
            headers: [
                { text: 'App Id', align: 'start', value: 'id'},
                { text: 'Name', align: 'start', sortable: true, value: 'name' },
                { text: 'Mobile No', align: 'start', sortable: true, value: 'mobile' },
                { text: 'Lead Source', align: 'start', sortable: true, value: 'source' },
                { text: 'Connection Address', align: 'start', sortable: true, value: 'connection_address' },
                { text: 'Email Address', align: 'start', sortable: true, value: 'email' }
            ],
            loadTable: true,
            applications: []
        }
    },
    mounted() {
        this.fetchDuplicateLead();
    },

    methods: {
        cancelDuplicateLead() {
            this.$emit('cancelDuplicateLead');
        },
        async fetchDuplicateLead() {
            this.applications = (await DuplicateLeadService.getDuplicateLeadData(this.duplicateGroupId)).data;
            this.loadTable= false;
        }
    }
}
</script>

<style scoped>
.text-large {
    color: #542E89;
    font-size: 16px;
}
.text-small {
    font-size: 15px;
}
</style>
