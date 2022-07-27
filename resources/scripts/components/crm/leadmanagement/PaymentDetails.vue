<template>
    <v-row class="personal-details-row">
        <v-col cols="6">
            <h3>Payment</h3>
            <h4 class="py-4">Payment Details</h4>
            <div class="crm-text-field">
                <div class="pr-4">
                    <span>Payment Link: </span>
                </div>

                <div >
                    <ValidationProvider name="Payment Link" rules="required" v-slot="{ errors }">
                        <v-menu offset-y>
                            <template v-slot:activator="{ on, attrs }">
                                <v-btn v-bind="attrs" v-on="on">
                                    Send  link to customer  <span class="mdi mdi-send"></span>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item v-for="(item, index) in items" :key="index">
                                    <v-icon v-text="item.icon" class="pr-4"></v-icon> <v-list-item-title>{{ item.text }} </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </ValidationProvider>
                </div>
            </div>

            <div class="crm-text-field">
                <span>Payment Status: </span> <span class="grey--text pl-2"> Pending/Valid/Invalid </span>
            </div>
        </v-col>

        <v-col cols="6" >
            <h4 class="pb-2">Estimated Billing (Power)</h4>
            <div class="crm-text-field">
                <div class="pr-3">
                    <span>Cost: </span>
                </div>
                <div class="text-field">
                    <ValidationProvider
                        name="Cost"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <v-text-field
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Cost"
                            :error-messages="errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div>
                    <span>Period: </span>
                </div>
                <div class="text-field">
                    <ValidationProvider
                        name="Period"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <v-select
                            outlined
                            dense
                            :items="powerPeriod"
                            hide-details="auto"
                            :error-messages="errors[0]"
                            placeholder="Please choose one"
                        >
                        </v-select>
                    </ValidationProvider>
                </div>
            </div>

            <h4 class="pb-2">Estimated Billing (Gas)</h4>
            <div class="crm-text-field">
                <div class="pr-3">
                    <span>Cost: </span>
                </div>
                <div class="text-field">
                    <ValidationProvider
                        name="Cost"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <v-text-field
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Cost"
                            :error-messages="errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div>
                    <span>Period: </span>
                </div>
                <div class="text-field">
                    <ValidationProvider
                        name="Period"
                        rules="required"
                        v-slot="{ errors }"
                    >
                        <v-select
                            outlined
                            dense
                            :items="gasPeriod"
                            hide-details="auto"
                            :error-messages="errors[0]"
                            placeholder="Please choose one"
                        >
                        </v-select>
                    </ValidationProvider>
                </div>
            </div>
        </v-col>
    </v-row>
</template>

<script>


export default {
    name: "PaymentDetails",
    props: {

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
            gasPeriod: [
                {
                    text: "Monthly",
                    value: "monthly",
                },
                {
                    text: "Bi-Monthly",
                    value: "bi-monthly",
                },
                {
                    text: "Quartely",
                    value: "quartely",
                },
                {
                    text: "Yearly",
                    value: "yearly",
                },
            ],
            powerPeriod: [
                {
                    text: "Monthly",
                    value: "monthly",
                },
                {
                    text: "Bi-Monthly",
                    value: "bi-monthly",
                },
                {
                    text: "Quartely",
                    value: "quartely",
                },
                {
                    text: "Yearly",
                    value: "yearly",
                },
            ],
        }

    },
    methods: {

    }

};
</script>

<style scoped>

</style>
