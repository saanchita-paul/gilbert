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
                                <v-col :cols="isShowCloseReason? '3' : '4'">
                                    <v-card-title class="text--primary ml-2">Item</v-card-title>
                                    <v-card-title class="text--primary mt-3">
                                        &nbsp;&nbsp;Application
                                    </v-card-title>

                                    <template v-if="leadSummary.connection_services.length"
                                              v-for="connection_service in leadSummary.connection_services">
                                        <v-card-title class="text--primary"
                                                      v-if="isActive(connection_service.service_type)">
                                            <v-icon size="20" :color="getServiceColor(connection_service.service_type)">
                                                {{ getIcon(connection_service.service_type) }}
                                            </v-icon>
                                            &nbsp;{{ getServiceTitle(connection_service.service_type) }}
                                        </v-card-title>
                                    </template>
                                </v-col>

                                <v-col :cols="isShowCloseReason? '3' : '4'">
                                    <v-card-title class="text--primary ml-n4">Current Status</v-card-title>
                                    <br>
                                    <v-text-field :placeholder="application_status_display_text" readonly outlined
                                                  dense></v-text-field>
                                    <template v-if="leadSummary.service_interests.length"
                                              v-for="connection_service in leadSummary.service_interests">

                                        <v-text-field :placeholder="getServiceStatusPlaceholder(connection_service)"
                                                      readonly outlined
                                                      dense></v-text-field>
                                    </template>
                                </v-col>

                                <v-col :cols="isShowCloseReason? '3' : '4'">
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
                                                @change="applicationStatusChangeHandler"
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
                                                :items="powerStatusDD"
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
                                                :items="gasStatusDD"
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
                                                :items="waterStatusDD"
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
                                                :items="waterStatusDD"
                                                outlined
                                                dense
                                                hide-details="auto"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>
                                </v-col>

                                <v-col cols="3" v-if="isShowCloseReason">
                                    <v-card-title class="text--primary ml-n4">Close Reason</v-card-title>
                                    <br>
                                    <div class="text-field margin-bottom-26">
                                        <ValidationProvider
                                            name="Close Reason status"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                placeholder="Please select"
                                                v-model="formData.closed_reason"
                                                :error-messages="errors[0]"
                                                item-text="text"
                                                item-value="value"
                                                :items="closeReasonDD"
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
                                :disabled="isInvalidData"
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
import AppCloseReasonService from "@scripts/services/AppCloseReasonService";

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
                closed_reason: null,
            },
            oldStatus: {
                application_status: null,
                power_status: null,
                gas_status: null,
                water_status: null,
                internet_status: null,
            },
            statusDD: [],
            powerStatusDD: [],
            gasStatusDD: [],
            waterStatusDD: [],
            closeReasons: [],
        }
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
            let excludeStatus = [1];
            return this.statusDD.filter(status => status.type === 'application' && !excludeStatus.includes(status.status_value));
        },
        isShowCloseReason() {
            return this.formData.application_status === 8;
        },
        isNullData() {
            return this.formData.application_status === null
                && this.formData.power_status === null
                && this.formData.gas_status === null
                && this.formData.water_status === null
                && this.formData.internet_status === null
        },
        isPreviousData() {
            return this.formData.application_status === this.oldStatus.application_status
                && this.formData.power_status === this.oldStatus.power_status
                && this.formData.gas_status === this.oldStatus.gas_status
                && this.formData.water_status === this.oldStatus.water_status
                && this.formData.internet_status === this.oldStatus.internet_status;
        },
        isInvalidData() {
            return this.isNullData || this.isPreviousData;
        },
        closeReasonDD() {
            return this.closeReasons.filter(reason => reason.value !== 17);
        },
    },
    async mounted() {
        await this.getInitData();
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

            // old status
            this.oldStatus.application_status = this.leadSummary.status_value;
            this.oldStatus.power_status = this.getServiceStatusValue('power');
            this.oldStatus.gas_status = this.getServiceStatusValue('gas');
            this.oldStatus.water_status = this.getServiceStatusValue('water');
            this.oldStatus.internet_status = this.getServiceStatusValue('internet');

            this.powerStatusDD = await this.getServiceStatusDD('power', this.formData.power_status);
            this.gasStatusDD = await this.getServiceStatusDD('gas', this.formData.gas_status);

            await this.getWaterServiceSTatusDD();

            this.closeReasons = await AppCloseReasonService.getAppCloseReasonData();
        },

        async getServiceStatusDD(service_type, new_status) {
            let service_id = this.getServiceStatusId(service_type);
            const formdata = {
                application_status: this.formData.application_status,
                service_id: service_id,
                new_status,
            };
            const data = await ApplicationServiceStatusChangeService.getServiceStatusDD(formdata);
            return data;
        },

        async getWaterServiceSTatusDD() {
            this.waterStatusDD = await ApplicationServiceStatusChangeService.getWaterServiceStatusDD();
        },

        async applicationStatusChangeHandler() {
            this.powerStatusDD = await this.getServiceStatusDD('power', this.formData.power_status);
            this.gasStatusDD = await this.getServiceStatusDD('gas', this.formData.gas_status);

            await this.getWaterServiceSTatusDD();
        },

        getServiceStatusValue(service) {
            return this.leadSummary.connection_services.find(connectionService => connectionService.service_type === service)?.status;
        },

        getServiceStatusId(service) {
            return this.leadSummary.connection_services.find(connectionService => connectionService.service_type === service)?.id;
        },

        // Close assign applications modal
        closeModal() {
            // this.assignedApplications = cloneDeep(this.applications);
            this.$emit('closeStatusChangeModal');
        },

        isActive(service) {
            return this.leadSummary.service_interests.includes(service.toLowerCase());
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

        getIcon(service) {
            if (this.isActive(service)) {
                if (service.toLowerCase() === 'power') {
                    return 'mdi-flash';
                }
                if (service.toLowerCase() === 'gas') {
                    return 'mdi-fire';
                }
                if (service.toLowerCase() === 'internet') {
                    return 'mdi-wifi';
                }
                if (service.toLowerCase() === 'water') {
                    return 'mdi-water';
                }
            }
            return 'mdi-flash';
        },

        getServiceTitle(service) {
            if (this.isActive(service)) {
                if (service.toLowerCase() === 'power') {
                    return 'Power';
                }
                if (service.toLowerCase() === 'gas') {
                    return 'Gas';
                }
                if (service.toLowerCase() === 'internet') {
                    return 'Internet';
                }
                if (service.toLowerCase() === 'water') {
                    return 'Water';
                }
            }
            return 'Power';
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

        getServiceColor(service) {
            if (service.toLowerCase() === 'power' || service.toLowerCase() === 'gas') {
                return this.getEnergyColor(service);
            }
            return this.getColor(service);
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
        getServiceStatusPlaceholder(service) {
            if (this.isActive(service)) {
                if (service.toLowerCase() === 'power') {
                    return this.power_status_display_text;
                }
                if (service.toLowerCase() === 'gas') {
                    return this.gas_status_display_text;
                }
                if (service.toLowerCase() === 'internet') {
                    return this.internet_status_display_text;
                }
                if (service.toLowerCase() === 'water') {
                    return this.water_status_display_text;
                }
            }
            return 'grey lighten-1';
        },
        setNullInFormData() {
            this.formData.application_status = this.formData.application_status === this.oldStatus.application_status ? null : this.formData.application_status;
            this.formData.power_status = this.formData.power_status === this.oldStatus.power_status ? null : this.formData.power_status;
            this.formData.gas_status = this.formData.gas_status === this.oldStatus.gas_status ? null : this.formData.gas_status;
            this.formData.water_status = this.formData.water_status === this.oldStatus.water_status ? null : this.formData.water_status;
            this.formData.internet_status = this.formData.internet_status === this.oldStatus.internet_status ? null : this.formData.internet_status;
        },
        async submitHandler() {
            console.log('Status change form submitted....');
            try {
                this.isLoading = true;
                await this.setNullInFormData();
                const data = await ApplicationServiceStatusChangeService.updateStatus(this.formData);
                if (data.success) {
                    this.closeModal();
                    this.$emit('reloadPlanNoteAndLead');
                    this.$eventBus.$emit("manual_status_changed");
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

.v-dialog > * {
    height: 100% !important;
}
</style>
