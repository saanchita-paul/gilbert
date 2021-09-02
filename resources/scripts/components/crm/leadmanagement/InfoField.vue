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
                            item-value="value"
                            item-text="text"
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
                    <ValidationProvider name="Mobile Number" rules="required|cv-phone|length:10"  v-slot="{ errors }">
                        <v-text-field
                            v-model="person_details.phone"  @input="updateLeads"
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
                        <v-select v-model="person_details.is_email_billing" :items="emailBillingDD" item-text="text" item-value="value"  @input="updateLeads" :error-messages=" errors[0]" outlined dense hide-details="auto">
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
                                <ValidationProvider name="Date Of Birth" rules="required"  v-slot="{ errors }">
                                    <v-text-field
                                        placeholder="DD/MM/YYYY"
                                        outlined
                                        dense
                                        append-icon="mdi-calendar"
                                        v-model="property_details.moving_date"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                        :error-messages=" errors[0]"
                                        hide-details="auto"
                                        @input="updateLeads"
                                    ></v-text-field>
                                </ValidationProvider>
                            </template>
                            <v-date-picker v-model="property_details.moving_date" @input="connection_date = false"></v-date-picker>
                        </v-menu>

                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Service Address</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Service Address" rules="required"  v-slot="{ errors }">
                        <v-textarea
                            @input="updateLeads"
                            v-model="property_details.address_text"
                            outlined
                            hide-details="auto"
                            :error-messages=" errors[0]"
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
                    <span>Same as service address</span>
<!--                    <ValidationProvider name="Billing Address" rules="required"  v-slot="{ errors }">-->
<!--                        <v-text-field v-model="property_details.billing_address" @input="updateLeads"-->
<!--                        outlined-->
<!--                        dense-->
<!--                        hide-details="auto" :error-messages=" errors[0]"-->
<!--                    ></v-text-field>-->
<!--                    </ValidationProvider>-->
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Property Type</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Property Type" rules="required"  v-slot="{ errors }">
                       <v-select @input="updateLeads"  :error-messages=" errors[0]"
                                 v-model="property_details.property_type" :items="propertyTypeDD" outlined placeholder="Residential" dense hide-details="auto">
                        </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Life Support</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Life Support" rules="required"  v-slot="{ errors }">
                        <v-select @input="updateLeads"  :error-messages=" errors[0]"
                                  v-model="property_details.has_life_support" :items="lifeSupportDD" outlined  placeholder="Yes or No" dense hide-details="auto">
                        </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>Solar Power</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Solar Power" rules="required"  v-slot="{ errors }">
                        <v-select @input="updateLeads" :error-messages=" errors[0]"
                                  v-model="property_details.has_solar" :items="solarPowerDD" outlined placeholder="Yes or No" dense hide-details="auto">
                        </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>NMI (Power)</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="NMI"  rules="min:10|max:11|numeric"  v-slot="{ errors }">
                        <v-text-field @input="updateLeads"

                            v-model="property_details.nmi"
                            outlined
                            dense
                            hide-details="auto"
                                      :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>MIRN (Gas)</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Mirn"  rules="min:10|max:10|numeric" v-slot="{ errors }">
                        <v-text-field
                            @input="updateLeads"
                            v-model="property_details.mirn"
                            outlined
                            dense
                            hide-details="auto"
                            :error-messages=" errors[0]"
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
                    <ValidationProvider name="Identification Number" rules="required"  v-slot="{ errors }">
                        <v-select v-model="indentification.type"  :error-messages=" errors[0]"  @input="updateLeads" item-text="text" item-value="value" :items="idenficationTypeDD" outlined dense hide-details="auto" >
                        </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field">
                <div class="field-label">
                    <span>{{indentification.type == 1?'Passport ':indentification.type == 2?'Driver’s License':indentification.type == 3?'Medicare Card ':'Card'}} Number</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="Card Number" rules="required"  v-slot="{ errors }">
                        <v-text-field
                            :error-messages=" errors[0]"
                            v-model="indentification.card_number"
                            @input="updateLeads"
                            indentification
                        outlined
                        dense
                        hide-details="auto"
                    ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>
            <div class="crm-text-field" v-if="indentification.type == 1">
                <div class="field-label">
                    <span>Country</span>
                </div>
                <div class="text-field" >
                    <ValidationProvider name="Country" rules="required"  v-slot="{ errors }">
                        <v-text-field
                            v-model="indentification.country"
                            @input="updateLeads"
                            indentification
                            :error-messages=" errors[0]"
                            outlined
                            dense
                            hide-details="auto"
                        ></v-text-field>
                    </ValidationProvider>
                </div>
            </div>

            <div class="crm-text-field" v-if="indentification.type == 2">
                <div class="field-label">
                    <span>State</span>
                </div>
                <div class="text-field" >
                    <ValidationProvider name="State" rules="required"  v-slot="{ errors }">
                        <v-select
                            v-model="indentification.state"
                            :items="statesDD"
                            item-text="text"
                            item-value="value"
                            :error-messages=" errors[0]"
                            @input="updateLeads"
                            indentification
                            outlined
                            dense
                            hide-details="auto"
                        ></v-select>
                    </ValidationProvider>
                </div>
            </div>

            <div class="crm-text-field" v-if="indentification.type == 3">
                <div class="field-label">
                    <span>Special Number</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                    <v-select v-model="indentification.special_number"
                              :error-messages=" errors[0]"
                              @input="updateLeads" :items="specialNumberDD" outlined dense hide-details="auto" >
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
            <div class="crm-text-field" v-if="indentification.type == 3">
                <div class="field-label">
                    <span>Card Colour</span>
                </div>
                <div class="text-field">
                    <ValidationProvider name="DOB" rules="required"  v-slot="{ errors }">
                    <v-select v-model="indentification.card_color"  item-text="text" item-value="value" @input="updateLeads" :items="colorDD" outlined dense hide-details="auto" >
                    </v-select>
                    </ValidationProvider>
                </div>
            </div>
            <p class="sub-title mt-5">Agent’s Additional Instructions <v-btn text right class="primary--text" @click="readMore">read more ...</v-btn></p>
            <ValidationProvider name="DOB"   v-slot="{ errors }">
                <v-textarea
                    v-model="person_details.additional_instruction"
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
        lead:{
            require: true,
        }
    },
    data () {
        return {
            titlesDD:[

                {
                    text: 'Mr',
                    value: 'MR'
                },
                {
                    text: 'Mrs',
                    value: 'MISS'
                },
                {
                    text: 'Mrs',
                    value: 'MRS'
                },
                {
                    text: 'Ms',
                    value: 'MS'
                },

            ],
            emailBillingDD: [ {
                text: 'Yes',
                value: 1
                },
                {
                    text: 'No',
                    value: 2
                }],

            statesDD: [
                {text: 'NSW', value: 'New South Wales'},
                {text: 'VIC', value: 'Victoria'},
                {text: 'QLD', value: 'Queensland'},
                {text: 'SA', value: 'South Australia'},
                {text: 'NT', value: 'Northern Territory'},
                {text: 'TAS', value: 'Tasmania'},
                {text: 'ACT', value: 'Australian Capital Territory'},
            ],
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
                    text: 'Residential',
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
                    value: 1
                },
                {
                    text: 'Driver\'s License',
                    value: 2
                },
                {
                    text: 'Medical Card',
                    value: 3
                }
            ],
            specialNumberDD:[
                "1","2"
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
                card_number: '',
                special_number: '',
                expire_date: '',
                card_color: '',
                state: '',
                country: ''

            },
            property_details: {
                moving_date: '',
                address_text: '',
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
                phone: '',
                email: '',
                is_email_billing: '',
                tenancy_type: '',
                additional_instruction: '',

            },
            showMovingDate: false,
            connection_date: false,
            showDateOfBirth: false
        }
    },

    methods: {
        updateLeads() {
           this.$emit('updateLead',{
               indentification: this.indentification,
               property_details: this.property_details,
               person_details: this.person_details,
           })
        },

        readMore() {
            this.$emit('readMore');
        },

        synFormData () {

            // console.log(this.lead);

            this.person_details.title = this.lead.title;
            this.person_details.first_name = this.lead.first_name;
            this.person_details.last_name = this.lead.last_name;
            this.person_details.dob = this.lead.dob;
            this.person_details.email = this.lead.email;
            this.person_details.phone = this.lead.phone;
            this.person_details.tenancy_type = this.lead.tenancy_type;
            this.person_details.is_email_billing = this.lead.is_email_billing;
            this.person_details.additional_instruction = this.lead.additional_instruction;

            this.property_details.moving_date = this.lead.moving_date;
            this.property_details.address_text = this.lead.address_text;
            // this.property_details.billing_address = this.lead.billing_address;
            this.property_details.property_type = this.lead.property_type;
            this.property_details.has_life_support = this.lead.has_life_support;
            this.property_details.has_solar = this.lead.has_solar;
            this.property_details.nmi = this.lead.nmi;
            this.property_details.mirn = this.lead.mirn;

            this.indentification.type = this.lead.identification?.type;
            this.indentification.card_number = this.lead.identification?.card_number;
            this.indentification.state = this.lead.identification?.state;
            this.indentification.country = this.lead.identification?.country;
            this.indentification.special_number = this.lead.identification?.special_number;
            this.indentification.expire_date = this.lead.identification?.expire_date;
            this.indentification.card_color = this.lead.identification?.card_color;
        }
    },

    watch: {
        indentification()
        {
            // console.log('I am changed')
        },

        property_details() {
            // console.log('I am changed')
        },

        person_details() {
            // console.log('I am changed')
        }

    },

    mounted() {
      this.synFormData();
      this. updateLeads();
    }
};
</script>

<style scoped>
</style>
