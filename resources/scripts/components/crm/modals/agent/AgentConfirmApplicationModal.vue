<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="580px"
        >
            <v-card>
                <v-container>
                    <AgentConfirmApplicationDetails
                        :application="application"
                        :identification="identification"
                        :authorisedPerson="authorisedPerson"
                        :has_authorized="has_authorized"
                        @cancelDialog="cancelApplicationModal"
                        @saveApplication="saveApplication">
                    </AgentConfirmApplicationDetails>
                </v-container>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import AgentConfirmApplicationDetails from "@scripts/components/crm/agent/AgentConfirmApplicationDetails";
export default {
name: "AgentConfirmApplicationModal",
    components: {AgentConfirmApplicationDetails},
    props:['dialog', 'application','identification','authorisedPerson', 'has_authorized'],
    methods: {
        cancelApplicationModal() {
            this.$emit('cancelApplicationModal');
        },
        saveApplication() {
           this.$emit('saveApplication');
        }
    }
}
</script>

<style scoped>

</style>
