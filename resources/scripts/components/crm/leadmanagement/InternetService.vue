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
                    @reviewPlan="reviewPlan"
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
        </v-col>

        <v-col cols="12">
            <div class="d-flex justify-space-between">
                <div class="flex-basis-50">
                    <v-row>
                        <v-col cols="12">
                            <p class="mt-4 sub-title">Home Phone</p>
                            <div class="crm-text-field">
                                <div class="field-label flex-basis-80">
                                    <span>Customer need home phone service?*</span>
                                </div>
                                <div class="text-field flex-basis-20 d-flex justify-end">
                                    <v-switch v-model="internetServiceInfo.is_need_home_phone"
                                              inset
                                              class="mt-0"
                                              @change="updateInternetServiceInfo"
                                    ></v-switch>
                                </div>
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
                                <v-checkbox v-model="internetServiceInfo.is_existing_landline"
                                            @change="updateInternetServiceInfo"
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
                                            v-model="internetServiceInfo.home_phone_number"
                                            @blur="updateInternetServiceInfo"
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
                                            v-model="internetServiceInfo.current_provider"
                                            @blur="updateInternetServiceInfo"
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
                                            v-model="internetServiceInfo.account_number"
                                            @blur="updateInternetServiceInfo"
                                        ></v-text-field>
                                    </div>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
                <div>
                    <v-divider vertical></v-divider>
                </div>
                <div class="flex-basis-50">
                    <v-row>
                        <v-col cols="12">
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
                                            v-model="internetServiceInfo.otp"
                                            @blur="updateInternetServiceInfo"
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
                                            :items="modemTypesItems"
                                            placeholder="Choose Modem Type"
                                            v-model="internetServiceInfo.modem_type"
                                            @blur="updateInternetServiceInfo"
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
                                            :items="charityItems"
                                            placeholder="Please select"
                                            v-model="internetServiceInfo.charity"
                                            @blur="updateInternetServiceInfo"
                                        >
                                        </v-select>
                                    </div>
                                </div>

                                <div class="crm-text-field">
                                    <div class="field-label flex-basis-80">
                                        <span>Back to base?</span>
                                    </div>
                                    <div class="text-field flex-basis-20 d-flex justify-end">
                                        <v-switch
                                            inset
                                            class="mt-0"
                                            v-model="internetServiceInfo.is_back_to_base"
                                            @change="updateInternetServiceInfo"
                                        ></v-switch>
                                    </div>
                                </div>

                                <div class="crm-text-field">
                                    <div class="field-label flex-basis-80">
                                        <span>Medical or Security Alarm?</span>
                                    </div>
                                    <div class="text-field flex-basis-20 d-flex justify-end">
                                        <v-switch
                                            inset
                                            class="mt-0"
                                            v-model="internetServiceInfo.is_security_alarm"
                                            @change="updateInternetServiceInfo"
                                        ></v-switch>
                                    </div>
                                </div>
                            </div>
                        </v-col>

                        <v-col cols="12">
                            <v-divider></v-divider>
                        </v-col>

                        <v-col cols="12">
                            <div class="crm-text-field">
                                <div class="field-label">
                                    <span>Payment Details</span>
                                </div>
                            </div>

                            <div class="crm-text-field">
                                <div class="field-label">
                                    <span>Payment Status</span>
                                </div>
                                <div class="text-field">
                                    <span>Pending/Valid/Invalid</span>
                                </div>
                            </div>

                            <div class="crm-text-field">
                                <div class="field-label">
                                    <span>Payment Code</span>
                                </div>
                                <div class="text-field">
                                    <span>0000</span>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </div>
            </div>
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

        <v-dialog v-model="viewPlanDetails" max-width="650">
            <InternetPlanDetails/>
        </v-dialog>

        <InternetSubmitConfirmationModal
            v-if="showInternetSubmitModal"
            :dialog="showInternetSubmitModal"
            @confirmSubmit="confirmSubmit"
            @backToEdit="backToEdit"
        ></InternetSubmitConfirmationModal>
    </v-card>
</template>

<script>
import InternetServiceProvider from "@scripts/modules/internet/components/InternetServiceProvider";
import InternetPlan from "@scripts/modules/internet/components/InternetPlan";
import InternetPlanDetails from "@scripts/modules/internet/components/InternetPlanDetails";
import InternetSubmitConfirmationModal from "@scripts/modules/internet/modals/InternetSubmitConfirmationModal";
import InternetService from "@scripts/modules/internet/services/InternetService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";

export default {
    name: "InternetService.",
    components: {
        InternetPlan,
        InternetServiceProvider,
        InternetPlanDetails,
        InternetSubmitConfirmationModal
    },
    props: {
        leadSummary: {
            require: true
        }
    },
    data() {
        return {
            viewPlanDetails: false,
            plans: null,
            showInternetSubmitModal: false,
            modemTypesItems: InternetService.getModemTypes(),
            charityItems: InternetService.getCharityItems(),
        }
    },
    computed: {
        providers() {
            return InternetService.getProviderAndPlan();
        },
        isDisable() {
            return false;
        },
        selectedProvider: {
            get() {
                return InternetService.getInternetProvider();
            },
            set(value) {
                InternetService.setInternetProvider(value);
            }
        },
        selectedPlan: {
            get() {
                return InternetService.getInternetPlan();
            },
            set(value) {
                InternetService.setInternetPlan(value);
            }
        },
        internetServiceInfo: {
            get() {
                return InternetService.loadInternetServiceInfo();
            },
            async set(value) {
                return await InternetService.updateInternetServiceInfo(value, this.leadSummary.id);
            }
        },
    },
    mounted() {
    },
    methods: {
        reviewPlan() {
            this.viewPlanDetails = !this.viewPlanDetails;
        },
        onSelectProvider(provider) {
            this.selectedProvider = provider;

            const selectedProvider = this.providers.find(dt => {
                return dt.name === this.selectedProvider;
            })

            this.plans = selectedProvider.plans;
        },
        selectPlan(plan) {
            this.selectedPlan = plan.name;
        },
        submit() {
            this.showInternetSubmitModal = true;
        },
        backToEdit() {
            this.showInternetSubmitModal = false;
        },
        confirmSubmit() {
            this.showInternetSubmitModal = false;
        },
        updateInternetServiceInfo() {
            ({internetServiceInfo: this.internetServiceInfo} = this);
        }
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

.flex-basis-50 {
    flex-basis: 48%;
}

.flex-basis-80 {
    flex-basis: 80% !important;
}

.flex-basis-20 {
    flex-basis: 20% !important;
}
</style>
