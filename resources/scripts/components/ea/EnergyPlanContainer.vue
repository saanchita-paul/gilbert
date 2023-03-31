<template>
    <v-row no-gutters>
    <header class="header-area py-4 px-3 rounded-t">
        <h2 class="display-1">{{ planDetails.name }}</h2>
        <p class="subtitle-1">{{ planText }}</p>
        <p class="body-2 mb-0">{{ planDetails.slogan }}</p>
    </header>
    <div class="content px-3 background-white">
                    <div class="mt-3 mb-2" v-if="isPlanHasElectricity">
                        <p class="mb-1">{{ planDetails.description }}</p>
                        <p class="mb-1">{{ planDetails.gst_inclusive_text }}</p>
                        <p class="mb-1">Customer type:
                            <span class="bold-text">{{ planDetails.customer_type }}</span>
                        </p>
                        <p class="mb-1">Distributor:
                            <span  class="bold-text">{{ planDetails.distributor_name.electricity }}</span>
                        </p>
                        <p class="mb-1 ">
                            <small>{{planDetails.disclaimer}}</small>
                        </p>

                        <!-- <p  v-if="(plan === 'total_plan' || plan === 'no_frills')" class="pt-2 mb-4 small-text">
                            <span  class="font-weight-bold">* If credit is offered </span> - This is 25% less than the reference price, Which includes the credit of $25)
                            <span style="display: block" class="">
                                This is just an estimate and your bills will be based on your actual usage over the year and your tariff type.
                            </span>
                        </p> -->



                    </div>
                    <v-divider></v-divider>
                    <p class="title font-weight-bold mt-3 mb-2">{{ planDetails.promotion_title }}</p>
                    <div v-if="plan === 'basic_plan'">
                        <p class="mb-3"><span class="font-weight-bold">Standard  Plan </span></p>
                    </div>
                    <div v-if="plan !== 'basic_plan'">
                        <div class="d-flex mb-2" v-if="isPlanHasElectricity">
                            <div class="mx-3 ">
                                <v-icon
                                    medium
                                    color="blue lighten-1"
                                >
                                    mdi mdi-lightbulb-cfl
                                </v-icon>
                            </div>
                            <div>

                                <p v-if="planDetails.state_distributor_discount.elec_credit_amount === '' || planDetails.state_distributor_discount.elec_credit_amount === null || planDetails.state_distributor_discount.elec_credit_amount === 0" class="mb-0"><span class="font-weight-bold">{{ planDetails.promotional_texts.electricity }}</span>
                                </p>
                                <p v-else class="mb-0"><span class="font-weight-bold">{{ planDetails.promotional_texts.electricity }}  + ${{ planDetails.state_distributor_discount.elec_credit_amount}} credit </span>
                                </p>
                                <p v-if="isTopPlan">guaranteed </p>
                                <p v-if="plan === 'no_frills'">Simplified energy pricing</p>
                            </div>
                        </div>

                        <div class="d-flex mb-2" v-if="isPlanHasGas">
                            <div class="mx-3">
                                <v-icon
                                    medium
                                    color="red"
                                >
                                    mdi mdi-fire
                                </v-icon>
                            </div>
                            <div>
                                <p v-if="planDetails.state_distributor_discount.gas_credit_amount === '' || planDetails.state_distributor_discount.gas_credit_amount === null || planDetails.state_distributor_discount.gas_credit_amount === 0" class="mb-0"><span class="font-weight-bold">{{ planDetails.promotional_texts.gas  }}</span></p>
                                <p v-else class="mb-0"><span class="font-weight-bold">{{ planDetails.promotional_texts.gas }}  + ${{ planDetails.state_distributor_discount.gas_credit_amount}} credit </span></p>
                                <p v-if="isTopPlan">guaranteed</p>
                                <p v-if="plan === 'no_frills'">Simplified energy pricing</p>
                            </div>
                        </div>
                    </div>
                    <v-divider></v-divider>

                    <p class="title font-weight-bold mt-3">Features</p>

                    <div v-if="plan === isTopPlan" class="mb-3">
                        <p class="mb-0 font-weight-bold">Discount</p>
                        <p class="mb-0">{{planDetails.discounts.electricity}}% discount off off total electricity bill. Guaranteed discount that applied to your energy charges</p>
                        <p class="mb-0">A variable rate, with a total energy bill discount.</p>
                    </div>

                    <div class="mb-3" v-for="feature in planDetails.features" :key="feature.title">
                        <p class="mb-0 font-weight-bold">{{ feature.title }}</p>
                        <p class="mb-0">{{ feature.description }}</p>
                    </div>

                    <p @click="goToSearch" style="cursor: pointer" class="mt-5 text-decoration-underline font-weight-bold green--text">Basic plan information and energy
                        fact sheets</p>

                    <v-divider></v-divider>

                    <div class="d-flex justify-space-between mt-4">
                        <div class="title font-weight-bold">Rates:</div>
                        <!--                            <div class="d-flex align-center">-->
                        <!--                                <div class="mx-2">Inc GST</div>-->
                        <!--                                <div><v-switch v-model="planDetails.rates.include_gst" color="success" class="mt-0" hide-details></v-switch></div>-->
                        <!--                            </div>-->
                    </div>
                    <div class="my-1">
                        <p>{{ `These ${planText} rates are based on: Postcode ${postcode}` }}</p>
                        <!--                            <p class="my-2">Solar feed-in tariffs are under review from 1 February 2021. If this happens,-->
                        <!--                                new rates can be viewed online from this date. More information on rate changes-->
                        <!--                                <a class=" icon green&#45;&#45;text"-->
                        <!--                                   href="https://www.energyaustralia.com.au/home/electricity-and-gas/solar-power/feed-in-tariffs"-->
                        <!--                                   target="_blank"-->
                        <!--                                >here</a>.</p>-->
                    </div>
                    <div v-if="isVictorian" class="py-3 green--text text-center">
                        <p>The VDO is the Victorian Government’s regulated electricity offer.</p>
                    </div>

                    <div class="my-3">
                        <p v-for="(de, index) in planDetails.rates.description" :key="index">
                            {{ de }}
                        </p>
                    </div>


                    <v-expansion-panels>
                        <v-expansion-panel v-if="isPlanHasElectricity">
                            <v-expansion-panel-header color="grey lighten-4">
                                Show electricity rates
                            </v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <p class=" my-2">
                                    Distributor name:
                                    <span class="font-weight-bold">{{planDetails.distributor_name.electricity}}</span>
                                </p>
                                <p class="mb-0 mt-3">{{ planDetails.rates.electricity.daily_supply_charge.title }}
                                    <v-icon small> mdi mdi-alert-circle</v-icon>
                                </p>
                                <div v-if="isTopPlan">
                                    <p><small>Before discount</small></p>
                                    <p>{{ planDetails.rates.electricity.daily_supply_charge.before_discount }}</p>
                                    <p><small>After discount</small></p>
                                    <p>{{ planDetails.rates.electricity.daily_supply_charge.after_discount }}</p>
                                </div>
                                <div v-else>
                                    <p>{{ planDetails.rates.electricity.daily_supply_charge.before_discount }}</p>
                                </div>
                                <p class="mt-3 mb-1">Usage rates
                                    <v-icon small> mdi mdi-alert-circle</v-icon>
                                </p>
                                <div
                                    v-for="(value, key) in planDetails.rates.electricity.usage_rates"
                                    :key="key"
                                    class="outlined pa-3"
                                >
                                    <p class="font-weight-bold">
                                        {{ value.title }}</p>
                                    <div v-if="isTopPlan">
                                        <p><small>Before discount</small></p>
                                        <p>
                                            {{
                                                value.before_discount
                                                    | price_with_4_decimal
                                            }}</p>
                                        <p><small>After discount</small></p>
                                        <p>
                                            {{
                                                value.after_discount
                                                    | price_with_4_decimal
                                            }}</p>
                                    </div>
                                    <div v-else>
                                        <p>Flat rate</p>
                                        <p>
                                            {{
                                                value.before_discount
                                                    | price_with_4_decimal
                                            }}</p>
                                    </div>

                                </div>


                                <p class="mt-5 pt-5">
                                    All prices listed above included GST.
                                </p>
                                <p class="pt-2">
                                    EnergyAustralia’s Basic plan electricity rates for Victoria and solar rates on any plan for all states are under review and may change 1st September. Solar Feed in Tariffs are not fixed and may decrease. Please note that depending on when you sign up, your quoted rates may differ from those on your first bill once these changes come into effect. You will be notified of any applicable changes in your welcome pack or on your first bill. This information will also be available on the EnergyAustralia website.
                                </p>
                            </v-expansion-panel-content>
                        </v-expansion-panel>

                        <v-expansion-panel v-if="isPlanHasGas">
                            <v-expansion-panel-header color="grey lighten-4">
                                Show gas rates
                            </v-expansion-panel-header>
                            <v-expansion-panel-content>
                                <!-- <p class=" my-2">
                                    Distributor name:
                                    <span class="font-weight-bold">{{planDetails.distributor_name.electricity}}</span>
                                </p> -->
                                <template  v-if="planDetails.rates.gas.session_all_year">
                                    <DailySupplyCharge
                                        :plan="plan"
                                        :usage_rates="planDetails.rates.gas.usage_rates"
                                    ></DailySupplyCharge>
                                    <GasUsage
                                        :usage_rates="planDetails.rates.gas.usage_rates"
                                        :plan="plan"
                                    ></GasUsage>
                                </template>

                                <template v-else>
                                    <DailySupplyCharge
                                        :plan="plan"
                                        :usage_rates="planDetails.rates.gas.usage_rates[0].data"
                                    ></DailySupplyCharge>
                                    <GasUsage
                                        class=""
                                        :key="rates.session_description"
                                        v-for="rates in planDetails.rates.gas.usage_rates"
                                        :session_description="rates.session_description"
                                        :plan="plan"
                                        :usage_rates="rates.data"
                                    ></GasUsage>
                                </template>
                                <p class="mt-5 pt-5">
                                    All prices listed above included GST.
                                </p>
                            </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-expansion-panels>
                    <p class="my-3">{{ planDetails.state_disclaimer }}</p>
                    <p class="title font-weight-bold mt-3 mb-2">Plan details</p>
                    <p class="mb-2"><span class="font-weight-bold ">Benefit period:</span>
                        {{ planDetails.plan_details.benefit_period }}
                        <!-- <v-icon small> mdi mdi-alert-circle</v-icon> -->
                    </p>
                    <p class="mb-2"><span class="font-weight-bold">Exit Fees: </span> {{ planDetails.plan_details.exit_fees }}
                        <!-- <v-icon small> mdi mdi-alert-circle</v-icon> -->
                    </p>
                    <p v-if="isPlanHasGas" class="mb-2"><span class="font-weight-bold">Gas Connection Fee: </span>
                        $ {{ planDetails.connection_fees.gas }} <small>(Incl. GST)</small>
                        <!-- <v-icon small> mdi mdi-alert-circle</v-icon> -->
                    </p>
                    <p v-if="planDetails.distributor_name.gas === 'Envestra VIC'" class="line-height-18t mb-2">
                        The connection fee for your gas is up to $15.07 including GST, which will appear on your first bill.
                    </p>
                    <p v-if="isPlanHasElectricity" class="mb-2"><span class="font-weight-bold">Electricity Connection Fee: </span>
                        $ {{ planDetails.connection_fees.electricity }} <small>(Incl. GST)</small>
                        <!-- <v-icon small> mdi mdi-alert-circle</v-icon> -->
                    </p>
                    <p class="mb-2"><span class="font-weight-bold">Rates: </span> {{ planDetails.plan_details.rates }}
                    </p>
                    <p class="mb-2"><span class="font-weight-bold">Late Payment Fee:</span>
                        {{ planDetails.plan_details.late_payment_fee }}
                        <!-- <v-icon small> mdi mdi-alert-circle</v-icon> -->
                    </p>
                    <p class="mb-2">Customer type: {{ planDetails.plan_details.customer_type }}
                        <!-- <v-icon small> mdi mdi-alert-circle</v-icon> -->
                    </p>

                    <v-divider></v-divider>
                    <p class="title font-weight-bold mt-3 mb-2">Solar buy back rate</p>
                    <!-- <p class="mb-2"> -->
                    <!-- Single rate feed-in tariff (excl. GST)^^: -->
                    <!-- Solar rate feed-in tariff if applicable (Excl. GST):
                    {{ planDetails.solar_buy_pack_rate.single_rate_c_kwh }}c/kwh -->
                    <!-- </p> -->

                    <p class="font-weight-bold d-inline-flex col-sm-8 col-8 px-0">Solar rate feed-in tariff if applicable (Excl. GST)</p>
                    <p class="col-sm-3 col-3 px-0 d-inline-flex" > <b>: </b> {{ planDetails.solar_buy_pack_rate.single_rate_c_kwh }}c/kwh</p>


                    <!-- <div v-if="isVictorian"> -->
                    <!--                            <p class="mb-2">Time of use feed-in tariff (excl. GST)^^: {{}}</p>-->
                    <!-- <p >Peak: {{ planDetails.solar_buy_pack_rate.peak_c_kwh }}c/kwh
                        <EnergyTooltip
                            :value="solarBuyPackInfo.peak_c_kwh"
                        ></EnergyTooltip>
                    </p>
                    <p >Shoulder: {{ planDetails.solar_buy_pack_rate.shoulder_c_kwh }}c/kwh
                        <EnergyTooltip
                            :value="solarBuyPackInfo.shoulder_c_kwh"
                        ></EnergyTooltip>
                    </p>
                    <p >Off Peak: {{ planDetails.solar_buy_pack_rate.off_peak_c_kwh }}c/kwh
                        <EnergyTooltip
                            :value="solarBuyPackInfo.off_peak_c_kwh"
                        ></EnergyTooltip>
                    </p>
                </div> -->

                    <v-divider></v-divider>
                    <!--                        <p ref="bdid" class="title font-weight-bold mt-3 mb-2">Search for a Plan Document</p>-->
                    <!--                        <p class="font-weight-bold px-0">Please confirm your postcode</p>-->
                    <!--                        <TarrifSearch-->
                    <!--                            :utility=planText-->
                    <!--                            :planPostcode = getPostcode-->
                    <!--                        >-->
                    <!--                        </TarrifSearch>-->

                    <div class="py-4 policy">
                        <a
                            class="v-btn v-btn--flat icon green--text"
                            href="https://www.energyaustralia.com.au/privacy"
                            target="_blank"
                        >
                            <v-icon color="green">mdi-link</v-icon>
                            Privacy and policy</a>
                        <a
                            class="v-btn v-btn--flat green--text icon"
                            href="https://www.energyaustralia.com.au/creditstatement"
                            target="_blank"
                        >
                            <v-icon color="green">mdi-link</v-icon>
                            Credit statement</a>
                        <a
                            class="v-btn v-btn--flat icon green--text"
                            href="https://www.energyaustralia.com.au/conditions-pricing"
                            target="_blank"
                        >
                            <v-icon color="green">mdi-link</v-icon>
                            Market retail contract</a>
                    </div>
                    <!--                        <v-btn-->
                    <!--                            block-->
                    <!--                            class="white&#45;&#45;text mb-2"-->
                    <!--                            color="green darken-2 "-->
                    <!--                        >-->
                    <!--                            Selected-->
                    <!--                            <v-icon right>-->
                    <!--                                mdi-check-circle-->
                    <!--                            </v-icon>-->
                    <!--                        </v-btn>-->

                </div>
    </v-row>
</template>

<script>

import {isTopEAPlan, SERVICE_TYPES, SOLAR_PACK} from "@scripts/models/ea/EnergyPlan";
import GasUsage from "@scripts/components/ea/GasUsage";
import DailySupplyCharge from "@scripts/components/ea/DailySupplyCharge";
import EnergyTooltip from "@scripts/components/ea/EnergyTooltip";
// import TarrifSearch from "@scripts/components/ea/TarrifSearch";


export default {
    name: "EnergyPlanContainer",
    props: ['postcode', 'state', 'plan', 'services', 'planDetails'],

    components: {
        GasUsage,
        EnergyTooltip,
        DailySupplyCharge,
        // TarrifSearch
    },

    data() {
        return {
            // loaded: false,
            // planDetails: new EnergyPlan(),
            solarBuyPackInfo: SOLAR_PACK
        }
    },
    computed: {
        isVictorian() {
            return this.postcode && this.postcode.length === 4 && this.postcode.slice(0, 1).toString() === '3'
        },
        planText() {
            switch (this.services) {
                case SERVICE_TYPES.GAS:
                    return 'Gas';
                case SERVICE_TYPES.ELECTRICITY:
                    return 'Electricity';
                default:
                    return 'Electricity & Gas'
            }
        },
        isPlanHasGas() {
            return  !!this.planDetails.rates.gas;
            // const plan = this.customer.plan.which_utility;
            // return plan === ENERGY_PLANS.GAS || plan === ENERGY_PLANS.ELECTRICITY_AND_GAS
        },
        isPlanHasElectricity() {
            return  !!this.planDetails.rates.electricity;
            // const plan = this.customer.plan.which_utility;
            // return plan === ENERGY_PLANS.ELECTRICITY || plan === ENERGY_PLANS.ELECTRICITY_AND_GAS
        },
        // hasCreditAmount() {
        //     if(this.planDetails.state_distributor_discount.gas_credit_amount) {
        //         return this.planDetails.state_distributor_discount;
        //     }
        // },
        isTopPlan() {
            return isTopEAPlan(this.plan)
        }
    },

    filters: {
        estimated_price(value) {
            return Math.ceil(value)
        },
    },

    methods: {
        goToSearch() {
            if (this.$refs.bdid) {
                this.$refs.bdid.scrollIntoView({behavior: "smooth"});
            }
        }
    }
};
</script>

<style scoped>
.header-area {
    background-color: rgb(0, 83, 33);
    color: #ffffff;
    width: 100% !important;
}

.outlined {
    border: 1px solid #e9e9e9;
}

.policy {
    flex-wrap: wrap;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: flex-start;
    align-content: flex-start;
}

.background-white{
    background: #FFFFFF;
}

.line-height-18 {
    line-height: 18px;
}
</style>
