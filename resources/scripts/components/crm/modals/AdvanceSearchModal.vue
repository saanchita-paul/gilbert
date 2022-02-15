<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <v-card>
                    <AgencyAdvanceSearchDetails @filteredData="filteredData" @cancelDialog="cancelDialog"  @saveAgency="saveAgency"></AgencyAdvanceSearchDetails>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import AgencyAdvanceSearchDetails from "@scripts/components/crm/AgencyAdvanceSearchDetails";
import SetupIndeAgency from "@scripts/components/crm/SetupIndeAgency";

export default {
name: "AdvanceSearchModal",
    components: {SetupIndeAgency, AgencyAdvanceSearchDetails},
    props:['dialog'],
    methods: {
        cancelDialog() {
            this.$emit('cancelDialog');
        },
        saveAgency(agency) {
           this.$emit('openSuccessfulModal',agency);
        },
        filteredData(data){
            this.$emit('filteredData', data);
        }
    }
}
</script>

<style scoped>

</style>
