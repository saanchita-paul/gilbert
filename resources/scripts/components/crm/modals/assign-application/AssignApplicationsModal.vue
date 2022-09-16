<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            scrollable
            max-width="1024px"
            transition="dialog-bottom-transition"
        >
            <v-card max-height="600px">
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-toolbar-title>Assign applications to Offices and Agents</v-toolbar-title>
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

                <v-card-title>
                    <h3 class="text-large">What does this mean?</h3>
                </v-card-title>

                <v-card-subtitle class="mb-n8 mt-n2">
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
                                        v-for="(item, index) in assignedApplications"
                                        :key="item.id"
                                    >
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.tenant_name }}</td>
                                        <td>{{ item.address_text }}</td>
                                        <td>
                                            <v-btn color="primary" text icon class="ma-2"
                                                   @click="openOfficesModal(index)">
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

                <v-card-actions class="justify-end pa-6 mt-n5 pr-6">
                    <v-btn
                        @click="closeModal"
                        :disabled="isLoading"
                    >
                        Cancel
                    </v-btn>
                    <v-btn
                        :loading="isLoading"
                        :disabled="disabledAssignApplicationButton"
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
                      :selectedApplication="selectedApp"
                      @selectApplication="selectApplication"
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
import OfficesModal from "@scripts/components/crm/modals/assign-application/OfficesModal";
import AssignApplicationsConfirmModal
    from "@scripts/components/crm/modals/assign-application/AssignApplicationsConfirmModal";
import AssignedApplicationSuccessModal
    from "@scripts/components/crm/modals/assign-application/AssignedApplicationSuccessModal";
import AssignApplicationService from "@scripts/services/crm/AssignApplicationService";
import {cloneDeep} from "lodash-es";

export default {
    name: "AssignApplicationsModal",
    components: {
        OfficesModal,
        AssignApplicationsConfirmModal,
        AssignedApplicationSuccessModal,
    },
    props: {
        dialog: {
            require: true,
            type: Boolean,
        },
        applications: {
            require: true,
            type: Array,
        },
        assignApplicationsDisabled: {
            require: true,
            type: Boolean,
        },
    },
    data() {
        return {
            assignedApplications: [],
            loadTable: true,
            showOfficesModal: false,
            showConfirmModal: false,
            showSuccessModal: false,
            selectedApp: {},
            selectedIndex: null,
            isLoading: false,
        }
    },
    mounted() {
        this.assignedApplications = cloneDeep(this.applications);
    },
    computed: {
        // Assign application button disabled conditionally
        disabledAssignApplicationButton() {
            return this.assignedApplications.some(item => !item.is_selected && !item.created_by);
        },
    },

    methods: {
        // Reset the some data
        resetData() {
            this.selectedApp = {};
        },
        // Close assign applications modal
        closeModal() {
            this.assignedApplications = cloneDeep(this.applications);
            this.$emit('closeModalAssignApplicationModal');
        },
        // Open offices modal and set the selected application
        openOfficesModal(index) {
            this.selectedApp = this.assignedApplications[index];
            this.showOfficesModal = true;
        },
        // Close offices modal
        closeOfficesModal() {
            this.showOfficesModal = false;
        },
        // Open confirm modal
        openConfirmModal() {
            this.showConfirmModal = true;
        },
        // Close confirm modal
        closeConfirmModal() {
            this.showConfirmModal = false;
        },
        // Open success modal & save the selected applications
        async openSuccessModal() {
            // disabled and show loading for buttons
            this.isLoading = true;

            // Format selected applications to be sent to the backend
            let formattedSelectedApp = this.assignedApplications.map((item) => {
                if (item.is_selected) {
                    return {
                        id: item.id,
                        office_id: item.office_id,
                        created_by: item.created_by,
                    }
                }
            });

            // Close confirmation modal
            this.closeConfirmModal();

            // Save the selected applications by calling the service
            let data = await AssignApplicationService.saveSelectedApplications(formattedSelectedApp);

            // If response is success then open success modal
            if (data.success) {
                this.isLoading = false;
                this.showSuccessModal = true;
            }
        },
        // Close success modal
        closeSuccessModal() {
            this.showSuccessModal = false;
            this.closeModal();
            this.$emit('completeAssignApplications');
        },
        // Select application and push this into array
        selectApplication() {
            let index = this.assignedApplications.findIndex(dt => dt.id === this.selectedApp.id);
            this.assignedApplications[index] = this.selectedApp;
            this.resetData();
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
