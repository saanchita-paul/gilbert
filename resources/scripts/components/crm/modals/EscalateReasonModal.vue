<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <div class="section-dialogs">
                    <v-row>
                    <v-col cols="12">
                        <p class="dialogs-title">What’s the reason why you want to Escalate this application?</p>
                    </v-col>
                    <v-col>
                        <v-textarea v-model="escalated_reason">

                        </v-textarea>
                    </v-col>
                    <v-col cols="12">
                        <div class="d-flex justify-space-between">
                            <v-btn @click="cancelEscalasion">Back</v-btn>
                            <v-btn @click="saveEscalasionReason" color="primary">Confirm</v-btn>
                        </div>
                    </v-col>
                    </v-row>
                </div>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import AgencyDetails from "@scripts/components/crm/AgencyDetails";
import AgentConfirmApplicationModal from "@scripts/components/crm/modals/agent/AgentConfirmApplicationModal";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";

export default {
    name: "EscalateReasonModal",
    components: {},
    props:{
        dialog: {
            require: true,
        },
        leadSummary: {
            require: true,
        }
    },
    data() {
        return {
            escalated_reason: ''
        }
    },
    methods: {
        cancelEscalasion() {
            this.$emit('cancelEscal');
        },
        async saveEscalasionReason() {
             await LeadApplicationService.saveEscalateReason(this.escalated_reason, this.leadSummary.id);
            this.$emit('sucessSaveEscal');
        }
    }
}
</script>

<style scoped>

</style>
