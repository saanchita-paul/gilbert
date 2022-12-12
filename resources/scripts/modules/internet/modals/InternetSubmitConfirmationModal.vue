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
                                        <div class="field-label">
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

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Email Billing</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.email_billing }}</span>
                                        </div>
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <v-divider></v-divider>
                                </v-col>

                                <v-col cols="12">
                                    <div class="crm-text-field">
                                        <div class="field-label">
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
                                            <span>{{ leadSummary.internet_service_info.address.address_text }}</span>
                                        </div>
                                    </div>
                                </v-col>

                                <v-col cols="12">
                                    <v-divider></v-divider>
                                </v-col>

                                <v-col cols="12">
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Additional Information</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Modem Type</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.internet_service_info.modem_type }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Charity</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.internet_service_info.charity }}</span>
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

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Payment Status</span>
                                        </div>
                                        <div class="text-field">
                                            <span>Valid</span>
                                        </div>
                                    </div>
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
                                    <div class="internet-plan selected">

                                        <div class="plan-title-header">
                                            <div class="d-flex align-center">
                                                <v-img
                                                    class="mr-2"
                                                    max-width="30"
                                                    :src="activePlan.logo"
                                                ></v-img>
                                                <h3>{{ activePlan.title }}</h3>
                                            </div>
                                        </div>

                                        <div class="pa-4">
                                            <p class="mb-0 text-internet">{{ activePlan.name }}</p>
                                            <p class="text-internet">{{ activePlan.mbps }}</p>
                                            <p class="black--text font-weight-bold">{{ activePlan.amount }}</p>
                                        </div>

                                        <div class="view-plan">
                                            <v-btn block rounded>
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
                                                      v-model="leadSummary.internet_service_info.is_need_home_phone">
                                            </v-switch>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Selected Phone Plan</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.internet_service_info.home_phone_plan }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Homephone #</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.internet_service_info.home_phone_number }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Current Provider</span>
                                        </div>
                                        <div class="text-field">
                                            <span>{{ leadSummary.internet_service_info.current_provider }}</span>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Name on Account</span>
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
                                                      v-model="leadSummary.internet_service_info.is_back_to_base">
                                            </v-switch>
                                        </div>
                                    </div>

                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Medical or Security Alarm?</span>
                                        </div>
                                        <div class="text-field d-flex justify-end">
                                            <v-switch inset class="mt-0 p-0"
                                                      v-model="leadSummary.internet_service_info.is_security_alarm">
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
                        <v-btn large block @click="backToEdit">
                            Back to Edit
                        </v-btn>
                    </v-col>
                    <v-col cols="6">
                        <v-btn large block color="primary" @click="confirmSubmit">
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

export default {
    name: "InternetSubmitConfirmationModal",
    components: {},
    props: {
        dialog: {
            required: true
        },
        leadSummary: {
            required: true
        },
        activePlan: {
            required: true
        }
    },
    computed: {
        date_of_birth() {
            return DayJs(this.leadSummary.date_of_birth).format("DD/MM/YYYY");
        },
    },
    methods: {
        backToEdit() {
            this.$emit('backToEdit');
        },
        confirmSubmit() {
            this.$emit('confirmSubmit');
        }
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
    border: 1px solid #85639A;
    text-align: left;
    border-radius: 32px;
    width: 50%;
}

.plan-title-header {
    background-color: #85639A;
    color: white;
    padding: 20px 10px;
    border-radius: 32px 32px 0 0;
}

.selected {
    opacity: .9;
}

.view-plan {
    padding: 0 15px;
    margin-bottom: 10px
}

.text-internet {
    color: #85639A;
    font-weight: 700;
}
</style>
