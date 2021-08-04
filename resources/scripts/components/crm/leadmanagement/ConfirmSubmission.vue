<template>
        <v-container>
                <v-card>
                    <v-app-bar height="80px">
                        <spam class="py-4 dialogs-title">Confirm Submission</spam>
                    </v-app-bar>
                    <section class="pa-4 submission-section">
                        <v-row>
                            <v-col cols="6">
                                    <p class="sub-title title-align">Personal Details</p>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Title</span>
                                        </div>
                                        <div class="text-field">
                                        <v-select outlined dense hide-details="auto"  placeholder="Mr">
                                        </v-select>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Firstname</span>
                                        </div>
                                        <div class="text-field">
                                            <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                        ></v-text-field>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Lastname</span>
                                        </div>
                                        <div class="text-field">
                                            <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                        ></v-text-field>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Date of Birth</span>
                                        </div>
                                        <div class="text-field">
                                            <v-text-field
                                            outlined
                                            dense
                                            placeholder="DD/MM/YYYY"
                                            hide-details="auto"
                                            append-icon="mdi-calendar"
                                        ></v-text-field>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Mobile</span>
                                        </div>
                                        <div class="text-field">
                                            <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                        ></v-text-field>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Email</span>
                                        </div>
                                        <div class="text-field">
                                            <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                        ></v-text-field>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Email Billing</span>
                                        </div>
                                        <div class="text-field">
                                            <v-select outlined dense hide-details="auto">
                                        </v-select>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Tenancy Type</span>
                                        </div>
                                        <div class="text-field">
                                        <v-select outlined dense hide-details="auto" >
                                        </v-select>
                                        </div>
                                    </div>
                            </v-col>
        
                            <v-col cols="">
                                    <p class="sub-title title-align">Property Details</p>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Connection Date</span>
                                        </div>
                                        <div class="text-field">
                                            <v-text-field
                                                outlined
                                                dense
                                                placeholder="DD/MM/YYYY"
                                                hide-details="auto"
                                                append-icon="mdi-calendar"
                                            ></v-text-field>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Service Address</span>
                                        </div>
                                        <div class="text-field">
                                            <v-textarea
                                                outlined
                                                hide-details="auto"
                                                placeholder="This is an extra long address, 398 Bourke Road, Camberwell 3124 VIC"
                                            ></v-textarea>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Billing Address</span>
                                        </div>
                                        <div class="text-field">
                                            <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                        ></v-text-field>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Property Type</span>
                                        </div>
                                        <div class="text-field">
                                        <v-select outlined placeholder="Residentail / Business" dense hide-details="auto">
                                            </v-select>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Life Support</span>
                                        </div>
                                        <div class="text-field">
                                            <v-select outlined placeholder="Residentail / Business" dense hide-details="auto">
                                            </v-select>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>Solar Power</span>
                                        </div>
                                        <div class="text-field">
                                            <v-select outlined placeholder="Residentail / Business" dense hide-details="auto">
                                            </v-select>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>NMI (Power)</span>
                                        </div>
                                        <div class="text-field">
                                            <v-text-field
                                                outlined
                                                dense
                                                hide-details="auto"
                                            ></v-text-field>
                                        </div>
                                    </div>
                                    <div class="crm-text-field">
                                        <div class="field-label">
                                            <span>MIRN (Gas</span>
                                        </div>
                                        <div class="text-field">
                                                <v-text-field
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                ></v-text-field>
                                        </div>
                                    </div>
                            </v-col>

                           <v-col cols="12">
                                <v- divider></v->
                            </v-col>

                            <v-col cols="6">
                                <p class="sub-title title-align">Identification</p>
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Identification</span>
                                    </div>
                                    <div class="text-field">
                                        <v-select outlined dense hide-details="auto" >
                                        </v-select>
                                    </div>
                                </div>
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Card Number</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                    </div>
                                </div>
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Special Number</span>
                                    </div>
                                    <div class="text-field">
                                        <v-select outlined dense hide-details="auto" >
                                        </v-select>
                                    </div>
                                </div>
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Expiry Date</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                    </div>
                                </div>
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Card Colour</span>
                                    </div>
                                    <div class="text-field">
                                        <v-select outlined dense hide-details="auto" >
                                        </v-select>
                                    </div>
                                </div>
                            </v-col>

                            <v-col cols="6">
                                <p class="sub-title title-align">Service Connection</p>
                                <div class="d-flex mb-5">
                                        <div>
                                            <p class="mb-0">Energy</p>
                                            <div class="d-flex">
                                                <div class="service-box active">
                                                    <p class="mb-0"><v-icon color="yellow">mdi-flash</v-icon> Power</p>  
                                                </div>
                                                <div class="service-box active">
                                                    <p class="mb-0"><v-icon color="red">mdi-fire</v-icon> Gas</p> 
                                                </div>
                                        </div>
                                        </div>
                                        <div>
                                            <p class="mb-0 ml-2">Water</p>
                                            <div class="service-box">
                                                <p class="mb-0"><v-icon color="grey lighten-1">mdi-water</v-icon> water</p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="mb-0 ml-2">Internet</p>
                                            <div class="service-box">
                                                <p class="mb-0"><v-icon color="grey lighten-1">mdi-wifi</v-icon> Internet</p>
                                            </div>
                                        </div>
                                </div>
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Power Supplier</span>
                                    </div>
                                    <div class="text-field">
                                        <v-select outlined dense hide-details="auto" >
                                        </v-select>
                                    </div>
                                </div>
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Power Plan</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                    </div>
                                </div>
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Gas Supplier</span>
                                    </div>
                                    <div class="text-field">
                                        <v-select outlined dense hide-details="auto" >
                                        </v-select>
                                    </div>
                                </div>
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Gas Plan</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                    </div>
                                </div>
                            </v-col>

                            <v-col cols="12">
                                <v- divider></v->
                            </v-col>

                            <v-col cols="12">
                                <p class="sub-title">Have you read and accepted the Terms and Conditions to the Lead? TBA by Tomy</p>
                                    <v-radio-group
                                        v-model="row"
                                        row
                                        >
                                        <v-radio
                                            label="Yes"
                                            value="radio-1"
                                        ></v-radio>
                                        <v-radio
                                            label="No"
                                            value="radio-2"
                                        ></v-radio>
                                    </v-radio-group>
                            </v-col>

                            <v-col cols="12">
                                <p class="sub-title">Consent to us verifying your identity information with the document issuer or official record holder? TBA by Tomy</p>
                                    <v-radio-group
                                        v-model="row"
                                        row
                                        >
                                        <v-radio
                                            label="Yes"
                                            value="radio-1"
                                        ></v-radio>
                                        <v-radio
                                            label="No"
                                            value="radio-2"
                                        ></v-radio>
                                    </v-radio-group>
                            </v-col>

                            <v-col cols="12">
                                <p class="sub-title">Acknowledge we will perform a credit check before transferring you (we will let you know if there is a problem) TBA by Tomy</p>
                                    <v-radio-group
                                        v-model="row"
                                        row
                                        >
                                        <v-radio
                                            label="Yes"
                                            value="radio-1"
                                        ></v-radio>
                                        <v-radio
                                            label="No"
                                            value="radio-2"
                                        ></v-radio>
                                    </v-radio-group>
                            </v-col>

                            <v-col cols="12">
                                <p class="sub-title">Acknowledge we will perform a credit check before transferring you (we will let you know if there is a problem) TBA by Tomy</p>
                                    <v-radio-group
                                        v-model="row"
                                        row
                                        >
                                        <v-radio
                                            label="Yes"
                                            value="radio-1"
                                        ></v-radio>
                                        <v-radio
                                            label="No"
                                            value="radio-2"
                                        ></v-radio>
                                    </v-radio-group>
                            </v-col>

                        </v-row>
                    </section>

                    <v-footer  class="text-right">
                        <v-col class="text-right" cols="12">
                            <v-btn>Back to Edit</v-btn>
                            <v-btn>Confirm and Submit</v-btn>
                        </v-col>
                    </v-footer>
                </v-card>
        </v-container>
</template>

<script>
export default {
  name: "ConfirmSubmission",
};
</script>

<style scoped>
</style>
