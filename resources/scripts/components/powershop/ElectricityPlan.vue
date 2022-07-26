<template>
    <div>
<!--        <div class="plan-title-header pl-4 pr-4 pb-2 d-flex justify-space-between">-->
<!--            <div>-->
<!--                <p class="font-weight-bold mb-0" style="font-size:24px">Origin Home Assist </p>-->
<!--                <p style="font-size:20px">Electricity</p>-->
<!--            </div>-->
<!--        </div>-->

        <div class="plan-details">
            <div class="d-flex">
                <v-icon color="yellow" size="20" class="pb-4 pr-2">mdi-flash</v-icon>
                <p class="font-weight-bold">Electricity</p>
            </div>
            <div class="pb-2">
                <p class="font-weight-bold" style="font-size:26px">Title</p>
                <p class="font-weight-bold" style="font-size:14px">text here</p>
                <p class="plan-content">text here</p>
                <p class="plan-content">Guarantee usage and supply charges will not increase for 12 months.</p>
                <p class="plan-content">Other charges and any solar feed-in tariff may change.</p>
            </div>
            <v-expansion-panels>
                <v-expansion-panel color="red">
                    <v-expansion-panel-header class="font-weight-bold">
                        See electricity prices
                        <template v-slot:actions>
                            <v-icon color="orange">mdi-menu-down</v-icon>
                        </template>
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <div class="d-flex">
                            <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Supply Charge</p>
                            <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                mdi-progress-question
                            </v-icon>
                        </div>
                        <div class="price-list">
                            <div class="pr-12 plan-text" style="font-size:14px">

                            </div>
                            <div class="pl-14 plan-text"></div>
                        </div>

                        <div class="d-flex">
                            <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Usage Charges</p>
                            <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                mdi-progress-question
                            </v-icon>
                        </div>
                        <div class="price-list">
                            <div class="plan-text" style="font-size:14px">

                            </div>
                            <div class="plan-text"></div>
                        </div>

                        <div class="d-flex">
                            <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Fees</p>
                        </div>
                        <div class="price-list">
                            <div class="plan-text" style="font-size:14px">
                                Standard Connection Fee
                            </div>
                            <div class="plan-text">fee</div>
                        </div>
                        <div class="price-list">
                            <div class="plan-text" style="font-size:14px">
                                Same Day Connection Fee
                            </div>
                            <div class="plan-text">same day</div>
                        </div>

                        <!--					<p class="plan-text mt-3">We’ve already included any discounts in the rates above. All prices are inclusive of GST.</p>-->
                        <p class="plan-text">Rates may have been rounded up to the nearest decimal place.</p>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>
    </div>

</template>

<script>
export default {
    props: {},
    methods: {
        closeDialog() {
            this.$emit('toggleDialog')
        }
    }
}
</script>

<style scoped>
.plan-details {
    padding: 4% 2% 4% 6%;
    margin: 2% 2% 8% 2%;
    outline: #cccaca solid 1px;
}

.plan-details .v-expansion-panel::before {
    box-shadow: none !important;
}

.plan-title {
    padding-top: 8%;
    padding-left: 8%;
}

.plan-text {
    color: #505050;
    display: block;
    font-size: 14px;
    font-family: sans-serif;
    line-height: 24px;
    text-rendering: optimizeLegibility;
    text-transform: none;
    -webkit-font-smoothing: antialiased;
}

.plan-content {
    color: #505050;
    font-size: 14px;
    font-family: sans-serif;
    margin-top: 0%;
    padding-top: 0%;
}

.price-list {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.plan-title-header {
    background-color: #cd5b32;
    color: white;
}

</style>
