<template>
    <v-card>
        <v-col cols="12">
            <p class="mb-0 sub-title">Our Available NBN Suppliers and their plans</p>
            <div class="d-flex align-content-lg-space-around mt-2">
                <div class="d-flex align-center">Supplier:</div>
                <InternetServiceProvider
                    @onSelectProvider="onSelectProvider(provider.name)"
                    :selectedProvider="selectedProvider"
                    v-for="provider in providers"
                    :key="provider.name"
                    :provider="provider">
                </InternetServiceProvider>
            </div>
        </v-col>

        <v-col cols="12">
            <div class="d-flex w-100 overflow-auto">
                <InternetPlan
                    @view="view"
                    :isActive="selectedPlan"
                    v-for="(plan, index) in plans"
                    :key="index"
                    :plan="plan"
                    @click.native="selectPlan(plan)">
                </InternetPlan>
            </div>
        </v-col>

        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>


        <v-col cols="12">
            <p class="mb-0 sub-title">You have chosen Goodtel NBN!</p>
            <v-row>
                <v-col cols="6">
                    <p class="mt-4 sub-title">Home Phone</p>
                    <div class="d-flex justify-content-between">
                        <p class="sub-title">Customer need home phone service?*</p>
                        <v-switch
                            inset
                            class="mt-0"
                        ></v-switch>
                    </div>

                    <div>
                        <p class="mb-0">Home Phone Plans</p>
                        <div class="home-plan">
                            <div class="pa-2">
                                <p class="mb-0 text-internet">Phone Calls</p>
                                <p class="black--text font-weight-bold">$10/month</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <v-checkbox
                            :label="`Do you have an existing landline phone number you'd like to bring to your new service? *`">
                        </v-checkbox>
                    </div>

                    <div>
                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>Homephone no.*</span>
                            </div>
                            <div class="text-field">
                                <v-text-field
                                    outlined
                                    dense
                                    hide-details="auto"
                                    placeholder="Home Phone No"
                                ></v-text-field>
                            </div>
                        </div>

                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>Current Provider*</span>
                            </div>
                            <div class="text-field">
                                <v-text-field
                                    outlined
                                    dense
                                    hide-details="auto"
                                    placeholder="Current Provider"
                                ></v-text-field>
                            </div>
                        </div>

                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>Account Number*</span>
                            </div>
                            <div class="text-field">
                                <v-text-field
                                    outlined
                                    dense
                                    hide-details="auto"
                                    placeholder="Account Number"
                                ></v-text-field>
                            </div>
                        </div>
                    </div>
                </v-col>

                <!--            <v-divider vertical></v-divider>-->

                <v-col cols="6">
                    <p class="mt-4 sub-title">Few additional questions for our customer</p>

                    <div>
                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>Setup OTP</span>
                            </div>
                            <div class="text-field">
                                <v-text-field
                                    outlined
                                    dense
                                    hide-details="auto"
                                    placeholder="One Time Password"
                                ></v-text-field>
                            </div>
                        </div>

                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>Modem Type</span>
                            </div>
                            <div class="text-field">
                                <v-select
                                    outlined
                                    dense
                                    hide-details="auto"
                                    :items="statesDD"
                                    placeholder="Choose Modem Type"
                                >
                                </v-select>
                            </div>
                        </div>

                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>Charity</span>
                            </div>
                            <div class="text-field">
                                <v-select
                                    outlined
                                    dense
                                    hide-details="auto"
                                    :items="statesDD"
                                    placeholder="Please select"
                                >
                                </v-select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <p class="sub-title">Back to base?</p>
                            <v-switch
                                inset
                                class="mt-0"
                            ></v-switch>
                        </div>

                        <div class="d-flex justify-content-between">
                            <p class="sub-title">Medical or Security Alarm?</p>
                            <v-switch
                                inset
                                class="mt-0"
                            ></v-switch>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-col>

        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>

        <v-col cols="12">
            <div class="d-flex justify-end py-4 px-4" style="width: 100%; background-color: white;">
                <v-btn
                    :disabled="isDisable"
                    color="#542E89"
                    @click="submit"
                    class="white--text"
                >
                    Submit for NBN
                </v-btn>
            </div>
        </v-col>

        <v-dialog v-model="viewPlanDetails" max-width="450">
            <v-card>
                <InternetPlanDetails
                />
            </v-card>
        </v-dialog>

        <InternetSubmitConfirmationModal
            v-if="showInternetSubmitModal"
            :dialog="showInternetSubmitModal"
        ></InternetSubmitConfirmationModal>
    </v-card>
</template>

<script>
import InternetServiceProvider from "@scripts/components/crm/leadmanagement/InternetServiceProvider";
import InternetPlan from "@scripts/components/crm/leadmanagement/InternetPlan";
import InternetProviders from "@scripts/data/InternetProviders";
import InternetPlanDetails from "@scripts/components/internet/goodtel/InternetPlanDetails";
import InternetSubmitConfirmationModal from "@scripts/components/crm/modals/InternetSubmitConfirmationModal";

export default {
    name: "InternetService.",
    components: {InternetPlan, InternetServiceProvider, InternetPlanDetails, InternetSubmitConfirmationModal},
    props: {
        leadSummary: {
            require: true
        }
    },
    data() {
        return {
            viewPlanDetails: false,
            plans: null,
            selectedProvider: '',
            selectedPlan: null,
            statesDD: [
                {text: "NSW", value: "New South Wales"},
                {text: "VIC", value: "Victoria"},
                {text: "QLD", value: "Queensland"},
                {text: "SA", value: "South Australia"},
                {text: "NT", value: "Northern Territory"},
                {text: "TAS", value: "Tasmania"},
                {text: "ACT", value: "Australian Capital Territory"},
                {text: "WA", value: "Western Australia"}
            ],
            showInternetSubmitModal: false
        }
    },
    computed: {
        providers() {
            return InternetProviders.map(provider => provider) || [];
        },
        isDisable() {
            return false;
        },
    },
    mounted() {
    },
    methods: {
        view() {
            this.viewPlanDetails = !this.viewPlanDetails;
        },
        onSelectProvider(providerId) {
            this.selectedProvider = providerId;

            const selectedProvider = this.providers.find(dt => {
                return dt.name === this.selectedProvider;
            })

            this.plans = selectedProvider.plans;
            // this.selectedPlan = selectedProvider.default_plan;
        },
        selectPlan(plan) {
            this.selectedPlan = plan.name;
        },
        submit() {
            this.showInternetSubmitModal = true;
        },
    }
}
</script>

<style scoped>
.justify-content-between {
    justify-content: space-between;
}

.home-plan {
    display: flex;
    justify-content: center;
    width: 25%;
    border: 2px solid #85639A;
    border-radius: 20px;
    cursor: pointer;
}

.text-internet {
    color: #85639A;
    font-weight: 700;
}

.mt-0 {
    margin-top: 0 !important;
}

.field-label {
    text-align: left !important;
}
</style>
