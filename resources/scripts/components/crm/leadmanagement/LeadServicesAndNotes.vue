<template>
    <v-row>
        <v-col cols="8" class="mb-8 pb-8">
            <v-card class="pa-4">
                <ServiceApplications :leadSummary="leadSummary" @updatePlan="updatePlan"></ServiceApplications>
            </v-card>
        </v-col>
        <v-col cols="4" class="mb-8 pb-8">
            <v-card class="pa-4">
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

        saveNote(newNote) {
            LeadApplicationService.saveNote(newNote, this.leadSummary.id);
            this.$emit('updateNote');
        }


    },
    mounted() {
    }
}
</script>

<style scoped>

</style>
