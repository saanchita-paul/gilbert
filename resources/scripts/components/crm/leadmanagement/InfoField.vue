<template>
    <v-row>
        <v-col cols="4">
            <p class="sub-title title-align">Personal Details</p>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Title</span>
                </div>

                <div class="text-field">
                    <ValidationProvider name="Title" rules="required"  v-slot="{ errors }">
                        <v-select
                            @input="updateLeads"
                            outlined dense hide-details="auto"
                            :items="titlesDD"
                            v-model="person_details.title"
                            :error-messages=" errors[0]"
                            placeholder="Mr">
                        </v-select>
                    </ValidationProvider>

                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Firstname</span>
                </div>
                <div class="text-field">
                        <ValidationProvider name="First Name" rules="required"  v-slot="{ errors }">
                        <v-text-field
                            v-model="person_details.first_name"  @input="updateLeads"
                            outlined
                            dense
                            hide-details="auto"
                            placeholder="Firstname"
                            :error-messages=" errors[0]"
                    ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Lastname</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Lastname" rules="required"  v-slot="{ errors }">
                        <v-text-field
                            v-model="person_details.last_name"  @input="updateLeads"
                        outlined
                        dense :error-messages=" errors[0]"
                        hide-details="auto"
                    ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Date of Birth</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Date of Birth" rules="required"  v-slot="{ errors }">
<!--                        <v-text-field v-model="person_details.dob"  @input="updateLeads"-->
<!--                        outlined-->
<!--                        dense :error-messages=" errors[0]"-->
<!--                        placeholder="DD/MM/YYYY"-->
<!--                        hide-details="auto"-->
<!--                        append-icon="mdi-calendar"-->
<!--                        ></v-text-field>-->

                        <v-menu
                            v-model="showDateOfBirth"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <ValidationProvider name="Bate Of Birth" rules="required"  v-slot="{ errors }">
                                    <v-text-field
                                        label="Date of Birth*"
                                        placeholder="DD/MM/YYYY"
                                        outlined
                                        dense
                                        append-icon="mdi-calendar"
                                        v-model="person_details.dob"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        :error-messages=" errors[0]"
                                        hide-details="auto"
                                    ></v-text-field>
                                </ValidationProvider>
                            </template>
                            <v-date-picker v-model="person_details.dob" @input="showDateOfBirth = false"></v-date-picker>
                        </v-menu>

                    </ValidationProvider>

                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Mobile</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required|cv-phone|length:10"  v-slot="{ errors }">
                        <v-text-field
                            v-model="person_details.mobile"  @input="updateLeads"
                        outlined
                        dense
                            :error-messages=" errors[0]"
                        hide-details="auto"
                    ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Email</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Email" rules="required|email"  v-slot="{ errors }">
                            <v-text-field v-model="person_details.email"  @input="updateLeads"
                            outlined
                            dense
                            hide-details="auto" :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Email Billing</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Email Billing" rules="required"  v-slot="{ errors }">
                        <v-select v-model="person_details.email_billing" :items="emailBillingDD" item-text="text" item-value="value"  @input="updateLeads" :error-messages=" errors[0]" outlined dense hide-details="auto">
                    </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Tenancy Type</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Tenant Type" rules="required"  v-slot="{ errors }">
                        <v-select v-model="person_details.tenancy_type" :items="tenantTypeDD" item-text="text" item-value="value" :error-messages=" errors[0]"
                                  @input="updateLeads" outlined dense hide-details="auto" >
                        </v-select>
                    </ValidationProvider>
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
                    <ValidationProvider name="Connection Date" rules="required"  v-slot="{ errors }">
                        <v-menu
                            v-model="connection_date"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <ValidationProvider name="Bate Of Birth" rules="required"  v-slot="{ errors }">
                                    <v-text-field
                                        label="Connection Date*"
                                        placeholder="DD/MM/YYYY"
                                        outlined
                                        dense
                                        append-icon="mdi-calendar"
                                        v-model="property_details.connection_date"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        :error-messages=" errors[0]"
                                        hide-details="auto"
                                        @input="updateLeads"
                                    ></v-text-field>
                                </ValidationProvider>
                            </template>
                            <v-date-picker v-model="property_details.connection_date" @input="connection_date = false"></v-date-picker>
                        </v-menu>

                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Service Address</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                        <v-textarea
                            @input="updateLeads"
                            v-model="property_details.service_address"
                            outlined
                            hide-details="auto"
                            placeholder="This is an extra long address, 398 Bourke Road, Camberwell 3124 VIC"
                        ></v-textarea>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Billing Address</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                        <v-text-field v-model="property_details.billing_address" @input="updateLeads"
                        outlined
                        dense
                        hide-details="auto"
                    ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Property Type</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                       <v-select @input="updateLeads" v-model="property_details.property_type" :items="propertyTypeDD" outlined placeholder="Residentail / Business" dense hide-details="auto">
                        </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Life Support</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                        <v-select @input="updateLeads" v-model="property_details.life_support" :items="lifeSupportDD" outlined  placeholder="Yes or No" dense hide-details="auto">
                        </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Solar Power</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                        <v-select @input="updateLeads" v-model="property_details.solor_power" :items="solarPowerDD" outlined placeholder="Yes or No" dense hide-details="auto">
                        </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>NMI (Power)</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                        <v-text-field @input="updateLeads"
                            v-model="property_details.nmi"
                            outlined
                            dense
                            hide-details="auto"
                        ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>MIRN (Gas</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB"  v-slot="{ errors }">
                        <v-text-field
                            @input="updateLeads"
                            v-model="property_details.mirn"
                            outlined
                            dense
                            hide-details="auto"
                        ></v-text-field>
                    </ValidationProvider>
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
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                        <v-select v-model="indentification.type" @input="updateLeads" item-text="text" item-value="value" :items="idenficationTypeDD" outlined dense hide-details="auto" >
                        </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Card Number</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                        <v-text-field
                            v-model="indentification.number"
                            @input="updateLeads"
                            indentification
                        outlined
                        dense
                        hide-details="auto"
                    ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Special Number</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                    <v-select v-model="indentification.special_number"  @input="updateLeads" :items="specialNumberDD" outlined dense hide-details="auto" >
                    </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Expiry Date</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Expired Date" rules="required"  v-slot="{ errors }">

                        <v-menu
                            v-model="showMovingDate"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                            min-width="290px"
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <ValidationProvider name="Expired Date" rules="required"  v-slot="{ errors }">
                                    <v-text-field
                                        label="Expired Date*"
                                        placeholder="DD/MM/YYYY"
                                        outlined
                                        dense
                                        append-icon="mdi-calendar"
                                        v-model="indentification.expire_date"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        :error-messages=" errors[0]"
                                        hide-details="auto"
                                    ></v-text-field>
                                </ValidationProvider>
                            </template>
                            <v-date-picker v-model="indentification.expire_date" @input="showMovingDate = false"></v-date-picker>
                        </v-menu>

                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Card Colour</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                    <v-select v-model="indentification.color"  item-text="text" item-value="value" @input="updateLeads" :items="colorDD" outlined dense hide-details="auto" >
                    </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <p class="sub-title mt-5">Agent’s Additional Instructions <v-btn text  @click="readMore">read more ...</v-btn></p>
            <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                <v-textarea
                    v-model="indentification.additional_instruction"
                    @input="updateLeads"
                    outlined
                    hide-details="auto"
                    placeholder="Additional Instructions goes here."
                ></v-textarea>
            </ValidationProvider>
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
            emailBillingDD: [ {
                text: 'Yes',
                value: 1
                },
                {
                    text: 'No',
                    value: 2
                }],
            tenantTypeDD: [
                {
                    text: 'Renter',
                    value: 1
                },
                {
                    text: 'Owner',
                    value: 2
                }
            ],

            propertyTypeDD:[
                {
                    text: 'Recidential',
                    value: 1
                },
                {
                    text: 'Business',
                    value: 2
                }
            ],
            lifeSupportDD:[
                {
                    text: 'Yes',
                    value: 1
                },
                {
                    text: 'No',
                    value: 2
                }
            ],
            solarPowerDD:[
                {
                    text: 'Yes',
                    value: 1
                },
                {
                    text: 'No',
                    value: 2
                }
            ],
            idenficationTypeDD:[
                {
                    text: 'Passport',
                    value: 'passport'
                },
                {
                    text: 'Driver\'s License',
                    value: 'drivers_license'
                },
                {
                    text: 'Medical Card',
                    value: 'medical_card'
                }
            ],
            specialNumberDD:[
                1,2
            ],
            colorDD:[
                {
                    text: 'Green',
                    value: 'green'
                },
                {
                    text: 'Blue',
                    value: 'blue'
                },
                {
                    text: 'Yellow',
                    value: 'yellow'
                }
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

            },
            showMovingDate: false,
            connection_date: false,
            showDateOfBirth: false
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
        },
        readMore() {
            this.$emit('readMore');
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
