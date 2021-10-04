<template>
    <div v-if="usage_rates && plan">
        <p class="font-weight-bold">Daily supply charge
            <v-icon small> mdi mdi-alert-circle</v-icon>
        </p>
        <div v-if="plan === 'total_plan'">
                <p><small>Before discount</small></p>
                <p>{{
                        daily_supply_charge.before_discount | price_with_4_decimal
                    }}</p>
                <p><small>After discount</small></p>
                <p>
                    {{
                        daily_supply_charge.after_discount | price_with_4_decimal
                    }}</p>
        </div>
        <div v-else>
            <p>
                {{
                    daily_supply_charge.before_discount | price_with_4_decimal
                }}</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "DailySupplyCharge",
    props: ['plan', 'usage_rates'],
    computed: {
        daily_supply_charge() {
            return this.usage_rates['daily_supply_charge']
        }
    }
}
</script>

<style scoped>

</style>
