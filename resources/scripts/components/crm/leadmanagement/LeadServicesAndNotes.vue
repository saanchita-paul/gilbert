<template>
    <v-row>
        <v-col cols="8" class="mb-8 pb-8">
            <v-card class="hood-card">
                <ServiceApplications @updateService="updateService" :leadSummary="leadSummary" @updatePlan="updatePlan"></ServiceApplications>
            </v-card>
        </v-col>
        <v-col cols="4" class="mb-8 pb-8">
            <v-card class="hood-card">
                <ApplicationNotes :notes="notes" @saveNote="saveNote"></ApplicationNotes>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import ServiceApplications from "@scripts/components/crm/leadmanagement/ServiceApplications";
import ApplicationNotes from "@scripts/components/crm/leadmanagement/ApplicationNotes";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";

export default {
name: "LeadServicesAndNotes",
    props: {
        notes: {

        },
        leadSummary: {
            require: true
        }
    },
    components:{
        ServiceApplications,
        ApplicationNotes
    },
    methods: {
        updatePlan(plan)
        {
            this.$emit('updatePlan', plan);
        },

        async saveNote(note) {
          await LeadApplicationService.saveNote(note, this.leadSummary.id);
          this.$emit('updateNote');
        },
        updateService(service)
        {
            this.$emit('updateService',service);
        }


    },
    mounted() {
    }
}
</script>

<style scoped>

</style>
