<template>
    <v-row>
        <v-col cols="8" class="mb-8 pb-8">
                <ServiceApplications @updateDraft="updateDraft" @updateService="updateService" :afterHourFlag="afterHourFlag" :leadSummary="leadSummary" @updatePlan="updatePlan"></ServiceApplications>
        </v-col>
        <v-col cols="4" class="mb-8 pb-8">
            <v-card class="hood-card">
                <ApplicationNotes :notes="notes" @saveNote="saveNote"></ApplicationNotes>
            </v-card>
        </v-col>
    </v-row>
</template>

<script>
import ServiceApplications from "@scripts/components/crm/leadmanagement/ServiceApplications";
import ApplicationNotes from "@scripts/components/crm/leadmanagement/ApplicationNotes";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";

export default {
name: "LeadServicesAndNotes",
    props: {
        notes: {

        },
        leadSummary: {
            require: true
        },
        afterHourFlag: {
            require: false
        }
    },
    components:{
        ServiceApplications,
        ApplicationNotes
    },
    methods: {
        updateDraft( field, value, isDate, identification, isManualChangeFlag) {
            this.$emit('updateDraft', field, value, isDate, identification, isManualChangeFlag );
        },
        updatePlan(plan, isManual)
        {
            this.$emit('updatePlan', plan, isManual);
        },
        async saveNote(note) {
            await LeadApplicationService.saveNote(note, this.leadSummary.id);
            this.$emit('updateNote');
        },
        updateService(service)
        {
            this.$emit('updateService',service);
        },
        setUtilityServicesToStore(services)
        {
            const powerService = services.find(
                data => data.service_type === "power"
            );
            const gasService = services.find(
                data => data.service_type === "gas"
            );

            UtilityStoreService.setPowerProvider(powerService?.provider_name);
            UtilityStoreService.setPowerPlan(powerService?.plan_type);
            UtilityStoreService.setGasProvider(gasService?.provider_name);
            UtilityStoreService.setGasPlan(gasService?.plan_type);

            if(powerService?.provider_name === gasService?.provider_name
                && powerService?.plan_type === gasService?.plan_type
                && powerService?.plan_type !== null
                && LeadApplicationService.canSubmitEnergy(services)
            ) {
                UtilityStoreService.setIsBothEnergySelected(true);
            }
        }
    },
    mounted() {
        console.log("Lead summary", this.leadSummary);
        this.setUtilityServicesToStore(this.leadSummary.connection_services);
    }
}
</script>

<style scoped>

</style>
