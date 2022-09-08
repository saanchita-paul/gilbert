<template>
    <v-row class="personal-details-row">
        <v-col cols="6">
            <h3>Payment</h3>
            <h4 class="py-4">Payment Details</h4>
            <div class="crm-text-field justify-content">
                <div class="pr-4">
                    <h4>Payment Link </h4>
                </div>

                <div>
                    <v-menu offset-y>
                        <template  v-slot:activator="{ on, attrs }">
                            <v-btn @click="checkSameDayValidation" class="button-border" v-bind="attrs" text v-on="on" :disabled="disabledPaymentButton()">
                                Send link to customer <span class="mdi mdi-send"></span>
                            </v-btn>
                        </template>

                        <v-list v-if="!isLoading">
                            <v-list-item :disabled="!isEnable" v-for="(item, index) in items" :key="index">
                                <v-icon v-text="item.icon" class="pr-4"></v-icon>
                                <v-list-item-title style="cursor : pointer" @click="sendPaymentLink(item.value)">
                                    {{ item.text }}
                                </v-list-item-title>
                            </v-list-item>
                        </v-list>
                        <template v-else>
                            <v-skeleton-loader
                                type="list-item,list-item"
                            ></v-skeleton-loader>
                        </template>
                    </v-menu>

                </div>
            </div>

            <div class="crm-text-field justify-content">
                <h4>Payment Status </h4> <span class="text-bolder pl-2"> {{ getPaymentStatus }} </span>
            </div>
        </v-col>

        <v-col cols="6">
                <h3 class="pb-2">Estimated Billing</h3>
                <p class="mt-4">Please input values from 1-900 in fields below.</p>

                <div class="crm-text-field">
                    <div class="pr-3 title-text">
                        <h4>Quarterly Cost (Power) <span>*</span></h4>
                    </div>
                    <div class="">
                        <ValidationProvider
                            name="Power cost"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <v-text-field
                                v-model="powerCost"
                                @blur="savePaymentInfo('estimated_elec_billing_cost', powerCost)"
                                outlined
                                dense
                                hide-details="auto"
                                placeholder="Power Cost"
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
                        <h4>Quarterly Cost (Gas) <span>*</span></h4>
                    </div>
                    <div class="">
                        <ValidationProvider
                            name="Gas cost"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <v-text-field
                                v-model="gasCost"
                                @blur="savePaymentInfo('estimated_gas_billing_cost', gasCost)"
                                outlined
                                dense
                                hide-details="auto"
                                placeholder="Gas Cost"
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
import DayJs from "dayjs";
import PowerShopSameDayConnectionService from "@scripts/modules/powershop/services/PowerShopSameDayConnectionService";

export default {
    name: "PaymentDetails",
    components: {},
    props: {
        lead: {
            require: true,
        },
        selectedProvider: {
            require: true,
        },
        submitType: {
            require: true
        },
    },
    data() {
        return {
            isEnable: false,
            isLoading: false,
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
            gasCost: null,
            sameDayConnectionData: null
        }
    },
    computed: {
        getPaymentStatus() {
            return this.paymentStatus
                ? powerShopPaymentStatusNumberToName[this.paymentStatus]
                : powerShopPaymentStatusNumberToName[this.lead?.powershop_payment_info?.status];
        },
    },
    watch: {
        paymentStatus() {
            this.$emit('paymentStatus', this.paymentStatus);
        },
    },
    async mounted() {
        await this.synFormData();
    },
    methods: {
        async savePaymentInfo(field, value) {
            await PowershopService.updatePaymentInformation(field, value, this.lead.id);
        },
        synFormData() {
            this.powerCost = this.lead?.powershop_payment_info?.estimated_elec_billing_cost;
            this.gasCost = this.lead?.powershop_payment_info?.estimated_gas_billing_cost;
        },
        disabledPaymentButton() {
            return this.lead?.powershop_payment_info?.status === 2;
        },
        async sendPaymentLink(linkType) {
            const response = await LeadApplicationService.sendPowershopPaymentLink(this.lead.id, linkType);
            this.paymentStatus = response.data?.status;
        },

        async checkSameDayValidation() {
            if (this.selectedProvider === 'powershop') {
                this.isEnable = false;
                this.isLoading = true;
                this.sameDayConnectionData = (await PowerShopSameDayConnectionService.validateSameDayConnection(this.lead.id, this.submitType)).data;
                console.log('Same day connection response : ', this.sameDayConnectionData);
                //this.isEnable = true;
                this.isLoading = false;
                this.isEnable = this.sameDayConnectionData?.electricityOk && this.sameDayConnectionData?.gasOk ?
                     true : false;
            }
        }

    },

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

.justify-content {
    justify-content: space-between !important;
}

</style>
