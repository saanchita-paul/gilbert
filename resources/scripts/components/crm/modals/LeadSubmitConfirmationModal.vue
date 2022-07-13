<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="70%"
        >
                <div>
                  <ConfirmSubmission @backToEdit="backToEdit" :submitType="submitType" :data="data" :leadId="leadId" @confirmSubmitLead="confirmSubmitLead"></ConfirmSubmission>
                </div>
        </v-dialog>
    </v-row>
</template>

<script>
import ConfirmSubmission from "@scripts/components/crm/leadmanagement/ConfirmSubmission";

export default {
    name: "LeadSubmitConfirmationModal",
    components: {ConfirmSubmission},
    props:{
        dialog: {
            require: true,
        },
        leadSummary: {
            require: true,
        },
        leadId : {
            require: true
        },
        data: {
            require: true
        },
        submitType: {
            require: true
        },
    },
    methods: {
        backToEdit() {
            this.$emit('backToEdit');
        },
        confirmSubmitLead() {
            this.$emit('confirmSubmitLead');
        }
    }
}
</script>

<style scoped>

</style>
