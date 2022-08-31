<template>
    <v-row class="personal-details-row">
        <v-col cols="6">
            <h3>Payment</h3>
            <h4 class="py-4">Payment Details</h4>
            <div class="crm-text-field">
                <div class="pr-4">
                    <h4>Payment Link: </h4>
                </div>

                <div >
                    <ValidationProvider name="Payment Link" rules="required" v-slot="{ errors }">
                        <v-menu offset-y>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn class="button-border" v-bind="attrs"  text v-on="on" :disabled="isDisable()">
                                    Send  link to customer  <span class="mdi mdi-send"></span>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item v-for="(item, index) in items" :key="index">
                                    <v-icon v-text="item.icon" class="pr-4"></v-icon>
                                    <v-list-item-title style="cursor : pointer" @click="sendPaymentLink(item.value)">{{ item.text }} </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </ValidationProvider>
                </div>
            </div>

            <div class="crm-text-field">
                <h4>Payment Status: </h4> <span class="grey--text pl-2"> {{ getPaymentStatus }} </span>
            </div>
        </v-col>

        <v-col cols="6" >
            <h3 class="pb-2">Estimated Billing</h3>
            <p class="mt-4">Please input values from 1-900 in fields below.</p>
            <div class="crm-text-field">
                <div class="pr-3 title-text">
                    <h4>Quarterly Cost (Power) </h4>
                </div>
                <div class="text-field">
                    <ValidationProvider
                        name="Cost"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <v-text-field
                            v-model="powerCost"
                            @blur="savePaymentInfo('estimated_elec_billing_cost', powerCost)"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Cost"
                            :error-messages="errors[0]"
                        >
                            <template v-slot:append>
                                <span class="custom-placeholder">AUD</span>
                            </template>
                        </v-text-field>
                    </ValidationProvider>
                </div>
            </div>

            <div class="crm-text-field">
                <div class="pr-3 title-text">
                    <h4>Quarterly Cost (Gas)</h4>
                </div>
                <div class="text-field">
                    <ValidationProvider
                        name="Cost"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <v-text-field
                            v-model="gasCost"
                            @blur="savePaymentInfo('estimated_gas_billing_cost', gasCost)"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Cost"
                            :error-messages="errors[0]"
                        >
                            <template v-slot:append>
                                <span class="custom-placeholder">AUD</span>
                            </template>
                        </v-text-field>
                    </ValidationProvider>
                </div>
            </div>

        </v-col>
    </v-row>
</template>
<script>

import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import {powerShopPaymentStatusNumberToName} from "@scripts/data/PowershopDataMapper";
import PowershopService from "@scripts/modules/powershop/services/PowershopService";

export default {
    name: "PaymentDetails",
    props: {
        lead: {
            require: true,
        },
    },
    components: {
    },
    data() {
        return {
            items: [
                {
                    text: "via SMS",
                    value: "sms",
                    icon: "mdi-android-messages"
                },
                {
                    text: "via Email",
                    value: "email",
                    icon: "mdi-email"
                },
            ],
            paymentStatus: null,
            powerCost: null,
            gasCost: null
        }
    },
    computed: {
        getPaymentStatus() {
            return powerShopPaymentStatusNumberToName[this.paymentStatus] ?? this.lead.powershop_payment_status;
        },
    },
    methods: {
        async savePaymentInfo(field, value) {
            await PowershopService.updatePaymentInformation(field, value, this.lead.id);
        },
        synFormData() {
            this.powerCost = this.lead?.powershop_payment_info?.estimated_elec_billing_cost;
            this.gasCost = this.lead?.powershop_payment_info?.estimated_gas_billing_cost;
        },
        isDisable() {
            return this.lead.powershop_payment_status === "Valid";
        },
        async sendPaymentLink(linkType) {
            const response = await LeadApplicationService.sendPowershopPaymentLink(this.lead.id, linkType);
            this.paymentStatus = response.data?.status;
        }

    },

    async mounted() {
        await this.synFormData();
    }

};
</script>

<style scoped>
.button-border {
    border: 1px solid #263238;
}
.title-text {
    flex-basis: 40%;
}
.custom-placeholder {
    font-weight: 500;
}
</style>
