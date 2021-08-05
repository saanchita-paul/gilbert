<template>
    <v-row>
        <v-col cols="8" class="mb-8 pb-8">
            <v-card class="pa-4">
                <ServiceApplications :plans="plans"></ServiceApplications>
            </v-card>
        </v-col>
        <v-col cols="4" class="mb-8 pb-8">
            <v-card class="pa-4">
                <ApplicationNotes :notes="notes"></ApplicationNotes>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import ServiceApplications from "@scripts/components/crm/leadmanagement/ServiceApplications";
import ApplicationNotes from "@scripts/components/crm/leadmanagement/ApplicationNotes";

export default {
name: "LeadServicesAndNotes",
    props: {
        notes: {

        },
        plans: {
            require: true
        }
    },
    components:{
        ServiceApplications,
        ApplicationNotes
    },
    mounted() {
        console.log('notes', this.notes);
        console.log('this.plans',this.plans);
    }
}
</script>

<style scoped>

</style>
