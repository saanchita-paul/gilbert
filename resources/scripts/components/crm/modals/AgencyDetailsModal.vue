<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <v-container>
                    <AgencyDetails @cancelDialog="cancelDialog"  @saveAgency="saveAgency"></AgencyDetails>
                </v-container>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import AgencyDetails from "@scripts/components/crm/AgencyDetails";
import SetupIndeAgency from "@scripts/components/crm/SetupIndeAgency";
export default {
name: "AgencyDetailsModal",
    components: {SetupIndeAgency, AgencyDetails},
    props:['dialog'],
    methods: {
        cancelDialog() {
            this.$emit('cancelDialog');
        },
        saveAgency(agency) {
           this.$emit('openSuccessfulModal',agency);
        }
    }
}
</script>

<style scoped>

</style>
