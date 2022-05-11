<template>
    <div>
        <p class="ma-0 pb-1 active-title text-center">
            {{ this.getProvider.provider }}
        </p>
        <p class="ma-0 active-subtitle text-center">
            {{ this.getPlan.plan }}
        </p>
    </div>
</template>

<script>
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";


export default {
    name: "EnergyStatus",
    props: {
        type: {
            require: true,
        },
        leadSummary: {
            require: true
        }
    },
    computed: {
        getProvider() {
            let provider = this.type.toLowerCase() === 'power' ? UtilityStoreService.getPowerProvider() : UtilityStoreService.getGasProvider();
            return LeadApplicationService.mapProvider(provider);
        },

        getPlan() {
            let plan = this.type.toLowerCase() === 'power' ? UtilityStoreService.getPowerPlan() : UtilityStoreService.getGasPlan();
            return LeadApplicationService.mapPlan(plan);
        },


        // provider() {
        //     return this.type === 'power' ? UtilityStoreService.getPowerProvider() : UtilityStoreService.getGasProvider();
        // },
        // getPlan() {
        //     return this.type === 'power' ? UtilityStoreService.getPowerPlan() : UtilityStoreService.getGasPlan();
        // }
    }
}
</script>

<style scoped>
.active-title {
    font-size: 12px !important;
    font-weight: 700;
}
.active-subtitle {
    font-size: 11px !important;
    font-weight: 500;
}
</style>
