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

                <v-alert v-if="isPowerError"
                         dense
                         border="left"
                         type="warning"
                         dismissible
                >
                    Power <strong>provider</strong> or <strong>plan</strong> is not available!
                </v-alert>

                <v-alert v-if="isGasError"
                         dense
                         border="left"
                         type="warning"
                         dismissible
                >
                    Gas <strong>provider</strong> or <strong>plan</strong> is not available!
                </v-alert>

                <v-alert v-if="this.isNullPowerAndGas"
                         dense
                         border="left"
                         type="warning"
                         dismissible
                >
                    <strong>Power</strong> or <strong>Gas</strong> must be submitted!
                </v-alert>

                <form @submit.prevent="openConfirmModal">
                    <ValidationObserver ref="application_status_change">
                        <v-card-text class="pa-5">
                            <v-row>
                                <v-col :cols="isShowCloseReason? '3' : '4'">
                                    <v-card-title class="text--primary ml-2">Item</v-card-title>
                                    <v-card-title class="text--primary mt-3">
                                        &nbsp;&nbsp;Application
                                    </v-card-title>

                                    <template v-if="activeConnectionServices.length"
                                              v-for="connection_service in activeConnectionServices">
                                        <v-card-title class="text--primary"
                                                      v-if="isActive(connection_service)">
                                            <v-icon size="20" :color="getServiceColor(connection_service)">
                                                {{ getIcon(connection_service) }}
                                            </v-icon>
                                            &nbsp;{{ getServiceTitle(connection_service) }}
                                        </v-card-title>
                                    </template>
                                </v-col>

                                <v-col :cols="isShowCloseReason? '3' : '4'">
                                    <v-card-title class="text--primary ml-n4">Current Status</v-card-title>
                                    <br>
                                    <v-text-field :placeholder="application_status_display_text" readonly outlined
                                                  dense></v-text-field>
                                    <template v-if="activeConnectionServices.length"
                                              v-for="connection_service in activeConnectionServices">

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

                                    <template v-if="activeConnectionServices.length"
                                              v-for="connection_service in activeConnectionServices">
                                        <div class="text-field margin-bottom-26"
                                             v-if="connection_service === 'power'">
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

                                        <div class="text-field margin-bottom-26"
                                             v-else-if="connection_service === 'gas'">
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

                                        <div class="text-field margin-bottom-26"
                                             v-else-if="connection_service === 'water'">
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

                                        <div class="text-field" v-else>
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
                                                    :items="internetStatusDD"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                >
                                                </v-select>
                                            </ValidationProvider>
                                        </div>
                                    </template>
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

        <StatusChangeConfirmationModal v-if="showConfirmModal"
                                       :dialog="showConfirmModal"
                                       @submitStatus="submitHandler"
                                       @closeConfirmModal="closeConfirmModal" :is-loading="isLoading"/>
    </v-row>
</template>

<script>
import ApplicationServiceStatusChangeService from "@scripts/services/crm/ApplicationServiceStatusChangeService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import leadApplicationService from "@scripts/services/crm/LeadApplicationService";
import {upperFirst} from "lodash-es";
import AppCloseReasonService from "@scripts/services/AppCloseReasonService";
import StatusChangeConfirmationModal
    from "@scripts/components/crm/modals/application-service-status/StatusChangeConfirmationModal";

export default {
    name: "ApplicationServiceStatusModal",
    components: {StatusChangeConfirmationModal},
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
                application_status: null,
                status_reason: null,
                closed_reason: null,
                power_status: null,
                gas_status: null,
                water_status: null,
                internet_status: null,
            },
            oldStatus: {
                application_status: null,
            },
            statusDD: [],
            powerStatusDD: [],
            gasStatusDD: [],
            waterStatusDD: [],
            internetStatusDD: [],
            closeReasons: [],
            showConfirmModal: false,
            activeConnectionServices: [],
            formDataStatuses: [],
            isPreviousData: true,
            isPowerError: false,
            isGasError: false
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
            let excludeStatus = [1, 7];
            return this.statusDD.filter(status => status.type === 'application' && !excludeStatus.includes(status.status_value));
        },
        isShowCloseReason() {
            return this.formData.application_status === 8;
        },
        isInvalidProviderPlan() {
            return this.isPowerError && this.isGasError;
        },
        isNullPowerAndGas() {
            return this.formData.power_status === null && this.formData.gas_status === null;
        },
        isNullData() {
            return this.formDataStatuses.every(status => this.formData[status] === null);
        },
        isInvalidData() {
            return this.isNullData || this.isPreviousData || this.isInvalidProviderPlan || this.isNullPowerAndGas;
        },
        closeReasonDD() {
            return this.closeReasons.filter(reason => reason.value !== 17);
        },
    },
    async mounted() {
        await this.getInitData();
    },
    watch: {
        "formData.power_status": function () {
            if (this.formData.power_status !== this.oldStatus.power_status) {
                this.isPreviousData = false;
            }
        },
        "formData.gas_status": function () {
            if (this.formData.gas_status !== this.oldStatus.gas_status) {
                this.isPreviousData = false;
            }
        },
        "formData.water_status": function () {
            if (this.formData.water_status !== this.oldStatus.water_status) {
                this.isPreviousData = false;
            }
        },
        "formData.internet_status": function () {
            if (this.formData.internet_status !== this.oldStatus.internet_status) {
                this.isPreviousData = false;
            }
        },
        "formData.application_status": function () {
            if (this.formData.application_status !== this.oldStatus.application_status) {
                this.isPreviousData = false;
            }
        },
    },
    methods: {
        async getInitData() {
            this.statusDD = await ApplicationServiceStatusChangeService.getAllStatus();

            this.formData.application_status = this.leadSummary.status_value;

            // old status
            this.setOldStatus();

            await this.applicationStatusChangeHandler();

            this.closeReasons = await AppCloseReasonService.getAppCloseReasonData();
            const sortOrder = ['power', 'gas', 'water', 'internet'];
            this.activeConnectionServices = this.leadSummary.service_interests
                .sort((a, b) => sortOrder.indexOf(a) - sortOrder.indexOf(b));
            this.formDataStatuses = Object.keys(this.formData).filter(key => key.includes('_status'));

            this.removeFormdataProperty();
        },

        setOldStatus() {
            this.oldStatus.application_status = this.leadSummary.status_value;
            this.leadSummary.connection_services.forEach(service => {
                this.oldStatus[`${service.service_type}_status`] = service.status;
                this.formData[`${service.service_type}_status`] = service.status;
            });
        },

        removeFormdataProperty() {
            if (!this.leadSummary.service_interests.includes('power')) {
                delete this.formData.power_status;
            }

            if (!this.leadSummary.service_interests.includes('gas')) {
                delete this.formData.gas_status;
            }

            if (!this.leadSummary.service_interests.includes('water')) {
                delete this.formData.water_status;
            }

            if (!this.leadSummary.service_interests.includes('internet')) {
                delete this.formData.internet_status;
            }
        },

        async getServiceStatusDD(service_type, new_status) {
            let service_id = this.getServiceStatusId(service_type);
            const formdata = {
                application_status: this.formData.application_status,
                service_id: service_id,
                new_status,
            };
            return await ApplicationServiceStatusChangeService.getServiceStatusDD(formdata);
        },

        async getWaterServiceStatusDD() {
            const formdata = {
                application_status: this.formData.application_status
            };
            this.waterStatusDD = await ApplicationServiceStatusChangeService.getWaterServiceStatusDD(formdata);
        },

        async getInternetServiceStatusDD() {
            const formdata = {
                application_status: this.formData.application_status
            };
            this.internetStatusDD = await ApplicationServiceStatusChangeService.getInternetServiceStatusDD(formdata);
        },

        async applicationStatusChangeHandler() {
            this.checkPlanAndProvider();

            console.log(this.oldStatus);

            if (this.leadSummary.service_interests.includes('power')) {
                this.powerStatusDD = await this.getServiceStatusDD('power', this.oldStatus.power_status);
            }

            if (this.leadSummary.service_interests.includes('gas')) {
                this.gasStatusDD = await this.getServiceStatusDD('gas', this.oldStatus.gas_status);
            }

            if (this.leadSummary.service_interests.includes('internet')) {
                await this.getInternetServiceStatusDD();
            }

            if (this.leadSummary.service_interests.includes('water')) {
                await this.getWaterServiceStatusDD();
            }

            this.setNullPowerAndGas();
        },
        checkPlanAndProvider() {
            // check provider and plan is available
            const allowableStatus = [3, 4, 5, 6, 8];
            if (allowableStatus.includes(this.formData.application_status)) {
                this.leadSummary.connection_services.forEach(service => {
                    if (service.service_type === 'power') {
                        this.isPowerError = service.provider_name === null || service.plan_type === null;
                    }

                    if (service.service_type === 'gas') {
                        this.isGasError = service.provider_name === null || service.plan_type === null;
                    }
                });
            }
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
            return !!status;
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
        setNullPowerAndGas() {
            if (this.formData.application_status === 4 && this.oldStatus.power_status === 7) {
                this.formData.power_status = null;
            }
            if (this.formData.application_status === 4 && this.oldStatus.gas_status === 7) {
                this.formData.gas_status = null;
            }
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
                const data = await ApplicationServiceStatusChangeService.updateStatus(this.leadSummary.id, this.formData);
                if (data.success) {
                    this.closeModal();
                    this.$emit('reloadPlanNoteAndLead');
                    this.$eventBus.$emit("manual_status_changed");
                    this.closeConfirmModal();
                }
            } catch (err) {
                console.log(err.response.data);
            } finally {
                this.isLoading = false;
            }
        },
        openConfirmModal() {
            this.showConfirmModal = true;
        },
        // Close confirm modal
        closeConfirmModal() {
            this.showConfirmModal = false;
        },
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
