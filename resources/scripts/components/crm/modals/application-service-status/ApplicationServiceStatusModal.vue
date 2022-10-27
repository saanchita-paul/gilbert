<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            scrollable
            max-width="850"
            transition="dialog-bottom-transition"
        >
            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-toolbar-title>Application Status</v-toolbar-title>
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

                <form @submit.prevent="submitHandler">
                    <ValidationObserver ref="application_status_change">
                        <v-card-text class="pa-5">
                            <v-row>
                                <v-col cols="4">
                                    <v-card-title class="text--primary ml-2">Item</v-card-title>
                                    <v-card-title class="text--primary mt-3">
                                        &nbsp;&nbsp;Application
                                    </v-card-title>

                                    <v-card-title class="text--primary">
                                        <v-icon size="20" :color="getEnergyColor('Power')">mdi-flash</v-icon>
                                        &nbsp;Power
                                    </v-card-title>

                                    <v-card-title class="text--primary">
                                        <v-icon size="20" :color="getEnergyColor('Gas')">mdi-fire</v-icon>
                                        &nbsp;Gas
                                    </v-card-title>

                                    <v-card-title class="text--primary">
                                        <v-icon size="20" :color="getColor('Water')">mdi-water</v-icon>
                                        &nbsp;Water
                                    </v-card-title>

                                    <v-card-title class="text--primary">
                                        <v-icon size="20" :color="getColor('Internet')">mdi-wifi</v-icon>
                                        &nbsp;Internet
                                    </v-card-title>
                                </v-col>

                                <v-col cols="4">
                                    <v-card-title class="text--primary ml-n4">Current Status</v-card-title>
                                    <br>
                                    <v-text-field :placeholder="application_status_display_text" readonly outlined
                                                  dense></v-text-field>
                                    <v-text-field :placeholder="power_status_display_text" readonly outlined
                                                  dense></v-text-field>
                                    <v-text-field :placeholder="gas_status_display_text" readonly outlined
                                                  dense></v-text-field>
                                    <v-text-field :placeholder="water_status_display_text" readonly outlined
                                                  dense></v-text-field>
                                    <v-text-field :placeholder="internet_status_display_text" readonly outlined
                                                  dense></v-text-field>
                                </v-col>

                                <v-col cols="4">
                                    <v-card-title class="text--primary ml-n4">New Status</v-card-title>
                                    <br>
                                    <div class="text-field margin-bottom-26">
                                        <ValidationProvider
                                            name="Application status"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                placeholder="Please select"
                                                v-model="formData.application_status"
                                                :error-messages="errors[0]"
                                                item-text="text"
                                                item-value="value"
                                                :items="applicationStatusDD"
                                                outlined
                                                dense
                                                hide-details="auto"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>

                                    <div class="text-field margin-bottom-26">
                                        <ValidationProvider
                                            name="Power status"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                placeholder="Please select"
                                                v-model="formData.power_status"
                                                :error-messages="errors[0]"
                                                item-text="text"
                                                item-value="value"
                                                :items="serviceStatusDD"
                                                outlined
                                                dense
                                                hide-details="auto"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>

                                    <div class="text-field margin-bottom-26">
                                        <ValidationProvider
                                            name="Gas status"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                placeholder="Please select"
                                                v-model="formData.gas_status"
                                                :error-messages="errors[0]"
                                                item-text="text"
                                                item-value="value"
                                                :items="serviceStatusDD"
                                                outlined
                                                dense
                                                hide-details="auto"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>

                                    <div class="text-field margin-bottom-26">
                                        <ValidationProvider
                                            name="Water status"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                placeholder="Please select"
                                                v-model="formData.water_status"
                                                :error-messages="errors[0]"
                                                item-text="text"
                                                item-value="value"
                                                :items="serviceStatusDD"
                                                outlined
                                                dense
                                                hide-details="auto"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>

                                    <div class="text-field">
                                        <ValidationProvider
                                            name="Internet status"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                placeholder="Please select"
                                                v-model="formData.internet_status"
                                                :error-messages="errors[0]"
                                                item-text="text"
                                                item-value="value"
                                                :items="serviceStatusDD"
                                                outlined
                                                dense
                                                hide-details="auto"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <v-card-title class="text--primary">
                                        Status Change Reason*
                                    </v-card-title>

                                    <ValidationProvider name="Status reason"
                                                        v-slot="{ errors }">
                                        <v-textarea
                                            class="pa-3"
                                            v-model="formData.status_reason"
                                            :error-messages="errors[0]"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            placeholder="Please type reason here..."
                                        ></v-textarea>
                                    </ValidationProvider>
                                </v-col>
                            </v-row>
                        </v-card-text>

                        <v-card-actions class="justify-end pa-6 mt-n5 pr-6">
                            <v-btn
                                class="font-weight-bolder"
                                rounded
                                @click="closeModal"
                                :disabled="isLoading"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                class="font-weight-bolder"
                                rounded
                                :loading="isLoading"
                                color="primary"
                                type="submit"
                            >
                                Update Status
                            </v-btn>
                        </v-card-actions>
                    </ValidationObserver>
                </form>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import ApplicationServiceStatusChangeService from "@scripts/services/crm/ApplicationServiceStatusChangeService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import leadApplicationService from "@scripts/services/crm/LeadApplicationService";
import {upperFirst} from "lodash-es";

export default {
    name: "ApplicationServiceStatusModal",
    props: {
        dialog: {
            required: true,
            type: Boolean,
        },
        leadSummary: {
            required: true,
            type: Object,
        },
    },
    data() {
        return {
            isLoading: false,
            formData: {
                application_id: null,
                application_status: null,
                power_status: null,
                gas_status: null,
                water_status: null,
                internet_status: null,
                status_reason: null,
            },
            statusDD: [],
        }
    },
    async mounted() {
        await this.getInitData();
    },
    computed: {
        application_status_display_text() {
            return upperFirst(this.leadSummary.status);
        },
        power_status_display_text() {
            return this.getEnergyServiceStatus('power').text;
        },
        gas_status_display_text() {
            return this.getEnergyServiceStatus('gas').text;
        },
        water_status_display_text() {
            return this.getServiceStatus('water').text;
        },
        internet_status_display_text() {
            return this.getServiceStatus('internet').text;
        },
        applicationStatusDD() {
            return this.statusDD.filter(status => status.type === 'application');
        },
        serviceStatusDD() {
            return this.statusDD.filter(status => status.type === 'service');
        },
    },

    methods: {
        async getInitData() {
            this.statusDD = await ApplicationServiceStatusChangeService.getAllStatus();

            this.formData.application_id = this.leadSummary.id;
            this.formData.application_status = this.leadSummary.status_value;
            this.formData.power_status = this.getServiceStatusValue('power');
            this.formData.gas_status = this.getServiceStatusValue('gas');
            this.formData.water_status = this.getServiceStatusValue('water');
            this.formData.internet_status = this.getServiceStatusValue('internet');
        },

        getServiceStatusValue(service) {
            return this.leadSummary.connection_services.find(connectionService => connectionService.service_type === service)?.status;
        },

        // Close assign applications modal
        closeModal() {
            // this.assignedApplications = cloneDeep(this.applications);
            this.$emit('closeStatusChangeModal');
        },

        isActive(service) {
            return this.leadSummary.service_interests.includes(service.toLowerCase()) ? true : false;
        },

        isEnergyActive(service) {
            let status = service.toLowerCase() === 'power' ? UtilityStoreService.getPowerStatus() : UtilityStoreService.getGasStatus();
            return status ? true : false;
        },

        getColor(service) {
            if (this.isActive(service)) {
                if (service.toLowerCase() === 'power') {
                    return 'yellow';
                }
                if (service.toLowerCase() === 'gas') {
                    return 'orange';
                }
                if (service.toLowerCase() === 'internet') {
                    return '#9C27B0';
                }
                if (service.toLowerCase() === 'water') {
                    return 'blue';
                }
            }
            return 'grey lighten-1';
        },

        getEnergyColor(service) {
            if (this.isEnergyActive(service)) {
                if (service.toLowerCase() === 'power') {
                    return 'yellow';
                }
                if (service.toLowerCase() === 'gas') {
                    return 'orange';
                }
            }
            return 'grey lighten-1';
        },

        getServiceStatus(conn_ser) {
            return LeadApplicationService.mapStatus(leadApplicationService
                .getServiceObj(this.leadSummary.connection_services, conn_ser)?.status);
        },

        getEnergyServiceStatus(service) {
            let status = service.toLowerCase() === 'power' ?
                UtilityStoreService.getPowerStatus() : UtilityStoreService.getGasStatus();
            return LeadApplicationService.mapStatus(status);
        },

        mapConnectionStatus(status) {
            return LeadApplicationService.mapStatus(status)
        },
        async submitHandler() {
            console.log('Status change form submitted....');
            try {
                this.isLoading = true;
                const data = await ApplicationServiceStatusChangeService.updateStatus(this.formData);
                if (data.success) {
                    this.closeModal();
                    this.$emit('reloadPlanNoteAndLead');
                }
            } catch (err) {
                console.log(err.response.data);
            } finally {
                this.isLoading = false;
            }
        }
    }
}
</script>

<style scoped>
.margin-bottom-26 {
    margin-bottom: 26px;
}
</style>
