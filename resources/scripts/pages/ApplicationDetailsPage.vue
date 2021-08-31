<template>
    <v-container fluid  v-if="planNoteFlag">
            <ValidationObserver ref="submit_lead">
                <LeadUserDetails @eacalate="eacalate"
                                 @updateLead="updateLead"
                                 @readMore="readMore" :leadSummary="leadSummary"></LeadUserDetails>
            </ValidationObserver>
                <LeadServicesAndNotes
                                   @updateService="updateService"
                                   @updatePlan="updatePlan"
                                   @updateNote= "updateNote"
                                   :leadSummary="leadSummary" :notes="notes"></LeadServicesAndNotes>

            <LeadsDetailsFotter v-if="leadSummary.status != 1" @submitConnection="submitConnection"></LeadsDetailsFotter>
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
export default {
    name: "ApplicationDetailsPage",
    data() {
      return {
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
      }
    },
    components: {
        LeadReadMoreModal,
        EscalationConfirmModal,
        EscalateReasonModal,
        LeadsDetailsFotter,
        LeadServicesAndNotes,
        LeadUserDetails,
        LeadSubmitConfirmationModal

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

        updatePlan(plan)
        {
            this.plan = plan;
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
        },
        updateService(service) {
            let index = this.services.findIndex(svc => svc === service.toLowerCase());
            if(index == -1) {
                this.services.push(service.toLowerCase());
                return;
            }
            this.services.splice(index,1);
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
                payload=  { ...this.lead.property_details,
                    ...this.lead.person_details,
                    'service_interests':this.services,
                    'identification':this.lead.indentification,
                    supplier: 1,
                    plan_type: this.plan?.key
                };
            }

            let response = await LeadApplicationService.saveLead(payload, this.leadId);
           this.$router.push({name:'applications'});
        }

    },

    mounted() {
       this.leadId = this.$route.params.id;
       this.loadPlanNoteAndLead();
        // this.loadLead();

    }
};
</script>

<style scoped>
</style>

