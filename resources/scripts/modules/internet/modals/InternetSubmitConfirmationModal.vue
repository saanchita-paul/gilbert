<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="900px"
        >
            <v-card>
                <v-toolbar dark color="primary">
                    <v-toolbar-title>Confirm NBN Submission</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <div class="d-flex justify-space-between">
                        <div class="flex-basis-58">
                            <v-row>
                                <v-col cols="12">
                                    <h2 class="primary--text">
                                        {{ this.leadSummary.applicant_name }}
                                    </h2>
                                </v-col>

                                <v-col cols="12">
                                    <v-divider></v-divider>
                                </v-col>

                                <v-col cols="12">
                                    <div class="crm-text-field">
                                        <div class="field-label font-size-16">
                                            <span>Personal Details</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>DOB</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ date_of_birth }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Mobile</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.phone }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Email</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.email }}</span>
                                        </div>
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <v-divider></v-divider>
                                </v-col>

                                <v-col cols="12">
                                    <div class="crm-text-field">
                                        <div class="field-label font-size-16">
                                            <span>Connection Details</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Connection Address</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.address_text }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Billing Address</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.billing_address_text }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Shipping Address</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ internetServiceInfo.address.address_text }}</span>
                                        </div>
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <v-divider></v-divider>
                                </v-col>

                                <v-col cols="12">
                                    <div class="crm-text-field">
                                        <div class="field-label font-size-16">
                                            <span>Additional Information</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Modem Type</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ modemMapper[internetServiceInfo.modem_type] }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Charity</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ charityMapper[internetServiceInfo.charity] }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>CIS Delivery</span>
                                        </div>
                                        <div class="text-field">
                                            <span>Shipping Address</span>
                                        </div>
                                    </div>

                                    <!--                                    <div class="crm-text-field">
                                                                            <div class="field-label">
                                                                                <span>Payment Status</span>
                                                                            </div>
                                                                            <div class="text-field">
                                                                                <span>Valid</span>
                                                                            </div>
                                                                        </div>-->
                                </v-col>
                            </v-row>
                        </div>
                        <div>
                            <v-divider vertical></v-divider>
                        </div>
                        <div class="flex-basis-40">
                            <v-row>
                                <v-col cols="12">
                                    <h4>NBN Plan Selected</h4>
                                    <div class="internet-plan">

                                        <div class="plan-title-header">
                                            <div class="d-flex align-center">
                                                <v-img
                                                    class="mr-2"
                                                    max-width="30"
                                                    :src="activePlan.logo"
                                                ></v-img>
                                                <h3>{{ activePlan.provider }}</h3>
                                            </div>
                                        </div>

                                        <div class="pa-4">
                                            <p class="mb-0 text-internet">{{ activePlan.display_name }}</p>
                                            <p class="text-internet mb-3">{{ activePlan.mbps }}</p>
                                            <p class="black--text font-weight-bold mb-0">
                                                {{ '$' + activePlan.price + '/month' }}
                                            </p>
                                        </div>

                                        <div class="view-plan">
                                            <v-btn
                                                block
                                                color="#85639A"
                                                class="font-weight-bold white--text"
                                                style="border-radius: 16px !important;"
                                            >
                                                Plan Selected!
                                            </v-btn>
                                        </div>
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Homephone</span>
                                        </div>
                                        <div class="text-field d-flex justify-end">
                                            <v-switch inset class="mt-0 p-0"
                                                      v-model="internetServiceInfo.is_need_home_phone"
                                                      @change="isNeedPhonePlanHandler">
                                            </v-switch>
                                        </div>
                                    </div>

                                    <div class="crm-text-field" v-if="internetServiceInfo.is_need_home_phone && internetServiceInfo.home_phone_plan">
                                        <div class="field-label">
                                            <span>Selected Phone Plan</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ internetServiceInfo.home_phone_plan.toUpperCase() }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field" v-if="internetServiceInfo.is_need_home_phone && internetServiceInfo.home_phone_number">
                                        <div class="field-label">
                                            <span>Homephone #</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ internetServiceInfo.home_phone_number }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field" v-if="internetServiceInfo.is_need_home_phone && internetServiceInfo.current_provider">
                                        <div class="field-label">
                                            <span>Current Provider</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ internetServiceInfo.current_provider }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Account Number</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.applicant_name }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Back to base?</span>
                                        </div>
                                        <div class="text-field d-flex justify-end">
                                            <v-switch inset class="mt-0 p-0"
                                                      v-model="internetServiceInfo.is_back_to_base"
                                                      @change="updateInternetServiceInfo">
                                            </v-switch>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Medical or Security Alarm?</span>
                                        </div>
                                        <div class="text-field d-flex justify-end">
                                            <v-switch inset class="mt-0 p-0"
                                                      v-model="internetServiceInfo.is_security_alarm"
                                                      @change="updateInternetServiceInfo">
                                            </v-switch>
                                        </div>
                                    </div>
                                </v-col>
                            </v-row>
                        </div>
                    </div>
                    <v-row>
                        <v-col cols="12">
                            <v-divider></v-divider>
                        </v-col>
                    </v-row>
                </v-card-text>

                <v-card-actions>
                    <v-col cols="6">
                        <v-btn
                            large
                            block
                            elevation="0"
                            @click="backToEdit"
                            style="border-radius: 16px !important;"
                        >
                            Back to Edit
                        </v-btn>
                    </v-col>
                    <v-col cols="6">
                        <v-btn
                            large
                            block
                            elevation="5"
                            color="#542E89"
                            class="white--text"
                            @click="confirmSubmit"
                            style="border-radius: 16px !important;"
                        >
                            Confirm & Submit
                        </v-btn>
                    </v-col>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import DayJs from "dayjs";
import ApplicationSummary from "@scripts/models/crm/ApplicationSummary";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import InternetService from "@scripts/modules/internet/services/InternetService";
import InternetServiceInfo from "@scripts/models/crm/InternetServiceInfo";
import InternetServiceConstant from "@scripts/modules/internet/constants/InternetServiceConstant";

export default {
    name: "InternetSubmitConfirmationModal",
    props: {
        dialog: {
            required: true
        },
        activePlan: {
            required: true
        }
    },
    data() {
        return {
            leadSummary: new ApplicationSummary(),
            modemMapper: InternetServiceConstant.MODEM_TYPE_MAP,
            charityMapper: InternetServiceConstant.CHARITY_MAP,
        };
    },
    computed: {
        date_of_birth() {
            return DayJs(this.leadSummary.date_of_birth).format("DD/MM/YYYY");
        },
        loadApplicationSummary() {
            return LeadApplicationService.loadApplicationSummary();
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
    async mounted() {
        this.leadSummary = await this.loadApplicationSummary;
        this.internetServiceInfo = await this.internetServiceInfo;
    },
    methods: {
        backToEdit() {
            this.$emit('backToEdit');
        },
        confirmSubmit() {
            this.$eventBus.$emit("nbn_submitted");
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
            }
            this.updateInternetServiceInfo();
        },
    }
}
</script>

<style scoped>
.flex-basis-58 {
    flex-basis: 58%;
}

.flex-basis-40 {
    flex-basis: 40%;
}

.field-label {
    text-align: left !important;
}

.crm-text-field {
    margin-bottom: 5px !important;
}

.internet-plan {
    border: 2px solid #85639A;
    text-align: left;
    border-radius: 30px;
    width: 50%;
    box-shadow: 0 6px 24px 0 #00000052 !important;
}

.plan-title-header {
    background-color: #85639A;
    color: white;
    padding: 10px;
    border-radius: 26px 26px 0 0;
}

.view-plan {
    padding: 0 10px;
    margin-bottom: 10px
}

.text-internet {
    color: #85639A;
    font-weight: 700;
}

.font-size-16 {
    font-size: 16px;
}

.v-card__text {
    color: #000000DE !important;
}
</style>
