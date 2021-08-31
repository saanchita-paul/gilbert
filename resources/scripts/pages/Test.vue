<template>
    <EnergyPlanDetailsPage
        plan="total_plan"
        postcode="3083"
        service_type="electricity_and_gas"
        state="=VIC"
    />
</template>

<script>
import EAPlanService from "@scripts/services/ea/EAPlanService";
import EnergyPlanDetailsPage from "@scripts/components/ea/EnergyPlanDetails";
export default {

    name: "Test",
    components: {EnergyPlanDetailsPage},
};
</script>

<style scoped>
</style>
