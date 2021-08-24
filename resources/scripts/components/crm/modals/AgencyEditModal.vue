<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <v-container>
                    <AgecnyNameUpdate title="AAAAAAA" @cancelDialog="cancelDialog"  @saveAgency="saveAgency"> </AgecnyNameUpdate>
                </v-container>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import AgecnyNameUpdate from "@scripts/components/crm/modals/AgecnyNameUpdate";

export default {
name: "AgencyEditModal",
    components:{AgecnyNameUpdate},
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
