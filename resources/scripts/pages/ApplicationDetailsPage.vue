<template>
    <v-container fluid  v-if="planNoteFlag">
            <ValidationObserver ref="submit_lead">
                <LeadUserDetails
                                v-model="infoToPass"
                                :nmiMernFlag="nmiMernFlag"
                                :services="services"
                                @closeApplicationWithReason="closeApplicationWithReason"
                                @closeApplication="closeApplication"
                                @eacalate="eacalate"
                                @updateLead="updateLead"
                                @readMore="readMore"
                                :leadSummary="leadSummary"
                                @updateAddress="updateAddress"
                                @updateDraft="updateDraft"
                        ></LeadUserDetails>
                 </ValidationObserver>            <!-- <LeadsDetailsFotter :lifeSupportInfo="infoToPass.lifeSupportInfo"  v-if="leadSummary.status != 1" :login-loading="this.submittedLoader" :isManualChangeFlag="isManualChangeFlag" @submitConnection="submitConnection"></LeadsDetailsFotter> -->
                <LeadServicesAndNotes
                    @updateDraft="updateDraft"
                    @updateService="updateService"
                    @updatePlan="updatePlan"
                    @updateNote= "updateNote"
                    :leadSummary="leadSummary"
                    :afterHourFlag="afterHourFlag"
                    :notes="notes">

                </LeadServicesAndNotes>

            <EscalateReasonModal v-if="escalateLead" :dialog="escalateLead" :leadSummary="leadSummary" @cancelEscal="cancelEscal" @sucessSaveEscal="sucessSaveEscal"></EscalateReasonModal>
            <EscalationConfirmModal v-if="escalateLeadConfirm" :dialog="escalateLeadConfirm" :title="fullName"></EscalationConfirmModal>

            <CloseApplicationReasonModal v-if="closeLead" :dialog="closeLead" :leadSummary="leadSummary" @closeApplicationWithReason="closeApplicationWithReason" @cancelClose="cancelClose" @sucessSaveClose="sucessSaveClose"></CloseApplicationReasonModal>

            <CloseConfirmModal v-if="closeConfirm" :dialog="closeConfirm" :title="fullName"></CloseConfirmModal>

            <!-- <CloseApplicationModal v-if="escalateLead" :dialog="escalateLead" :leadSummary="leadSummary" @cancelEscal="cancelEscal" @sucessSaveEscal="sucessSaveEscal"></CloseApplicationModal> -->

            <LeadReadMoreModal v-if="readMoreFlag" :dialog="readMoreFlag"
                               :readmore="additionalInstruction"
                               @close="closeReadMore"> </LeadReadMoreModal>

        <AssignedToUserEmptyModal v-if="assignedToDialog" :dialog="assignedToDialog" @closeMessage="closeAssignedToEmptyModal"></AssignedToUserEmptyModal>

        <LeadSubmitConfirmationModal :dialog="showSubmitModal" :data="payload" secondaryContact="secondaryContact" v-if="showSubmitModal" @saveData="saveData" @backToEdit="backToEdit"> </LeadSubmitConfirmationModal>
            <PreventSubmissionModal v-if="preventSubmissionFlag" :message="preventSubmissionMessage" :dialog="preventSubmissionFlag" @closeMessage="closePreventSubmissionModal"></PreventSubmissionModal>
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
import * as dayjs from "dayjs";
import {isNull} from "lodash-es";
import PreventSubmissionModal from "@scripts/components/crm/modals/PreventSubmissionModal";
import EAAfterHourService from "@scripts/services/ea/EAAfterHourService";
import ChatbotService from "@scripts/services/crm/ChatbotService";

export default {
    name: "ApplicationDetailsPage",

    components: {
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
        PreventSubmissionModal
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
            additionalInstruction:null,
            lead: null,
            plan: null,
            supplier: 'ea',
            services: [],
            showSubmitModal: false,
            payload: null,
            fullName: null,
            submittedLoader: false,
            isManualChangeFlag: false,
            submitType: null,
            assignedToDialog: false,
            preventSubmissionFlag: false,
            preventSubmissionMessage: '',
            ea_service_type: 'electricity_and_gas',
            eaElectricityDistributor: '',

            //$attrs
            infoToPass:{
                lifeSupportInfo: {
                    value: false,
                    errorMsg: false,
                }
            },
            nextBusinessDay: null,
        }
    },
    watch: {

    },
    computed: {

        afterHourFlag() {
            return this.plan && EAAfterHourService.calculateAfterHourFlag(this.eaElectricityDistributor,
                this.leadSummary.moving_date, this.leadSummary.state, this.nextBusinessDay);
        }

    },
    methods: {

        async getElectricityDistributor()
        {
            if(!isNull(this.plan)) {
                this.eaElectricityDistributor = await EAAfterHourService.getElectricityDistributor(this.leadSummary.service_interests,
                    this.plan?.key, this.leadSummary?.postcode, this.leadSummary?.state);
            }

        },

        async loadPlanNoteAndLead()
        {
            this.notes = await LeadApplicationService.loadNote(this.leadId);
            this.leadSummary = await LeadApplicationService.loadUserLead(this.leadId);
            this.lead = this.leadSummary;
            this.services = this.leadSummary?.service_interests;
            this.planNoteFlag = true;
        },


        updatePlan(plan, isManual)
        {

            //manual click activation plan
            if(isManual) this.isManualChangeFlag = true;
            this.plan = plan;
            LeadApplicationService.saveSoleField('plan_type', this.plan, this.leadId);
            this.getElectricityDistributor();
        },

        updateNote() {
            this.loadPlanNoteAndLead();
        },

        eacalate() {
            this.escalateLead = true;
        },
        closeApplicationWithReason(){
            this.closeLead = true;
        },
        cancelClose(){
            this.closeLead = false;
        },
       async sucessSaveClose(closing_reason){
            // this.closeLead = false;

            // try {
            //     const data = await axios.post('api/applications/'+this.leadId+'/closeApplication' , {closing_reason});
            //     console.log(data)
            //     return true;
            // } catch (error) {
            //     console.log(error)
            //     return false;
            // }

            try {
                await LeadApplicationService.closeApplicationWithReason(this.leadId , closing_reason);
                this.closeLead = false;
                this.closeConfirm = true;
                // this.$router.push({name:'applications'});
            } catch (error) {
                console.log('closeApplication error' , error);
            }
        },
        sucessSaveEscal()
        {
            this.escalateLead = false;
            this.escalateLeadConfirm = true;
        },

        cancelEscal() {
            this.escalateLead = false;
        },

        async closeApplication(lead) {

            try {
                await LeadApplicationService.closeApplication(lead.id);
                this.$router.push({name:'applications'});
            } catch (error) {
                // console.log('closeApplication error' , erro);
            }
        },

        readMore() {
            this.additionalInstruction = this.lead.person_details.additional_instruction;
            this.readMoreFlag = true;
        },

        closeReadMore() {
            this.readMoreFlag = false;
        },

        updateLead(lead) {
            this.fullName = lead.person_details.first_name +' '+ lead.person_details.last_name;
            this.lead = lead;
            // console.log('lead3' , this.lead);
        },
        updateService(service) {
            this.isManualChangeFlag = true;
            let index = this.services.findIndex(svc => svc === service.toLowerCase());
            if(index == -1) {

                this.services.push(service.toLowerCase());
                 LeadApplicationService.saveSoleField('service_types', this.services, this.leadId, false, false, true);
                this.loadPlanNoteAndLead()

            } else {
                this.services.splice(index, 1);
                LeadApplicationService.saveSoleField('service_types', this.services, this.leadId, false, false, true);
                this.leadSummary.service_types = this.services;
                console.log('services', service, this.services)
                this.loadPlanNoteAndLead()
            }
            this.plan = null

        },

        async submitConnection(submitType) {
            let v = await this.validateLead();
            if(!v) return;

            let assignedHoodUser = await this.getAssignedHoodUser();
            if(!assignedHoodUser) {
                this.assignedToDialog = true;
                return true;
            }
            if(this.isWaterUnavailable(submitType, this.lead?.property_details?.state, this.lead?.person_details?.tenancy_type))
            {
                this.preventSubmissionFlag = true;
                return;
            }

            this.submitType = submitType;
            this.payload = { ...this.lead.property_details,
                ...this.lead.person_details,
                service_interests: this.services,
                supplier: 1,
                plan_type: this.plan,
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

            if($submitType === 'water' && rightState) {
                this.preventSubmissionMessage = 'Water is not available outside Victoria';
                return true;
            }
            if($submitType === 'water' && $tenantType === 2) {
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
          return await this.$refs.submit_lead.validate()
        },

        async saveData() {

            this.showSubmitModal = false;
            let payload = null;
            if(this.lead.property_details === undefined)
            {
                payload = {...this.lead};

            } else
            {
                if(this.lead?.indentification?.medicare_expire_date) {
                    delete this.lead.indentification.medicare_expire_date;
                }

                payload=  { ...this.lead.property_details,
                    ...this.lead.person_details,
                    'service_interests':this.services,
                    'identification': this.lead.identification,
                    supplier: 1,
                    plan_type: this.plan?.key,
                    submit_type: this.submitType

                };
            }

            this.submittedLoader = true;

            let response = await LeadApplicationService.saveLead(payload, this.leadId);
           this.$router.push({name:'applications'});
        },

        async updateAddress(address) {
            // if(this.leadSummary.address_text == address.address_text && this.leadSummary.billing_address_text == address.billing_address_text ) return;
            this.leadSummary.address_text = address.address_text
            this.leadSummary.street_address = address.street_address
            this.leadSummary.city = address.city
            this.leadSummary.is_billing_same = address.is_billing_same
            // this.leadSummary.is_renovation_on = address.is_renovation_on
            // this.leadSummary.has_electricity = address.has_electricity
            // this.leadSummary.inspection_time = address.inspection_time

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
            this.isManualChangeFlag = false;
            let response = await LeadApplicationService.updateAddress(address, this.leadId);
            this.leadSummary.nmi = response.nmi;
            this.leadSummary.mirn = response.mirn;
            this.nmiMernFlag = false;
            this.isManualChangeFlag = true;

            await this.getElectricityDistributor();
            await this.loadNextBusinessDay();
        },

        async updateDraft(field, value, isDate, identification, isManualChangeFlag) {

            // console.log('draft date', field , value);
            if(isNull(value)) return;

            if(isDate) {
                if(field == 'dob'&& dayjs(value,'DD/MM/YYYY').isSame(this.leadSummary.dob))
                {
                    return;
                }

                if(field == 'moving_date' && dayjs(value,'DD/MM/YYYY').isSame(this.leadSummary.moving_date))
                {
                    return;
                }

                if(field == 'expire_date' && dayjs(value,'DD/MM/YYYY').isSame(this.leadSummary.identification.expire_date))
                {
                   return;
                }
            }
            await LeadApplicationService.saveSoleField(field, value,this.leadId, isDate, identification, false);
            this.isManualChangeFlag = true;

           let [day, month, year] = [];
            if(isDate)
            {
                [day, month, year] = value.split('/');
                value = year + '-' + month + '-' + day;
            }
            if(identification) {
                this.leadSummary.identification = this.leadSummary.identification ? this.leadSummary.identification : {};
                if(field === 'type') {
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

        async updateAfterHourFlagMovingDate(field, value){
            if(field === 'moving_date' || field === 'service_interests') {
                await this.getElectricityDistributor();
            }
        },

        async updateMernNmi() {
            if(this.leadSummary.nmi == null && this.leadSummary.mirn == null) {
                const nmiMern = await LeadApplicationService.getNmiMern(this.leadId);
                this.leadSummary.nmi = nmiMern.nmi;
                this.leadSummary.mirn = nmiMern.mirn;
            }
        },

        closeAssignedToEmptyModal(){
            this.assignedToDialog = false;
        },
        async loadNextBusinessDay() {
            this.nextBusinessDay = await ChatbotService.getNextBusinessDay(this.leadSummary?.state);
        }
    },

  async  mounted() {

        const validateEvent = async (callback) => {
              let v = await this.validateLead();
              if(!v) return;
              callback('sumo');
          };
        const busWaterSubmitEvent = async (type) => {
              await this.submitConnection(type);
          }


        this.$eventBus.$on("validate", validateEvent);
        this.$eventBus.$on("busWaterSubmit", busWaterSubmitEvent);

        this.$once("hook:beforeDestroy", () => {
            this.$eventBus.$off("validate", validateEvent );
        });

        this.$once("hook:beforeDestroy", () => {
            this.$eventBus.$off("busWaterSubmit", busWaterSubmitEvent);
        });


      this.leadId = this.$route.params.id;
      await this.loadPlanNoteAndLead();
      await this.loadNextBusinessDay();
      await this.updateMernNmi();
      this.nmiMernFlag = false;



    }

};
</script>

<style scoped>
</style>

