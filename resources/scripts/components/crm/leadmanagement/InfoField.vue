<template>
    <v-row>
        <v-col cols="4">
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
        
        <v-col cols="4">
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

        <v-col cols="4">
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
            <p class="sub-title mt-5">Agent’s Additional Instructions</p>
            <v-textarea
                outlined
                hide-details="auto"
                placeholder="Additional Instructions goes here."
            ></v-textarea>
        </v-col>
    </v-row>
</template>

<script>

export default {
  name: "InfoField",
};
</script>

<style scoped>
</style>
