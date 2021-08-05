<template>
    <v-container>
            <LeadUserDetails></LeadUserDetails>
            <LeadServicesAndNotes  v-if="planNoteFlag" :plans="plans" :notes="notes"></LeadServicesAndNotes>
            <LeadsDetailsFotter></LeadsDetailsFotter>

    </v-container>
</template>

<script>
import InfoField from "@scripts/components/crm/leadmanagement/InfoField";
import ServiceApplications from "@scripts/components/crm/leadmanagement/ServiceApplications";
import ApplicationNotes from "@scripts/components/crm/leadmanagement/ApplicationNotes";
import LeadUserDetails from "@scripts/components/crm/leadmanagement/leaddetail/LeadUserDetails";
import LeadServicesAndNotes from "@scripts/components/crm/leadmanagement/leaddetail/LeadServicesAndNotes";
import LeadsDetailsFotter from "@scripts/components/crm/leadmanagement/leaddetail/LeadsDetailsFotter";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
export default {
    name: "ApplicationDetailsPage",
    data() {
      return {
          plans: null,
          notes: null,
          planNoteFlag: false
      }
    },
    components: {
        LeadsDetailsFotter,
        LeadServicesAndNotes,
        LeadUserDetails,

    },

    methods: {
        async loadPlanAndNote()
        {
            this.plans = await LeadApplicationService.loadPlan();
            this.notes = await LeadApplicationService.loadNote();
            this.planNoteFlag = true;
        },

       async loadNote() {

        }
    },

    mounted() {
       this.loadPlanAndNote();

    }
};
</script>

<style scoped>
</style>

