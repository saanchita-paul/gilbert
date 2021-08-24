<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <v-container v-if="isLoaded">
                    <AgecnyNameUpdate :title="title" @cancelDialog="cancelDialog"  @saveAgency="saveAgency"> </AgecnyNameUpdate>
                </v-container>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import AgecnyNameUpdate from "@scripts/components/crm/modals/AgecnyNameUpdate";
import AgencyService from "@scripts/services/crm/AgencyService";

export default {
name: "AgencyEditModal",
    components:{AgecnyNameUpdate},
    props:['dialog', 'agencyId'],
    data() {
        return {
            title: '',
            isLoaded: false
        }
    },
    methods: {
        cancelDialog() {
            this.$emit('cancelDialog');
        },
        saveAgency(agency) {
            this.$emit('openSuccessfulModal',agency);
        },
        async loadAgencyData() {
            const agency = await AgencyService.getAgency(this.agencyId);
            this.title = agency.title;
            console.log('agency tilte',agency.title)
            this.isLoaded = true;

        }
    },
    mounted() {
        this.loadAgencyData();
    }
}
</script>

<style scoped>

</style>
