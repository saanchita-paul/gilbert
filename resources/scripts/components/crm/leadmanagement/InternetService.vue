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
                    :selectedPlan="selectedPlan"
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
                                              @change="isNeedPhonePlanHandler"
                                    ></v-switch>
                                </div>
                            </div>

                            <div v-if="internetServiceInfo.is_need_home_phone">
                                <p class="mb-0">Home Phone Plans</p>
                                <div class="home-plan" @click="selectPhonePlan()">
                                    <div class="pa-2">
                                        <p class="mb-0 mt-3 text-internet">Phone Calls</p>
                                        <p class="black--text font-weight-bold">$10/month</p>
                                    </div>
                                </div>
                            </div>

                            <div v-if="internetServiceInfo.is_need_home_phone">
                                <v-checkbox v-model="internetServiceInfo.is_existing_landline"
                                            @change="updateInternetServiceInfo"
                                            :label="`Do you have an existing landline phone number you'd like to bring to your new service? *`">
                                </v-checkbox>
                            </div>

                            <div
                                v-if="internetServiceInfo.is_need_home_phone && internetServiceInfo.is_existing_landline">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Homephone no.*</span>
                                    </div>
                                    <div class="text-field">
                                        <ValidationProvider
                                            name="Home phone number"
                                            rules="required"
                                            v-slot="{ errors }"
                                        >
                                            <v-text-field
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="Home Phone No"
                                                v-model="internetServiceInfo.home_phone_number"
                                                :error-messages="errors[0]"
                                                @blur="updateInternetServiceInfo"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </div>

                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Current Provider*</span>
                                    </div>
                                    <div class="text-field">
                                        <ValidationProvider
                                            name="Current provider"
                                            rules="required"
                                            v-slot="{ errors }"
                                        >
                                            <v-text-field
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="Current Provider"
                                                v-model="internetServiceInfo.current_provider"
                                                @blur="updateInternetServiceInfo"
                                                :error-messages="errors[0]"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </div>

                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Account Number*</span>
                                    </div>
                                    <div class="text-field">
                                        <ValidationProvider
                                            name="Account number"
                                            rules="required"
                                            v-slot="{ errors }"
                                        >
                                            <v-text-field
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="Account Number"
                                                v-model="internetServiceInfo.account_number"
                                                :error-messages="errors[0]"
                                                @blur="updateInternetServiceInfo"
                                            ></v-text-field>
                                        </ValidationProvider>
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
                                        <ValidationProvider
                                            name="OTP"
                                            rules="required"
                                            v-slot="{ errors }"
                                        >
                                            <v-text-field
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="One Time Password"
                                                v-model="internetServiceInfo.otp"
                                                @blur="updateInternetServiceInfo"
                                                :error-messages="errors[0]"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </div>

                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Modem Type</span>
                                    </div>
                                    <div class="text-field">
                                        <ValidationProvider
                                            name="Modem type"
                                            rules="required"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :items="modemTypesItems"
                                                placeholder="Choose Modem Type"
                                                v-model="internetServiceInfo.modem_type"
                                                :error-messages="errors[0]"
                                                @blur="updateInternetServiceInfo"
                                                @change="paymentLinkChangeHandler($event)"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>
                                </div>

                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Charity</span>
                                    </div>
                                    <div class="text-field">
                                        <ValidationProvider
                                            name="Charity"
                                            rules="required"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :items="charityItems"
                                                placeholder="Please select"
                                                v-model="internetServiceInfo.charity"
                                                :error-messages="errors[0]"
                                                @blur="updateInternetServiceInfo"
                                            >
                                            </v-select>
                                        </ValidationProvider>
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
                                <v-btn outlined class="outlined-btn" @click="sendGoodtelPaymentLink"
                                       :loading="paymentBtnLoading" :disabled="paymentLinkSent">
                                    Send payment link
                                    <v-icon class="ml-4">mdi-email</v-icon>
                                </v-btn>
                                <div>
                                    <transition name="fade">
                                        <small
                                            class="snackbarDesign ml-2 mt-n4"
                                            v-if="showPaymentLinkSnackbar"
                                            transition="slide-y-transition">
                                            <v-icon color="green" class="pt-0" size="15">mdi-check</v-icon>
                                            Payment link sent!
                                        </small>
                                    </transition>
                                </div>
                            </div>

                            <div class="crm-text-field">
                                <div class="field-label">
                                    <v-btn outlined class="mr-2 outlined-btn"
                                           @click="copyToClipBoardGoodtelPaymentLink">
                                        Copy Link
                                        <v-icon class="ml-4">mdi-content-copy</v-icon>
                                    </v-btn>
                                    <div>
                                        <transition name="fade">
                                            <small
                                                class="snackbarDesign mt-2"
                                                v-if="showCopyBtnSnackbar"
                                                transition="slide-x-transition">
                                                <v-icon color="green" class="pt-0" size="15">mdi-check</v-icon>
                                                Payment link copied!
                                            </small>
                                        </transition>
                                    </div>
                                </div>
                                <div class="text-field">
                                    <v-text-field ref="goodtelPaymentLink"
                                                  @focus="$event.target.select()"
                                                  outlined
                                                  dense
                                                  readonly
                                                  :value="paymentLink"
                                    ></v-text-field>
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
            :leadSummary="leadSummary"
            :activePlan="activePlan"
        ></InternetSubmitConfirmationModal>
    </v-card>
</template>

<script>
import InternetServiceProvider from "@scripts/modules/internet/components/InternetServiceProvider";
import InternetPlan from "@scripts/modules/internet/components/InternetPlan";
import InternetPlanDetails from "@scripts/modules/internet/components/InternetPlanDetails";
import InternetSubmitConfirmationModal from "@scripts/modules/internet/modals/InternetSubmitConfirmationModal";
import InternetService from "@scripts/modules/internet/services/InternetService";
import ApplicationSummary from "@scripts/models/crm/ApplicationSummary";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";

export default {
    name: "InternetService",
    components: {
        InternetPlan,
        InternetServiceProvider,
        InternetPlanDetails,
        InternetSubmitConfirmationModal
    },
    data() {
        return {
            leadSummary: new ApplicationSummary(),
            viewPlanDetails: false,
            plans: null,
            showInternetSubmitModal: false,
            modemTypesItems: InternetService.getModemTypes(),
            charityItems: InternetService.getCharityItems(),
            activePlan: {},
            showPaymentLinkSnackbar: false,
            showCopyBtnSnackbar: false,
            paymentLink: null,
            paymentBtnLoading: false,
            paymentLinkSent: false
        }
    },
    computed: {
        providers() {
            return InternetService.getProviderAndPlan();
        },
        isDisable() {
            return (
                !LeadApplicationService.canSubmitInternet('internet') ||
                !this.selectedProvider ||
                !this.selectedPlan
            );
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
        loadApplicationSummary() {
            return LeadApplicationService.loadApplicationSummary();
        }
    },
    async mounted() {
        this.leadSummary = await this.loadApplicationSummary;
        await InternetService.loadProviderData(this.leadSummary.id);
        await this.onSelectProvider(this.selectedProvider ?? null);
        this.setActivePlan(this.selectedPlan ?? null);
        this.$eventBus.$on("nbn_submitted", async () => {
            console.log("nbn_submitted");
            await this.confirmSubmit();
            this.$eventBus.$off("nbn_submitted");
        });
    },
    methods: {
        reviewPlan() {
            this.viewPlanDetails = !this.viewPlanDetails;
        },
        async onSelectProvider(provider) {
            this.selectedProvider = provider;

            const selectedProvider = this.providers.find(dt => {
                return dt.name === this.selectedProvider;
            })


            if (selectedProvider && selectedProvider.name === 'goodtel') {
                await this.getGoodtelPlans();
            }
        },
        async getGoodtelPlans() {
            this.plans = await InternetService.getGoodtelPlans();
            this.initPaymentLink(this.internetServiceInfo.modem_type);
        },
        initPaymentLink(modem_type) {
            if (this.selectedPlan && this.plans.length) {
                const plan = this.plans.find(dt => {
                    return dt.name === this.selectedPlan;
                });
                console.log('initPaymentLink', plan);
                if (modem_type === 'standard' || modem_type === 'upgraded') {
                    console.log('p1', modem_type)
                    this.paymentLink = plan.payment_links.find(dt => {
                        return dt.modem_type === modem_type;
                    }).payment_link;
                } else {
                    console.log('p2', modem_type)
                    this.paymentLink = plan.payment_links.find(dt => {
                        return dt.modem_type === 'none';
                    }).payment_link;
                }
            }
        },
        paymentLinkChangeHandler(modem_type) {
            this.initPaymentLink(modem_type);
        },
        selectPlan(plan) {
            this.selectedPlan = plan.name;
            const formData = {
                provider_name: this.selectedProvider,
                plan_type: this.selectedPlan,
                service_type: 'internet'
            };
            InternetService.updateNbnProvider(formData, this.leadSummary.id);
        },
        setActivePlan(plan) {
            if (this.plans && this.plans.length > 0) {
                this.activePlan = this.plans.find(dt => {
                    return dt.name === plan;
                });
            }
        },
        selectPhonePlan(plan = 'standard') {
            this.internetServiceInfo.home_phone_provider = this.selectedProvider;
            this.internetServiceInfo.home_phone_plan = plan;
            this.updateInternetServiceInfo();
        },
        submit() {
            let v = this.$eventBus.$emit('nbn_submit_validate');
            if (!v) {
                return;
            }
            this.showInternetSubmitModal = true;
        },
        backToEdit() {
            this.showInternetSubmitModal = false;
        },
        async confirmSubmit() {
            await InternetService.submitNBN({service_type: 'internet'}, this.leadSummary.id);
            this.showInternetSubmitModal = false;
        },
        updateInternetServiceInfo() {
            ({internetServiceInfo: this.internetServiceInfo} = this);
        },
        isNeedPhonePlanHandler() {
            if (!this.internetServiceInfo.is_need_home_phone) {
                this.internetServiceInfo.home_phone_provider = null;
                this.internetServiceInfo.home_phone_plan = null;
                this.internetServiceInfo.is_existing_landline = false;
                this.internetServiceInfo.home_phone_number = null;
                this.internetServiceInfo.current_provider = null;
                this.internetServiceInfo.account_number = null;
                this.updateInternetServiceInfo();
            } else {
                this.selectPhonePlan();
            }
        },
        async sendGoodtelPaymentLink() {
            this.paymentBtnLoading = true;
            const res = await InternetService.sendGoodtelPaymentLink(this.leadSummary.id);
            if (res.data.success) {
                this.paymentLinkSent = true;
                this.paymentBtnLoading = false;
                this.showPaymentLinkSnackbar = true;
                setTimeout(() => {
                    this.showPaymentLinkSnackbar = false;
                }, 2000);
            }
        },
        copyToClipBoardGoodtelPaymentLink() {
            this.$refs.goodtelPaymentLink.focus();
            document.execCommand('copy');
            this.showCopyBtnSnackbar = true;
            setTimeout(() => {
                this.showCopyBtnSnackbar = false;
            }, 2000);
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
    width: 40%;
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

.outlined-btn:hover {
    border: 2px solid #542E89;
    color: #ffffff;
    background-color: #542E89;
}
</style>

<style lang="scss" scoped>
.snackbarDesign {
    color: green;
    font-weight: normal;
    font-size: 14px;
    padding: 5px 10px;
    position: absolute;
    background: white;
    border-radius: 6px;
    box-shadow: 0px 9px 24px 6px rgba(0, 0, 0, 0.22);
    -webkit-box-shadow: 0px 9px 24px 6px rgba(0, 0, 0, 0.22);
    -moz-box-shadow: 0px 9px 24px 6px rgba(0, 0, 0, 0.22);

    &:hover {
        cursor: pointer;
    }
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
    opacity: 0;
}
</style>
