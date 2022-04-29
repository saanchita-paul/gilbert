<template>
    <div class="plan-details">
        <div class="d-flex">
            <v-icon color="yellow" size="20" class="pb-4 pr-2">mdi-flash</v-icon>
            <p class="font-weight-bold"> Electricity </p>
        </div>
        <div class="pb-2">
                <p class="font-weight-bold" style="font-size:26px">$1,212/year</p>
                <p class="font-weight-bold" style="font-size:14px">70% off the Victorian Default Offer Reference Price</p>
                <p class="plan-content">Estimated cost inc GST for an average household using 3900 kWh/yearly on a flat rate tariff in the Ausgrid network.</p>
        </div>
        <v-expansion-panels >
            <v-expansion-panel color="red">
                <v-expansion-panel-header class="font-weight-bold">
                    See electricity prices
                    <template v-slot:actions><v-icon color="orange">mdi-menu-down</v-icon></template> 
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                    <div class="d-flex">
                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Supply Charge</p>
                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                            mdi-progress-question
                        </v-icon>
                    </div>
                    <div class="price-list">
                        <p class="pr-12 plan-text" style="font-size:14px">Daily Supply Charge
                            <span>(c/day)</span>
                        </p>
                        <p class="pl-14 plan-text">76.99</p>
                    </div>

                    <div class="d-flex">
                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Usage Charges</p>
                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                            mdi-progress-question
                        </v-icon>
                    </div>
                    <div class="price-list">
                        <p class="plan-text" style="font-size:14px">Peak Usage
                            <span>(c/kWh)</span>
                        </p>
                        <p class="plan-text">23.87 </p>
                    </div>
                    <p class="plan-text">We’ve already included any discounts in the rates above. All prices are inclusive of GST.</p>
                    <p class="plan-text">Rates are rounded up to the nearest 2 decimal places where applicable.</p>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>
    </div>
</template>

<script>

export default {
    data() {
        return {
        }
    },
    props: {
        
    },
}
</script>

<style scoped>

</style>
