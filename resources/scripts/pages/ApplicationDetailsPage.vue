<template>
    <v-container fluid  v-if="planNoteFlag">
            <ValidationObserver ref="submit_lead">
                <LeadUserDetails
                                v-model="infoToPass"
                                :nmiMernFlag="nmiMernFlag"
                                :services="services"
                                @closeApplication="closeApplication"
                                @eacalate="eacalate"
                                @updateLead="updateLead"
                                @readMore="readMore" :leadSummary="leadSummary"
                                @updateAddress="updateAddress" @updateDraft="updateDraft"
                        ></LeadUserDetails>
            </ValidationObserver>
                <LeadServicesAndNotes
                                   @updateService="updateService"
                                   @updatePlan="updatePlan"
                                   @updateNote= "updateNote"
                                   :leadSummary="leadSummary" :notes="notes"></LeadServicesAndNotes>

            <LeadsDetailsFotter :lifeSupportInfo="infoToPass.lifeSupportInfo"  v-if="leadSummary.status != 1" :login-loading="this.submittedLoader" :isManualChangeFlag="isManualChangeFlag" @submitConnection="submitConnection"></LeadsDetailsFotter>
            <EscalateReasonModal v-if="escalateLead" :dialog="escalateLead" :leadSummary="leadSummary" @cancelEscal="cancelEscal" @sucessSaveEscal="sucessSaveEscal"></EscalateReasonModal>
            <EscalationConfirmModal v-if="escalateLeadConfirm" :dialog="escalateLeadConfirm" :title="fullName"></EscalationConfirmModal>
            <LeadReadMoreModal v-if="readMoreFlag" :dialog="readMoreFlag"
                               :readmore="additionalInstruction"
                               @close="closeReadMore"> </LeadReadMoreModal>
            <LeadSubmitConfirmationModal :dialog="showSubmitModal" :data="payload" v-if="showSubmitModal" @saveData="saveData" @backToEdit="backToEdit"> </LeadSubmitConfirmationModal>
    </v-container>
</template>

<script>
import LeadUserDetails from "@scripts/components/crm/leadmanagement/LeadUserDetails";
import LeadServicesAndNotes from "@scripts/components/crm/leadmanagement/LeadServicesAndNotes";
import LeadsDetailsFotter from "@scripts/components/crm/leadmanagement/LeadsDetailsFotter";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import EscalateReasonModal from "@scripts/components/crm/modals/EscalateReasonModal";
import EscalationConfirmModal from "@scripts/components/crm/modals/EscalationConfirmModal";
import LeadReadMoreModal from "@scripts/components/crm/modals/LeadReadMoreModal";
import LeadSubmitConfirmationModal from "@scripts/components/crm/modals/LeadSubmitConfirmationModal";
import LeadApplicationAPI from "@scripts/api/crm/LeadApplicationAPI";
import * as dayjs from "dayjs";
import {isNull} from "lodash-es";
export default {
    name: "ApplicationDetailsPage",

    components: {
        LeadReadMoreModal,
        EscalationConfirmModal,
        EscalateReasonModal,
        LeadsDetailsFotter,
        LeadServicesAndNotes,
        LeadUserDetails,
        LeadSubmitConfirmationModal

    },

    data() {
        return {
            nmiMernFlag: true,
            leadId: null,
            leadSummary: null,
            notes: null,
            planNoteFlag: false,
            escalateLead: false,
            escalateLeadConfirm: false,
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

            //$attrs
            infoToPass:{
                lifeSupportInfo: {
                    value: false,
                    errorMsg: false,
                }
            }
        }
    },

    methods: {
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
        },

        updateNote() {
            this.loadPlanNoteAndLead();
        },

        eacalate() {
            this.escalateLead = true;
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

                return;
            }
            this.services.splice(index,1);
            LeadApplicationService.saveSoleField('service_types', this.services, this.leadId, false, false, true);
            this.leadSummary.service_types = this.services;
        },

        async submitConnection() {
            let v = await this.validateLead();
            if(!v) return;

            this.payload = { ...this.lead.property_details,
                ...this.lead.person_details,
                'service_interests':this.services,
                'identification':this.lead.indentification,
                supplier: 1,
                plan_type: this.plan
            };
            this.showSubmitModal = true;
        },

        backToEdit() {
            this.showSubmitModal = false;
        },

        async validateLead() {
          return await this.$refs.submit_lead.validate();
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
                    'identification':this.lead.indentification,
                    supplier: 1,
                    plan_type: this.plan?.key
                };
            }

            this.submittedLoader = true;

            let response = await LeadApplicationService.saveLead(payload, this.leadId);
           this.$router.push({name:'applications'});
        },

        async updateAddress(address) {
            if(this.leadSummary.address_text == address.address_text ) return;
            this.leadSummary.address_text = address.address_text
            this.leadSummary.street_address = address.street_address
            this.leadSummary.city = address.city
            // this.leadSummary.is_renovation_on = address.is_renovation_on
            // this.leadSummary.has_electricity = address.has_electricity
            // this.leadSummary.inspection_time = address.inspection_time
            this.leadSummary.postcode = address.postcode
            this.leadSummary.state = address.state
            this.leadSummary.street_number = address.street_number
            this.leadSummary.unit_number = address.unit_number
            this.leadSummary.street_name = address.street_name
            this.nmiMernFlag = true;
            this.leadSummary.nmi = '';
            this.leadSummary.mirn = '';
            this.isManualChangeFlag = false;
            let response = await LeadApplicationService.updateAddress(address, this.leadId);
            this.leadSummary.nmi = response.nmi;
            this.leadSummary.mirn = response.mirn;
            this.nmiMernFlag = false;
            this.isManualChangeFlag = true;
        },

        async updateDraft(field, value, isDate, identification, isManualChangeFlag) {

            if(isNull(value)) return;

            if(isDate)
            {
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

        },

        async updateMernNmi()
        {

            if(this.leadSummary.nmi == null && this.leadSummary.mirn == null)
            {
                const nmiMern = await LeadApplicationService.getNmiMern(this.leadId);
                this.leadSummary.nmi = nmiMern.nmi;
                this.leadSummary.mirn = nmiMern.mirn;
            }


        }

    },

  async  mounted() {
        this.$eventBus.$on("validate", async (callback) => {
              let v = await this.validateLead();
              if(!v) return;
              callback('sumo');
          });
      this.leadId = this.$route.params.id;
      await this.loadPlanNoteAndLead();
      await this.updateMernNmi();
      this.nmiMernFlag = false;


    }
};
</script>

<style scoped>
</style>

