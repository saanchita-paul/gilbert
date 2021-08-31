<template>
    <div v-if="usage_rates">

        <p class="mt-2 pt-5">Usage rates
            <v-icon small> mdi mdi-alert-circle</v-icon>
        </p>
        <div class="outlined my-2 pa-3">
            <div v-if="session_description">
                <p class="font-weight-bold">Rates for period:</p>
                <p class="mb-2">{{ session_description }}</p>
            </div>

            <template v-if="plan === 'total_plan'">
                <div
                    class="mt-3"
                    v-if="key !== 'daily_supply_charge' && value.title"
                    v-for="(value, key) in usage_rates" :key="key"
                >
                    <p class="font-weight-bold">{{ value.title || key | gas_rate_key }}</p>
                    <p><small>Before discount</small></p>
                    <p class="mb-2">{{ value.before_discount | price_with_4_decimal }}</p>
                    <p><small>After discount</small></p>
                    <p>{{ value.after_discount | price_with_4_decimal }}</p>
                </div>
            </template>
            <template v-else>
                <div
                    v-for="(value, key) in usage_rates" v-if="value.title" :key="key">
                    <p class="font-weight-bold">{{ value.title || key | gas_rate_key }}</p>
                    <p class="mb-0"><small>Flat rate</small></p>
                    <p class="mb-2">{{ value.before_discount |price_with_4_decimal }}</p>
                </div>
            </template>
        </div>

    </div>
</template>

<script>
import _ from "lodash";

export default {
    name: "GasUsage",
    props: ['plan', 'usage_rates', 'session_description'],
    filters: {
        gas_rate_key(value) {
            return _.replace(_.capitalize(value), /_/g, ' ')
        }
    }
}
</script>

<style scoped>
.outlined {
    border: 1px solid #e9e9e9;
}
</style>
