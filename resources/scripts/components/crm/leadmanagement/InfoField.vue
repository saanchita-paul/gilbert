<template>
    <v-row>
        <v-col cols="4">
            <p class="sub-title title-align">Personal Details</p>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Title</span>
                </div>
                <div class="text-field">
                <v-select @input="updateLeads" outlined dense hide-details="auto" :items="titlesDD" v-model="person_details.title" placeholder="Mr">
                </v-select>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Firstname</span>
                </div>
                <div class="text-field">
                    <v-text-field
                        v-model="person_details.first_name"  @input="updateLeads"
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
                        v-model="person_details.last_name"  @input="updateLeads"
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
                    <v-text-field v-model="person_details.dob"  @input="updateLeads"
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
                        v-model="person_details.mobile"  @input="updateLeads"
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
                    <v-text-field v-model="person_details.email"  @input="updateLeads"
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
                    <v-select v-model="person_details.email_billing" :items="emailBillingDD"  @input="updateLeads" outlined dense hide-details="auto">
                </v-select>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Tenancy Type</span>
                </div>
                <div class="text-field">
                <v-select v-model="person_details.tenancy_type" :items="tenantTypeDD"  @input="updateLeads" outlined dense hide-details="auto" >
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
                        @input="updateLeads"
                        v-model="property_details.connection_date"
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
                        @input="updateLeads"
                        v-model="property_details.service_address"
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
                    <v-text-field v-model="property_details.billing_address" @input="updateLeads"
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
                   <v-select @input="updateLeads" v-model="property_details.property_type" :items="propertyTypeDD" outlined placeholder="Residentail / Business" dense hide-details="auto">
                    </v-select>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Life Support</span>
                </div>
                <div class="text-field">
                    <v-select @input="updateLeads" v-model="property_details.life_support" :items="lifeSupportDD" outlined placeholder="Residentail / Business" dense hide-details="auto">
                    </v-select>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Solar Power</span>
                </div>
                <div class="text-field">
                    <v-select @input="updateLeads" v-model="property_details.solor_power" :items="solarPowerDD" outlined placeholder="Residentail / Business" dense hide-details="auto">
                    </v-select>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>NMI (Power)</span>
                </div>
                <div class="text-field">
                    <v-text-field @input="updateLeads"
                        v-model="property_details.nmi"
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
                            @input="updateLeads"
                            v-model="property_details.mirn"
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
                    <v-select v-model="indentification.type" @input="updateLeads" :items="idenficationTypeDD" outlined dense hide-details="auto" >
                    </v-select>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Card Number</span>
                </div>
                <div class="text-field">
                    <v-text-field
                        v-model="indentification.number"
                        @input="updateLeads"
                        indentification
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
                    <v-select v-model="indentification.special_number" @input="updateLeads" :items="specialNumberDD" outlined dense hide-details="auto" >
                    </v-select>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Expiry Date</span>
                </div>
                <div class="text-field">
                    <v-text-field
                        v-model="indentification.expire_date"
                        @input="updateLeads"
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
                    <v-select v-model="indentification.color" @input="updateLeads" :items="colorDD" outlined dense hide-details="auto" >
                    </v-select>
                </div>
            </div>
            <p class="sub-title mt-5">Agent’s Additional Instructions</p>
            <v-textarea
                v-model="indentification.additional_instruction"
                @input="updateLeads"
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
    props: {

    },
    data () {
        return {
            titlesDD:[
                'Mrs','Mr'
            ],
            emailBillingDD: ['Yes','No'],
            tenantTypeDD: ['Renter','Owner'],
            propertyTypeDD:[
                'Recidential','Business'
            ],
            lifeSupportDD:[
                'Yes','No'
            ],
            solarPowerDD:[
                'Yes','No'
            ],
            idenficationTypeDD:[
                'Passport','Driver\'s License','Medical Card'
            ],
            specialNumberDD:[
                1,2
            ],
            colorDD:[
                'Green','Blue', 'Yellow'
            ],
            indentification: {
                type: '',
                number: '',
                special_number: '',
                expire_date: '',
                color: '',
                additional_instruction: ''
            },
            property_details: {
                connection_date: '',
                service_address: '',
                billing_address: '',
                property_type: '',
                life_support: '',
                solor_power: '',
                nmi: '',
                mirn: '',
            },
            person_details: {
                title: '',
                first_name: '',
                last_name: '',
                dob: '',
                mobile: '',
                email: '',
                email_billing: '',
                tenancy_type: '',

            }
        }
    },

    methods: {
        updateLeads()
        {
           this.$emit('updateLead',{
               identifacation: this.indentification,
               property_details: this.property_details,
               person_details: this.person_details,
           })
        }
    },

    watch: {
        indentification()
        {
            console.log('I am changed')
        },

        property_details() {
            console.log('I am changed')
        },

        person_details() {
            console.log('I am changed')
        }



    }
};
</script>

<style scoped>
</style>
