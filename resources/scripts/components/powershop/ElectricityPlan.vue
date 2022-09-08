<template>
    <div v-if="plan">
        <div class="plan-title-header">
            <div class="d-flex align-center">
                <v-img
                    max-height="50"
                    max-width="50"
                    class="mr-2"
                    src="/assets/images/logo/providers/powershop_logo.png"
                ></v-img>
                <h3>Powershop</h3>
            </div>
            <div>
                <h2>{{ getSelectedElectricityPlan.name }}</h2>
                <p>Electricity</p>
            </div>
        </div>
        <div class="plan-details">
            <v-card>
                <div style="padding: 20px 10px">
                    <div class="text-center">
                        <div class="d-flex justify-center align-center">
                            <v-icon color="yellow" size="25" class="mr-2">mdi-flash</v-icon>
                            <h2 class="font-weight-bold plan-heading-text mb-2">Electricity</h2>
                        </div>
                        <div class="font-weight-bolder">

                            <p class="mb-2 bolder-text">{{ getSelectedElectricityPlan.title }} <span class="deep-text">(inc. GST)</span></p>
                            <p class="mb-2 bolder-text">{{ getSelectedElectricityPlan.line_2 }} <span class="deep-text">(less then the)</span></p>
                            <p class="mb-4">
                                <span v-if="victoriaState" class="linkable">Victorian Default Offer</span>
                                <span v-else class="linkable">Reference Pricing</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <p class="paragraph-text">{{ getSelectedElectricityPlan.line_1 }}</p>
                        <p class="paragraph-text">Your actual bills will vary depending on your usage and any price changes in the future.
                            You'll be notified of any change in accordance with our regulatory requirements.</p>
                    </div>
                    <div class="d-flex">
                        <h4 class="font-weight-bold plan-heading-text mr-2">Your distributor</h4>
                        <p class="deep-text">{{ plan.distributor_name }}</p>
                    </div>
                    <v-divider></v-divider>
                    <div>
                        <v-expansion-panels :value="opened">
                            <v-expansion-panel>

                                <v-expansion-panel-header class="font-weight-bold" style="font-size: 16px">
                                    Electricity usage and rates
                                    <template v-slot:actions>
                                        <v-icon color="#F1186C">
                                            $expand
                                        </v-icon>
                                    </template>
                                </v-expansion-panel-header>

                                <v-expansion-panel-content>

                                    <div class="d-flex">
                                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Supply Charge</p>
                                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                            mdi-help-circle-outline
                                        </v-icon>
                                    </div>

                                    <div class="price-list">
                                        <div class="plan-text" style="font-size:14px">
                                            Daily Supply Charge (c/day)
                                        </div>
                                        <div class="plan-text">{{ plan.supply_charge }}</div>
                                    </div>

                                    <div class="d-flex">
                                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Usage Charges</p>
                                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                            mdi-help-circle-outline
                                        </v-icon>
                                    </div>

                                    <div class="price-list">
                                        <div class="plan-text" style="font-size:14px">
                                            Single rate tarrif (c/kWh)
                                        </div>
                                        <div  class="plan-text">{{ plan.usage_charge }}</div>
                                    </div>

                                    <div class="d-flex mt-8">
                                        <p class="font-weight-bold" style="font-size:14px; margin-bottom: 2%">Fees</p>
                                        <v-icon aria-hidden="false" class="pl-1 pb-1" size="80%">
                                            mdi-help-circle-outline
                                        </v-icon>
                                    </div>

                                    <div class="price-list" v-for="fees in plan.fees">
                                        <div class="plan-text" style="font-size:14px">
                                            {{ fees.title }}
                                        </div>
                                        <div  class="plan-text">{{ fees.fees }}</div>
                                    </div>

                                </v-expansion-panel-content>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </div>
                </div>
            </v-card>
        </div>

    </div>

</template>

<script>
import PowershopMapper from "@scripts/modules/powershop/api/mappers/PowershopMapper";
export default {
    props: {
        plan: {
            require: true,
        },
        victoriaState: {
            require: false
        },
        selectedPlan: {
            require: false
        },
    },
    data() {
        return {
            opened: 0,
        }
    },
    computed: {
        getSelectedElectricityPlan() {
            const plan = this.plan?.vdo.find((item) => item.name === this.selectedPlan.name);

            if (plan){
                return {
                    name: plan?.marketing_offer_name,
                    title: (plan.vdo_dmo_amount && plan.vdo_dmo_amount.charAt(0) != "$" ? "$" : "")+ plan?.vdo_dmo_amount + "/Year",
                    line_1 : "For an average household using "+ plan?.consumption +" kWh/year, the estimated annual cost of this electricity plan is " + (plan.vdo_dmo_amount && plan.vdo_dmo_amount.charAt(0) != "$" ? "$" : "") + plan?.vdo_dmo_amount + " in the "+ this.plan.distributor_name +" network with single rate tariff.",
                    line_2 :  plan?.vdo_dmo_percentage + "%",
                }
            }
            return {
                name: "",
                title: "",
                line_1 : "",
                line_2 : "",
            }
        }
    },
    methods: {
    },

    mounted() {
    }
}
</script>

<style scoped>
.plan-title-header {
    background-color: #F1186C;
    color: white;
    padding: 10px;
}
.plan-details {
    margin: 20px 0;
    padding: 0 10px;
}
.plan-details .v-expansion-panel::before {
    box-shadow: none !important;
}
.price-list {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}
.plan-heading-text {
    color: #662445;
}
.v-expansion-panel-header {
    padding: 0;
}
.theme--light.v-expansion-panels >>> .v-expansion-panel-content__wrap {
    padding: 0;
}
.v-sheet.v-card {
    border-radius: 10px;
}
.deep-text {
    font-size: 14px;
    font-weight: normal;
}
.bolder-text{
    font-size: 20px;
    font-weight: bolder;
}
.linkable {
    color: #F1186C;
    font-size: 14px;
    text-decoration: underline;
}
.paragraph-text {
    font-size: 14px;
    font-weight: 400;
    text-align: justify;
}
</style>
