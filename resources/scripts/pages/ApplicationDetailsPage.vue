<template>
    <v-container fluid v-if="planNoteFlag">
        <ValidationObserver ref="submit_lead">
            <LeadUserDetails
                v-model="infoToPass"
                :nmiMernFlag="nmiMernFlag"
                :services="services"
                @closeApplicationWithReason="closeApplicationWithReason"
                @closeApplication="closeApplication"
                @sendToChatBotConfirmModal="sendToChatBotConfirmModal"
                @sendToChatBot="sendToChatBot"
                @eacalate="eacalate"
                @updateLead="updateLead"
                @readMore="readMore"
                :leadSummary="leadSummary"
                @updateAddress="updateAddress"
                @loadPlanNoteAndLead="loadPlanNoteAndLead"
                @updateDraft="updateDraft"
                @duplicateLead="duplicatedLead"
                :isLocked="isLocked"
            ></LeadUserDetails>
        </ValidationObserver>

        <ValidationObserver ref="service_form">
            <LeadServicesAndNotes
                @updateDraft="updateDraft"
                @updateNote="updateNote"
                :leadSummary="leadSummary"
                :afterHourFlag="afterHourFlag"
                :notes="notes"
                @serviceType="serviceType">
            </LeadServicesAndNotes>
        </ValidationObserver>

        <EscalateReasonModal v-if="escalateLead" :dialog="escalateLead" :leadSummary="leadSummary"
                             @cancelEscal="cancelEscal" @sucessSaveEscal="sucessSaveEscal"></EscalateReasonModal>
        <EscalationConfirmModal v-if="escalateLeadConfirm" :dialog="escalateLeadConfirm"
                                :title="fullName"></EscalationConfirmModal>

        <CloseApplicationReasonModal v-if="closeLead" :dialog="closeLead" :leadSummary="leadSummary"
                                     @closeApplicationWithReason="closeApplicationWithReason" @cancelClose="cancelClose"
                                     @sucessSaveClose="sucessSaveClose"></CloseApplicationReasonModal>

        <CloseConfirmModal v-if="closeConfirm" :dialog="closeConfirm" :title="fullName"></CloseConfirmModal>

        <SendToChatBotModal v-if="closeSentConfirm" :dialog="closeSentConfirm" :title="fullName"
                            @done="done"></SendToChatBotModal>

        <LeadReadMoreModal v-if="readMoreFlag" :dialog="readMoreFlag"
                           :readmore="additionalInstruction"
                           @close="closeReadMore"></LeadReadMoreModal>

        <AssignedToUserEmptyModal v-if="assignedToDialog" :dialog="assignedToDialog"
                                  @closeMessage="closeAssignedToEmptyModal"></AssignedToUserEmptyModal>

        <GasOnlyCanNotSubmitModal v-if="gasOnlyNotSubmitDialog" :dialog="gasOnlyNotSubmitDialog"
                                  @closeMessage="closegasOnlyNotSubmitModal"></GasOnlyCanNotSubmitModal>

        <LeadSubmitConfirmationModal :dialog="showSubmitModal" :data="payload" :leadId="leadId"
                                     secondaryContact="secondaryContact" v-if="showSubmitModal"
                                     @confirmSubmitLead="confirmSubmitLead" @backToEdit="backToEdit"
                                     :submitType="submitType" :leadSummary="leadSummary"></LeadSubmitConfirmationModal>
        <PreventSubmissionModal v-if="preventSubmissionFlag" :message="preventSubmissionMessage"
                                :dialog="preventSubmissionFlag"
                                @closeMessage="closePreventSubmissionModal"></PreventSubmissionModal>

        <DuplicateLeadModal v-if="duplicateLead" :dialog="duplicateLead" @cancelDuplicateLead="cancelDuplicateLead"
                            :duplicateGroupId="leadSummary.duplication_group_id"></DuplicateLeadModal>

        <ApplicationUnlockModal v-if="isChatBotApplication" :dialog="isChatBotApplication"
                                @openUnlockConfirmModal="openUnlockConfirmModal"
                                @escalate="eacalate" @closeApp="closeApplicationWithReason"></ApplicationUnlockModal>
        <ApplicationUnlockConfirmModal v-if="showUnlockConfirmModal" :dialog="showUnlockConfirmModal"
                                       @closeUnlockConfirmModal="closeUnlockConfirmModal"
                                       @confirmUnlock="unlockApp"></ApplicationUnlockConfirmModal>
        <SendToChatbotConfirmModal v-if="sentToChabotConfirmModal"
                                   :dialog="sentToChabotConfirmModal"
                                   @continueSendToChatBot="lockApp"
                                   @cancelSendToChatBotConfirmModal="cancelSendToChatBotConfirmModal"></SendToChatbotConfirmModal>
    </v-container>
</template>

<script>
import LeadUserDetails from "@scripts/components/crm/leadmanagement/LeadUserDetails";
import LeadServicesAndNotes from "@scripts/components/crm/leadmanagement/LeadServicesAndNotes";
import LeadsDetailsFotter from "@scripts/components/crm/leadmanagement/LeadsDetailsFotter";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import EscalateReasonModal from "@scripts/components/crm/modals/EscalateReasonModal";
import CloseApplicationReasonModal from "@scripts/components/crm/modals/CloseApplicationReasonModal";
import CloseConfirmModal from "@scripts/components/crm/modals/CloseConfirmModal";
import EscalationConfirmModal from "@scripts/components/crm/modals/EscalationConfirmModal";
import LeadReadMoreModal from "@scripts/components/crm/modals/LeadReadMoreModal";
import LeadSubmitConfirmationModal from "@scripts/components/crm/modals/LeadSubmitConfirmationModal";
import AssignedToUserEmptyModal from "@scripts/components/crm/modals/AssignedToUserEmptyModal";
import GasOnlyCanNotSubmitModal from "@scripts/components/crm/modals/GasOnlyCanNotSubmitModal";
import * as dayjs from "dayjs";
import {isNull} from "lodash-es";
import PreventSubmissionModal from "@scripts/components/crm/modals/PreventSubmissionModal";
import EAAfterHourService from "@scripts/services/ea/EAAfterHourService";
import ChatbotService from "@scripts/services/crm/ChatbotService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";
import Store from '@scripts/store/index';
import SendToChatBotModal from "@scripts/components/crm/modals/SendToChatBotModal";
import DuplicateLeadModal from "@scripts/components/crm/modals/DuplicateLeadModal";
import ApplicationUnlockModal from "@scripts/components/crm/modals/ApplicationUnlockModal";
import ApplicationUnlockConfirmModal from "@scripts/components/crm/modals/ApplicationUnlockConfirmModal";
import SendToChatbotConfirmModal from "@scripts/components/crm/modals/SendToChatbotConfirmModal";


export default {
    //todo shift afterHourFlag, nextBusinessDay, getElectricityDistributor to powerService
    name: "ApplicationDetailsPage",
    components: {
        SendToChatBotModal,
        LeadReadMoreModal,
        EscalationConfirmModal,
        EscalateReasonModal,
        LeadsDetailsFotter,
        LeadServicesAndNotes,
        LeadUserDetails,
        LeadSubmitConfirmationModal,
        CloseApplicationReasonModal,
        CloseConfirmModal,
        AssignedToUserEmptyModal,
        PreventSubmissionModal,
        GasOnlyCanNotSubmitModal,
        DuplicateLeadModal,
        ApplicationUnlockModal,
        ApplicationUnlockConfirmModal,
        SendToChatbotConfirmModal
    },

    data() {
        return {
            afterHourEffectedField: ['moving_date', 'plan_type'],
            nmiMernFlag: true,
            leadId: null,
            leadSummary: null,
            notes: null,
            planNoteFlag: false,
            escalateLead: false,
            closeConfirm: false,
            escalateLeadConfirm: false,
            closeLead: false,
            readMoreFlag: false,
            additionalInstruction: null,
            lead: null,
            plan: null,
            supplier: 'ea',
            services: [],
            showSubmitModal: false,
            payload: null,
            fullName: null,
            submittedLoader: false,
            submitType: null,
            assignedToDialog: false,
            preventSubmissionFlag: false,
            preventSubmissionMessage: '',
            ea_service_type: 'electricity_and_gas',
            eaElectricityDistributor: '',
            infoToPass: {
                lifeSupportInfo: {
                    value: false,
                    errorMsg: false,
                }
            },
            nextBusinessDay: null,
            gasOnlyNotSubmitDialog: false,
            serviceSubmitType: null,
            closeSentConfirm: false,
            duplicateLead: false,
            isChatBotApplication: false,
            showUnlockConfirmModal: false,
            sentToChabotConfirmModal: false,
            chatbotData: {
                is_sent_to_chatbot: false,
                chatbot_id: null,
                is_locked: false,
            },
        }
    },
    computed: {
        afterHourFlag() {
            return this.powerPlan && EAAfterHourService.calculateAfterHourFlag(this.eaElectricityDistributor,
                this.leadSummary.moving_date, this.leadSummary.state, this.nextBusinessDay);
        },
        isBothEnergySubmit() {
            return UtilityStoreService.getIsBothEnergySelected();
        },
        powerProvider() {
            return UtilityStoreService.getPowerProvider();
        },
        gasProvider() {
            return UtilityStoreService.getGasProvider();
        },
        powerPlan() {
            return UtilityStoreService.getPowerPlan();
        },
        gasPlan() {
            return UtilityStoreService.getGasPlan();
        },
        isLocked() {
            return this.chatbotData.chatbot_id !== null && !this.chatbotData.is_locked;
        },

    },
    methods: {
        async getElectricityDistributor() {
            if (!isNull(this.powerPlan) && this.powerProvider === 'ea') {
                this.eaElectricityDistributor = await EAAfterHourService.getElectricityDistributor(this.leadSummary.service_interests,
                    this.powerPlan, this.leadSummary?.postcode, this.leadSummary?.state);
            }
        },
        async loadPlanNoteAndLead() {
            this.notes = await LeadApplicationService.loadNote(this.leadId);
            this.leadSummary = await LeadApplicationService.loadUserLead(this.leadId);
            this.lead = this.leadSummary;
            this.services = this.leadSummary?.service_interests;
            this.planNoteFlag = true;
            UtilityStoreService.setUtilityDetails(this.leadSummary.connection_services);
        },
        updateNote() {
            this.loadPlanNoteAndLead();
        },
        eacalate() {
            this.escalateLead = true;
        },
        closeApplicationWithReason() {
            this.closeLead = true;
        },
        cancelClose() {
            this.closeLead = false;
        },

        async sucessSaveClose(closeReason) {
            try {
                await LeadApplicationService.closeApplicationWithReason(this.leadId, closeReason);
                this.closeLead = false;
                this.closeConfirm = true;
            } catch (error) {
                console.log('closeApplication error', error);
            }
        },
        sucessSaveEscal() {
            this.escalateLead = false;
            this.escalateLeadConfirm = true;
        },
        cancelEscal() {
            this.escalateLead = false;
        },
        async closeApplication(lead) {
            try {
                await LeadApplicationService.closeApplication(lead.id);
                this.$router.push({name: 'applications'});
            } catch (error) {
                // console.log('closeApplication error' , erro);
            }
        },
        async sendToChatBotConfirmModal() {
            this.sentToChabotConfirmModal = true;
        },
        async sendToChatBot() {
            try {
                let v = await this.validateLead();
                if (v) {
                    // let assignedHoodUser = await this.getAssignedHoodUser();
                    // if(!assignedHoodUser) {
                    //     this.assignedToDialog = true;
                    //     return true;
                    // }
                    await LeadApplicationService.sendToChatBot(this.leadId);
                    this.closeSentConfirm = true;
                }
            } catch (error) {
                console.log('sendToChatBot error', error);
            }
        },
        done() {
            this.$router.push({name: 'application.list'});
        },
        readMore() {
            this.additionalInstruction = this.lead.person_details.additional_instruction;
            this.readMoreFlag = true;
        },
        closeReadMore() {
            this.readMoreFlag = false;
        },
        updateLead(lead) {
            this.fullName = lead.person_details.first_name + ' ' + lead.person_details.last_name;
            this.lead = lead;
        },
        async submitConnection(submitType) {
            let v = await this.validateLead();
            let isProperAddress = await this.isProperAddress();

            if (!isProperAddress) Store.commit('setInvalidAddress', true);

            if (!v || !isProperAddress) return;

            let assignedHoodUser = await this.getAssignedHoodUser();
            if (!assignedHoodUser) {
                this.assignedToDialog = true;
                return true;
            }
            if (this.serviceSubmitType === 'gas') {
                if (this.gasProvider === 'powershop') {
                    this.gasOnlyNotSubmitDialog = true;
                    return true;
                }
            }
            if (this.isWaterUnavailable(submitType, this.lead?.property_details?.state, this.lead?.person_details?.tenancy_type)) {
                this.preventSubmissionFlag = true;
                return;
            }
            this.submitType = submitType;
            this.payload = {
                ...this.lead.property_details,
                ...this.lead.person_details,
                selectedProvider: (submitType === 'energy' || submitType === 'power') ? this.powerProvider : this.gasProvider,
                selectedPowerPlan: this.powerPlan,
                selectedGasPlan: this.gasPlan,
                submitType,
                identification: this.lead.identification,
            };
            this.showSubmitModal = true;
        },
        async getAssignedHoodUser() {
            return await LeadApplicationService.getAssignedHoodUser(this.leadId);
        },
        isWaterUnavailable($submitType, $state, $tenantType) {

            const rightState = ['vic', 'victoria'].includes($state?.toLowerCase());

            if ($submitType === 'water' && !rightState) {
                this.preventSubmissionMessage = 'Water is not available outside Victoria';
                return true;
            }
            if ($submitType === 'water' && $tenantType === 2) {
                this.preventSubmissionMessage = 'Water is not available for Tenancy Home Owner ';
                return true;
            }
            return false;
        },


        closePreventSubmissionModal() {
            this.preventSubmissionFlag = false;
        },
        backToEdit() {
            this.showSubmitModal = false;
        },
        async validateLead() {
            return (await this.$refs.submit_lead.validate()) && (await this.$refs.service_form.validate())
        },
        async confirmSubmitLead() {
            this.showSubmitModal = false;
            let payload = null;
            if (this.lead.property_details === undefined) {
                payload = {...this.lead};

            } else {
                if (this.lead?.indentification?.medicare_expire_date) {
                    delete this.lead.indentification.medicare_expire_date;
                }

                payload = {
                    ...this.lead.property_details,
                    ...this.lead.person_details,
                    'service_interests': this.services,
                    'identification': this.lead.identification,
                    supplier: 1,
                    plan_type: (this.submitType === 'energy' || this.submitType === 'power') ? this.powerPlan : this.gasPlan,
                    submit_type: this.submitType
                };
            }
            console.log('payload', payload);
            this.submittedLoader = true;
            let response = await LeadApplicationService.confirmSubmitLead(payload, this.leadId);
            this.$router.push({name: 'applications'});
        },
        async updateAddress(address) {
            this.leadSummary.address_text = address.address_text
            this.leadSummary.street_address = address.street_address
            this.leadSummary.city = address.city
            this.leadSummary.is_billing_same = address.is_billing_same

            this.leadSummary.billing_address_text = address.billing_address_text
            this.leadSummary.billing_street_address = address.billing_street_address

            this.leadSummary.postcode = address.postcode
            this.leadSummary.state = address.state
            this.leadSummary.street_number = address.street_number
            this.leadSummary.unit_number = address.unit_number
            this.leadSummary.street_type = address.street_type
            this.leadSummary.billing_street_type = address.billing_street_type
            this.leadSummary.street_name = address.street_name_only
            this.leadSummary.street_name_only = address.street_name_only
            this.leadSummary.billing_street_name = address.billing_street_name_only
            this.leadSummary.billing_street_name_only = address.billing_street_name_only
            this.leadSummary.billing_unit_number = address.billing_unit_number
            this.nmiMernFlag = true;
            this.leadSummary.nmi = '';
            this.leadSummary.mirn = '';
            let response = await LeadApplicationService.updateAddress(address, this.leadId);
            console.log('updateAddress response', response);
            this.leadSummary.nmi = response.nmi;
            this.leadSummary.is_embedded_nmi = response.is_embedded_nmi;
            this.leadSummary.mirn = response.mirn;
            this.nmiMernFlag = response.loading_address_info;

            await this.getElectricityDistributor();
            await this.loadNextBusinessDay();
        },

        async updateDraft(field, value, isDate, identification, isManualChangeFlag) {
            if (isNull(value)) return;

            if (isDate) {
                if (field == 'dob' && dayjs(value, 'DD/MM/YYYY').isSame(this.leadSummary.dob)) {
                    return;
                }

                if (field == 'moving_date' && dayjs(value, 'DD/MM/YYYY').isSame(this.leadSummary.moving_date)) {
                    return;
                }

                if (field == 'expire_date' && dayjs(value, 'DD/MM/YYYY').isSame(this.leadSummary.identification.expire_date)) {
                    return;
                }
            }
            const res = await LeadApplicationService.saveSoleField(field, value, this.leadId, isDate, identification, false);

            // Is embedded change
            this.leadSummary.is_embedded_nmi = res.data.data.is_embedded_nmi;

            let [day, month, year] = [];
            if (isDate) {
                [day, month, year] = value.split('/');
                value = year + '-' + month + '-' + day;
            }
            if (identification) {
                this.leadSummary.identification = this.leadSummary.identification ? this.leadSummary.identification : {};
                if (field === 'type') {
                    this.leadSummary.identification.card_number = '';
                    this.leadSummary.identification.special_number = '';
                    this.leadSummary.identification.expire_date = null;
                    this.leadSummary.identification.card_color = '';
                    this.leadSummary.identification.state = '';
                    this.leadSummary.identification.country = '';
                }

                this.leadSummary.identification[field] = value;
                return;
            }
            this.leadSummary[field] = value;
            await this.updateAfterHourFlagMovingDate(field, value)
        },
        async updateAfterHourFlagMovingDate(field, value) {
            if (field === 'moving_date' || field === 'service_interests') {
                await this.getElectricityDistributor();
            }
        },
        async updateMernNmi() {
            if (this.leadSummary.nmi == null && this.leadSummary.mirn == null) {
                const nmiMern = await LeadApplicationService.getNmiMern(this.leadId);
                this.leadSummary.nmi = nmiMern.nmi;
                this.leadSummary.mirn = nmiMern.mirn;
                this.leadSummary.is_embedded_nmi = nmiMern.is_embedded_nmi;
            }
        },
        closeAssignedToEmptyModal() {
            this.assignedToDialog = false;
        },
        closegasOnlyNotSubmitModal() {
            this.gasOnlyNotSubmitDialog = false;
        },
        async loadNextBusinessDay() {
            this.nextBusinessDay = await ChatbotService.getNextBusinessDay(this.leadSummary?.state);
        },
        isProperAddress() {
            if (this.leadSummary.street_number == null
                || this.leadSummary.street_name_only == null
                || this.leadSummary.street_type == null
                || this.leadSummary.state == null
                || this.leadSummary.city == null
                || this.leadSummary.postcode == null) {
                return false;
            }
            return true;
        },
        serviceType(value) {
            this.serviceSubmitType = value;
        },
        duplicatedLead() {
            this.duplicateLead = true;
        },
        cancelDuplicateLead() {
            this.duplicateLead = false;
        },


        // async updateEmail(field, value) {
        //     await LeadApplicationService.saveEmailField(field, value, this.leadId);
        // },
        closeUnlockConfirmModal() {
            this.showUnlockConfirmModal = false;
            this.isChatBotApplication = true;
        },
        async openUnlockConfirmModal() {
            this.isChatBotApplication = false;
            this.showUnlockConfirmModal = true;
        },

        async unlockApp() {
            const res = await LeadApplicationService.lockOrUnlockApp(this.leadId, {is_locked: false});
            console.log(res);

            if (res.success) {
                this.showUnlockConfirmModal = false;
                await this.getIsLocked();
            }
        },

        async lockApp() {
            const res = await LeadApplicationService.sendToChatBot(this.leadId);

            if (res.success) {
                this.sentToChabotConfirmModal = false;
                await this.getIsLocked();
            }
        },

        async getIsLocked() {
            const res = await LeadApplicationService.isSentToChatbot(this.leadId);

            this.chatbotData = res;
            this.isChatBotApplication = res.chatbot_id && res.is_locked;
        },
        cancelSendToChatBotConfirmModal() {
            this.sentToChabotConfirmModal = false;
        },
        listenMirnNmiNotification() {
            this.$echo.channel(`fetchMirnNmi.${this.leadSummary.id}`)
                .notification(async (res) => {
                    this.nmiMernFlag = res.loading_address_info;
                    await this.loadPlanNoteAndLead();
                });
        },
        listenEmbeddedNetworkNotification() {
            this.$echo.channel(`fetchEmbeddedNetwork.${this.leadSummary.id}`)
                .notification(async (res) => {
                    await this.loadPlanNoteAndLead();
                });
        }
    },
    watch: {
        powerPlan: {
            handler() {
                this.getElectricityDistributor();
            },
            deep: true,
        },
    },
    async mounted() {
        const validateEvent = async (callback) => {
            let v = await this.validateLead();
            if (!v) return;
            callback('sumo');
        };
        const busUtilitySubmitEvent = async (type) => {
            await this.submitConnection(type);
        }
        this.$eventBus.$on("validate", validateEvent);
        this.$eventBus.$on("busUtilitySubmit", busUtilitySubmitEvent);

        this.$once("hook:beforeDestroy", () => {
            this.$eventBus.$off("validate", validateEvent);
        });

        this.$once("hook:beforeDestroy", () => {
            this.$eventBus.$off("busUtilitySubmit", busUtilitySubmitEvent);
        });

        this.leadId = this.$route.params.id;
        await this.loadPlanNoteAndLead();
        await this.loadNextBusinessDay();
        await this.updateMernNmi();
        this.nmiMernFlag = false;


        await this.getIsLocked();

        this.$eventBus.$on("lock_app_auto_assign", async () => {
            await this.lockApp();
        });

        this.listenMirnNmiNotification();
        this.listenEmbeddedNetworkNotification();
    }
};
</script>

<style scoped>
</style>

