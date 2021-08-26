<template>
    <v-card class="hood-card mb-5">
       <LeadDetailsHeader :leadSummary="leadSummary" @eacalate="eacalate"></LeadDetailsHeader>
        <InfoField @updateLead="updateLead" :lead="leadSummary" @readMore="readMore"></InfoField>
    </v-card>
</template>

<script>
import LeadDetailsHeader from "@scripts/components/crm/leadmanagement/LeadDetailsHeader";
import InfoField from "@scripts/components/crm/leadmanagement/InfoField";
export default {
name: "LeadUserDetails",
    components: {LeadDetailsHeader, InfoField},
    props: {
        leadSummary: {
          require: true
        },
    },
    methods: {
        updateLead(lead) {
            this.$emit('updateLead',lead);
            // console.log('lead', lead);
        },
        eacalate(){
            this.$emit('eacalate');
        },
        readMore() {
            this.$emit('readMore');
        }
    },
    mounted() {
        // console.log('load summary header',this.leadSummary);
    }
}
</script>

<style scoped>

</style>
