<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="800px"
        >
            <v-card>
                <div class="section-dialogs">
                  <ConfirmSubmission @backToEdit="backToEdit" @saveData="saveData"></ConfirmSubmission>
                </div>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import AgencyDetails from "@scripts/components/crm/AgencyDetails";
import AgentConfirmApplicationModal from "@scripts/components/crm/modals/agent/AgentConfirmApplicationModal";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
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
        }
    },
    methods: {
        backToEdit() {
            this.$emit('backToEdit');
        },
        saveData() {
            this.$emit('saveData');
        }
    }
}
</script>

<style scoped>

</style>
