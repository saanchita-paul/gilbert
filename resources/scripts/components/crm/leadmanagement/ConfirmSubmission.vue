<template>
        <v-card v-if="data">
            <v-app-bar height="80px">
                <span class="py-4 dialogs-title">Confirm Submission</span>
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
                                <v-select outlined dense hide-details="auto" v-bind:value="data.title" readonly :items="titlesDD" placeholder="Mr">
                                </v-select>
                                </div>
                            </div>
                            <div class="crm-text-field">
                                <div class="field-label">
                                    <span>Firstname</span>
                                </div>
                                <div class="text-field">
                                    <v-text-field
                                        :value="data.first_name"
                                        readonly
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
                                        :value="data.last_name"
                                        readonly
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
                                        :value="data.dob"
                                        readonly
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
                                        readonly
                                        :value="data.phone"
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
                                        readonly
                                        :value="data.email"
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
                                    <v-select  :items="emailBillingDD" readonly item-text="text" item-value="value" :value="data.is_email_billing" outlined  dense hide-details="auto">
                                </v-select>
                                </div>
                            </div>
                            <div class="crm-text-field">
                                <div class="field-label">
                                    <span>Tenancy Type</span>
                                </div>
                                <div class="text-field">
                                <v-select :items="tenantTypeDD" item-text="text" readonly item-value="value" :value="data.tenancy_type" outlined dense hide-details="auto" >
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
                                        readonly
                                        :value="data.moving_date"
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
                                        readonly
                                        :value="data.address_text"
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
                                    <span>Same as service address</span>
                                </div>
                            </div>
                            <div class="crm-text-field">
                                <div class="field-label">
                                    <span>Property Type</span>
                                </div>
                                <div class="text-field">
                                <v-select outlined readonly placeholder="Residentail / Business" dense hide-details="auto"
                                            :value="data.property_type"
                                            item-value="value"
                                            item-text="text"
                                            :items="propertyTypeDD">
                                    </v-select>
                                </div>
                            </div>
<!--                            <div class="crm-text-field">-->
<!--                                <div class="field-label">-->
<!--                                    <span>Life Support</span>-->
<!--                                </div>-->
<!--                                <div class="text-field">-->
<!--                                    <v-select readonly outlined placeholder="Yes / No"-->
<!--                                                :value="data.has_life_support"-->
<!--                                                item-value="value"-->
<!--                                                item-text="text"-->
<!--                                                :items="lifeSupportDD"-->

<!--                                                dense hide-details="auto">-->
<!--                                    </v-select>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="crm-text-field">
                                <div class="field-label">
                                    <span>Solar Power</span>
                                </div>
                                <div class="text-field">
                                    <v-select readonly outlined placeholder="Yes / No"

                                                :value="data.has_solar"
                                                item-value="value"
                                                item-text="text"
                                                :items="solarPowerDD"

                                                dense hide-details="auto">
                                    </v-select>
                                </div>
                            </div>
                            <div class="crm-text-field">
                                <div class="field-label">
                                    <span>NMI (Power)</span>
                                </div>
                                <div class="text-field">
                                    <v-text-field
                                        readonly
                                        :value="data.nmi"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                </div>
                            </div>
                            <div class="crm-text-field">
                                <div class="field-label">
                                    <span>MIRN (Gas)</span>
                                </div>
                                <div class="text-field">
                                        <v-text-field
                                            readonly
                                            :value="data.mirn"
                                            outlined
                                            dense
                                            hide-details="auto"
                                        ></v-text-field>
                                </div>
                            </div>
                    </v-col>

                    <v-col cols="12">
                        <v-divider></v-divider>
                    </v-col>

                    <v-col cols="6">
                        <p class="sub-title title-align">Identification</p>
                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>Identification</span>
                            </div>
                            <div class="text-field">
                                <v-select readonly outlined dense hide-details="auto"
                                            :value="data.identification.type"
                                            item-value="value"
                                            item-text="text"
                                            :items="idenficationTypeDD"
                                >
                                </v-select>
                            </div>
                        </div>
                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>{{data.identification.type == 1?
                                    'Passport ':data.identification.type == 2?
                                        'Driver’s License':data.identification.type == 3?
                                            'Medicare Card ':'Card'}} Number</span>

                            </div>
                            <div class="text-field">
                                <v-text-field
                                    readonly
                                    :value="data.identification.card_number"
                                outlined
                                dense
                                hide-details="auto"
                            ></v-text-field>
                            </div>
                        </div>

                        <div v-if="data.identification.type == 1" class="crm-text-field">
                            <div class="field-label">
                                <span>Country</span>
                            </div>
                            <div class="text-field">
                                <v-text-field
                                    readonly
                                    outlined
                                    dense
                                    hide-details="auto"
                                    :value="data.identification.country"
                                ></v-text-field>
                            </div>
                        </div>

                        <div v-if="data.identification.type == 3" class="crm-text-field" >
                            <div class="field-label">
                                <span>Special Number</span>
                            </div>
                            <div class="text-field">
                                <v-select readonly outlined dense hide-details="auto"
                                            :value="data.identification.special_number"
                                            :items="specialNumberDD"
                                >
                                </v-select>
                            </div>
                        </div>
                        <div v-if="data.identification.type == 2" class="crm-text-field" >
                            <div class="field-label">
                                <span>State</span>
                            </div>
                            <div class="text-field">
                                <v-select readonly outlined dense hide-details="auto"
                                            :value="data.identification.state"
                                            :items="statesDD"
                                            item-value="value"
                                            item-text="text"
                                >
                                </v-select>
                            </div>
                        </div>
                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>Expiry Date</span>
                            </div>
                            <div class="text-field">
                                <v-text-field
                                    readonly
                                outlined
                                dense
                                hide-details="auto"
                                :value="data.identification.expire_date"
                            ></v-text-field>
                            </div>
                        </div>
                        <div class="crm-text-field" v-if="data.identification.type == 3">
                            <div class="field-label">
                                <span>Card Colour</span>
                            </div>
                            <div class="text-field">
                                <v-select readonly outlined dense hide-details="auto"
                                            :value="data.identification.card_color"
                                            item-value="value"
                                            item-text="text"
                                            :items="colorDD"
                                >
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
                                        <div class="service-box" :class="{'active':isActive('power')}">
                                            <p class="mb-0"><v-icon color="yellow">mdi-flash</v-icon> Power</p>
                                        </div>
                                        <div class="service-box" :class="{'active':isActive('gas')}">
                                            <p class="mb-0"><v-icon color="red">mdi-fire</v-icon> Gas</p>
                                        </div>
                                </div>
                                </div>
                                <div>
                                    <p class="mb-0 ml-2">Water</p>
                                    <div class="service-box" :class="{'active':isActive('water')}">
                                        <p class="mb-0"><v-icon color="grey lighten-1">mdi-water</v-icon> water</p>
                                    </div>
                                </div>
                                <div>
                                    <p class="mb-0 ml-2">Internet</p>
                                    <div class="service-box" :class="{'active':isActive('internet')}">
                                        <p class="mb-0"><v-icon color="grey lighten-1">mdi-wifi</v-icon> Internet</p>
                                    </div>
                                </div>
                        </div>
                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>Supplier</span>
                            </div>
                            <div class="text-field">
                                <v-text-field
                                    readonly
                                    outlined
                                    dense
                                    hide-details="auto"
                                    value="EA"

                                ></v-text-field>
                            </div>
                        </div>
                        <div class="crm-text-field">
                            <div class="field-label">
                                <span>Plan</span>
                            </div>
                            <div class="text-field">
                                <v-text-field
                                outlined
                                dense
                                hide-details="auto"
                                :value="data.plan_type.value"
                                readonly
                            ></v-text-field>
                            </div>
                        </div>

                    </v-col>

                    <v-col cols="12">
                        <v-divider></v-divider>
                    </v-col>



                    <v-col cols="12">


<!--                        <p class="sub-title">I have asked the applicant "Does anyone in the household require the use of medical equipment for life support?”</p>-->
                        <v-checkbox v-model="is_life_support" label='I have asked the applicant "Does anyone in the household require the use of medical equipment for life support?”'>

                        </v-checkbox>
                    </v-col>
                    <v-col cols="12">
<!--                        <p class="sub-title">I have asked the applicant "Do you understand and agree with everything we have discussed today"?</p>-->
                        <v-checkbox v-model="is_temp_condition" label='I have asked the applicant "Do you understand and agree with everything we have discussed today"?'></v-checkbox>
                    </v-col>


                </v-row>
            </section>

            <v-footer  class="text-right">
                <v-col class="text-right" cols="12">
                    <v-btn @click="backToEdit">Back to Edit</v-btn>
                    <v-btn  color="primary" @click="finalConfirmation" :disabled="!allOk">Confirm and Submit</v-btn>
                </v-col>
            </v-footer>
        </v-card>
</template>

<script>
export default {
  name: "ConfirmSubmission",
    props:{
        data: {
            require: true
        }
    },
    data() {
      return {
          is_temp_condition:null,
          is_discount_condition:null,
          is_credit_condition:null,
          is_credit2_condition:null,
          is_life_support: null,
          row:null,
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
                  value: 'GREEN'
              },
              {
                  text: 'Blue',
                  value: 'BLUE'
              },
              {
                  text: 'Yellow',
                  value: 'YELLOW'
              }
          ],
      }
    },
    computed:{
      allOk()
      {
          // return false;
          return this.is_temp_condition  &&
              this.is_life_support;
      }
    },
    methods: {
        backToEdit() {
            this.$emit('backToEdit');
        },
        finalConfirmation() {
            this.$emit('saveData');
        },
        isActive(service) {
            return this.data.service_interests.includes(service);
        }

        },

    mounted() {
      console.log(this.data);
    }

};
</script>

<style scoped>
</style>
