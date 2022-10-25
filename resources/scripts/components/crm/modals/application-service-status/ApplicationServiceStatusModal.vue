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
                        <v-card-text>
                            <v-row>
                                <v-col cols="4">
                                    <v-card-title class="text--primary">Item</v-card-title>
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
                                    <v-card-title class="text--primary">Current Status</v-card-title>
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
                                    <v-card-title class="text--primary">New Status</v-card-title>
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

                                <!--                                <v-col cols="12">
                                                                    <v-card-title class="text&#45;&#45;primary">
                                                                        Status Change Reason*
                                                                    </v-card-title>

                                                                    <ValidationProvider name="Status reason"
                                                                                        v-slot="{ errors }">
                                                                        <v-textarea
                                                                            :error-messages="errors[0]"
                                                                            outlined
                                                                            dense
                                                                            hide-details="auto"
                                                                            placeholder="Please type reason here..."
                                                                        ></v-textarea>
                                                                    </ValidationProvider>
                                                                </v-col>-->
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
import {upperFirst, snakeCase} from "lodash-es";

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
            applicationStatusDD: [],
            serviceStatusDD: [],
        }
    },
    async mounted() {
        await this.getInitData();
    },
    computed: {
        application_status_display_text() {
            return upperFirst(this.formData.application_status);
        },
        power_status_display_text() {
            return upperFirst(this.formData.power_status.replace('_', ' '));
        },
        gas_status_display_text() {
            return upperFirst(this.formData.gas_status.replace('_', ' '));
        },
        water_status_display_text() {
            return upperFirst(this.formData.water_status.replace('_', ' '));
        },
        internet_status_display_text() {
            return upperFirst(this.formData.internet_status.replace('_', ' '));
        },
    },

    methods: {
        async getInitData() {
            this.applicationStatusDD = await ApplicationServiceStatusChangeService.getAllStatus();
            this.serviceStatusDD = await ApplicationServiceStatusChangeService.getAllStatus('service');

            this.formData.application_id = this.leadSummary.id;
            this.formData.application_status = this.leadSummary.status.toLowerCase();
            this.formData.power_status = snakeCase(this.getEnergyServiceStatus('power').text);
            this.formData.gas_status = snakeCase(this.getEnergyServiceStatus('gas').text);
            this.formData.water_status = snakeCase(this.getServiceStatus('water').text);
            this.formData.internet_status = snakeCase(this.getServiceStatus('internet').text);
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
            return LeadApplicationService.mapStatus(leadApplicationService.getServiceObj(this.leadSummary.connection_services, conn_ser)?.status);
        },

        getEnergyServiceStatus(service) {
            let status = service.toLowerCase() === 'power' ? UtilityStoreService.getPowerStatus() : UtilityStoreService.getGasStatus();
            return LeadApplicationService.mapStatus(status);
        },

        mapConnectionStatus(status) {
            return LeadApplicationService.mapStatus(status)
        },
        submitHandler() {
            console.log('Status change form submitted....');
        }
    }
}
</script>

<style scoped>
.margin-bottom-26 {
    margin-bottom: 26px;
}
</style>
