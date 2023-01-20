<template>
    <v-card class="hood-card mb-5 pt-0">
        <LeadDetailsHeader @closeApplicationWithReason="closeApplicationWithReason"
                           :leadSummary="leadSummary"
                           @closeApplication="closeApplication"
                           @eacalate="eacalate"
                           @duplicateLead="duplicateLead"
                           @sendToChatBotConfirmModal="sendToChatBotConfirmModal"
                           @sendToChatBot="sendToChatBot"
                           @reloadPlanNoteAndLead="reloadPlanNoteAndLead"
                           :isLocked="isLocked"
        ></LeadDetailsHeader>
        <InfoField v-bind="$attrs" :services="services" @updateLead="updateLead" :nmiMernFlag="nmiMernFlag"
                   :lead="leadSummary" @updateDraft="updateDraft" @readMore="readMore"
                   @updateAddress="updateAddress"></InfoField>
    </v-card>
</template>

<script>
import LeadDetailsHeader from "@scripts/components/crm/leadmanagement/LeadDetailsHeader";
import InfoField from "@scripts/components/crm/leadmanagement/InfoField";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";

export default {
    name: "LeadUserDetails",
    components: {LeadDetailsHeader, InfoField},
    props: {
        leadSummary: {
            require: true
        },
        nmiMernFlag: {
            require: false
        },
        services: {
            require: false
        },
        isLocked: {
            required: true
        }

    },
    methods: {
        closeApplication(lead) {
            this.$emit('closeApplication', lead);
        },
        closeApplicationWithReason(lead) {
            this.$emit('closeApplicationWithReason', lead);
        },
        updateLead(lead) {
            this.$emit('updateLead', lead);
        },
        eacalate() {
            this.$emit('eacalate');
        },
        readMore() {
            this.$emit('readMore');
        },
        updateAddress(address) {
            this.$emit('updateAddress', address);
        },
        updateDraft(field, value, isDate, identification, isManualChangeFlag = false) {
            this.$emit('updateDraft', field, value, isDate, identification, isManualChangeFlag);
        },
        sendToChatBotConfirmModal() {
            this.$emit('sendToChatBotConfirmModal');
        },
        sendToChatBot(lead) {
            this.$emit('sendToChatBot', lead);
        },
        duplicateLead() {
            this.$emit('duplicateLead');
        },

        reloadPlanNoteAndLead() {
            this.$emit('loadPlanNoteAndLead');
        }

        // updateEmail(field, value) {
        //     this.$emit('updateEmail', field, value);
        // },

    },
    mounted() {
        // console.log('LeadUserDetails', this.Services);
    }

}
</script>

<style scoped>

</style>
