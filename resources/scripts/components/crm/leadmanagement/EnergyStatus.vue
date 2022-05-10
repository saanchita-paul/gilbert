<template>
    <div>
        <p class="ma-0 pb-1 active-title text-center">
            {{ this.provider }}
        </p>
        <p class="ma-0 active-subtitle text-center">
            {{ this.plan }}
        </p>
    </div>
</template>

<script>
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";

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
        provider() {
            return this.type === 'power' ? UtilityStoreService.getPowerProvider() : UtilityStoreService.getGasProvider();
        },
        plan() {
            return this.type === 'power' ? UtilityStoreService.getPowerPlan() : UtilityStoreService.getGasPlan();
        }
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
