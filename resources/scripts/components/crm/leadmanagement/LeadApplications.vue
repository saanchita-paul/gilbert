<template>
    <v-container>
        <v-row>
             <v-col cols="8">
                <v-card class="hood-card">
                    <p>Your Metrics</p>
                    <h3 class="page-title">Total Applications: 500</h3>
                    <AgentLeadMetrics></AgentLeadMetrics>
                </v-card>

                <ApplicantTable></ApplicantTable>
             </v-col>
             <v-col cols="4">
                 <ApplicationDetails></ApplicationDetails>
             </v-col>
        </v-row>
        
    </v-container>
</template>

<script>
import AgentLeadMetrics from "@scripts/components/crm/leadmanagement/ApplicationsMetrics";
import ApplicantTable from "@scripts/components/crm/leadmanagement/ApplicantTable";
import ApplicationDetails from "@scripts/components/crm/leadmanagement/ApplicationDetails";
export default {
    name: "LeadApplications",
    components: {
        AgentLeadMetrics,
        ApplicantTable,
        ApplicationDetails
    },
}
</script>

<style scoped>
</style>
