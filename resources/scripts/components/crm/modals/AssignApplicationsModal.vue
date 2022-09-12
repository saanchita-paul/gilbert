<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            scrollable
            max-width="1024px"
        >
            <v-card max-height="600px">
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-toolbar-title>Assign Applications to Offices and Agents</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            icon
                            dark
                            @click="cancelAssignApplications"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>

                <v-card-title>
                    <h3 class="text-large">What does this mean?</h3>
                </v-card-title>

                <v-card-subtitle>
                    <p class="text-small">
                        From this holding office. You can assign tenants to their respective agents
                        and offices.
                    </p>
                </v-card-subtitle>

                <v-card-text>
                    <v-row>
                        <v-col>
                            <v-simple-table class="text-no-wrap">
                                <template v-slot:default>
                                    <thead>
                                    <tr>
                                        <th class="text-left black--text">
                                            App ID
                                        </th>
                                        <th class="text-left black--text">
                                            Tenant Name
                                        </th>
                                        <th class="text-left black--text">
                                            Connection Address
                                        </th>
                                        <th class="text-left black--text">
                                        </th>
                                        <th class="text-left black--text">
                                            New Office Assignment
                                        </th>
                                        <th class="text-left black--text">
                                            Agent Name
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr
                                        v-for="item in applications"
                                        :key="item.id"
                                    >
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.tenant_name }}</td>
                                        <td>{{ item.address_text }}</td>
                                        <td>
                                            <v-btn color="primary" text icon class="ma-2" @click="openOfficesModal">
                                                <v-icon left>add</v-icon>
                                            </v-btn>
                                        </td>
                                        <td>
                                            {{ item.agency_office }}
                                        </td>
                                        <td>{{ item.agent_name }}</td>
                                    </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions class="justify-end">
                    <v-btn
                        @click="cancelAssignApplications"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        color="primary"
                        @click="openConfirmModal"
                    >
                        Assign Applications
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <OfficesModal v-if="showOfficesModal"
                      :dialog="showOfficesModal"
                      @closeOfficesModal="closeOfficesModal"/>

        <AssignApplicationsConfirmModal v-if="showConfirmModal"
                                        :dialog="showConfirmModal"
                                        @openSuccessModal="openSuccessModal"
                                        @closeConfirmModal="closeConfirmModal"/>

        <AssignedApplicationSuccessModal v-if="showSuccessModal"
                                         :dialog="showSuccessModal"
                                         @closeSuccessModal="closeSuccessModal"/>
    </v-row>
</template>

<script>
import OfficesModal from "@scripts/components/crm/modals/OfficesModal";
import AssignApplicationsConfirmModal from "@scripts/components/crm/modals/AssignApplicationsConfirmModal";
import AssignedApplicationSuccessModal from "@scripts/components/crm/modals/AssignedApplicationSuccessModal";

export default {
    name      : "AssignApplicationsModal",
    components: {
        OfficesModal,
        AssignApplicationsConfirmModal,
        AssignedApplicationSuccessModal,
    },
    props     : {
        dialog      : {
            require: true,
            type   : Boolean,
        },
        applications: {
            require: true,
            type   : Array,
        }
    },
    data() {
        return {
            loadTable       : true,
            showOfficesModal: false,
            showConfirmModal: false,
            showSuccessModal: false,
        }
    },
    mounted() {
        // this.fetchDuplicateLead();
    },

    methods: {
        cancelAssignApplications() {
            this.$emit('cancelAssignApplications');
        },
        openOfficesModal() {
            this.showOfficesModal = true;
        },
        closeOfficesModal() {
            this.showOfficesModal = false;
        },
        openConfirmModal() {
            this.showConfirmModal = true;
        },
        closeConfirmModal() {
            this.showConfirmModal = false;
        },
        openSuccessModal() {
            this.showSuccessModal = true;
        },
        closeSuccessModal() {
            this.showConfirmModal = false;
            this.$emit('cancelAssignApplications');
            this.showSuccessModal = false;
        },
        async fetchDuplicateLead() {
            this.applications = (await DuplicateLeadService.getDuplicateLeadData(this.duplicateGroupId)).data;
            this.loadTable    = false;
        },
        goToAllDuplicates() {
            let params = {duplication_group_id: this.duplicateGroupId, 'duplicates': true}
            this.$router.push({
                name : "applications",
                query: params
            });
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
