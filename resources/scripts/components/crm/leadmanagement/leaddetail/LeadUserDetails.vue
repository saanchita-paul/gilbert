<template>
    <v-card class="pa-4 mb-5">
       <LeadDetailsHeader></LeadDetailsHeader>
        <InfoField @updateLead="updateLead"></InfoField>
    </v-card>
</template>

<script>
import LeadDetailsHeader from "@scripts/components/crm/leadmanagement/leaddetail/LeadDetailsHeader";
import InfoField from "@scripts/components/crm/leadmanagement/InfoField";
export default {
name: "LeadUserDetails",
    components: {LeadDetailsHeader, InfoField},
    methods: {
        updateLead(lead) {
            console.log('lead', lead);
        }
    }
}
</script>

<style scoped>

</style>
