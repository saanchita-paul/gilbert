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
                                :value="expire_date"
                            ></v-text-field>
                            </div>
                        </div>
                        <div class="crm-text-field" v-if="data.identification.type == 3">
                            <div class="field-label">
                                <span>Card Colour</span>
                            </div>
                            <div class="text-field">
                                <v-select   readonly outlined dense hide-details="auto"
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

                        <div v-if="data.submitType == 'water'" class="d-flex">
                            <div class="d-flex px-2 py-1" style="background-color: purple; border-radius: 5px;">
                                <v-icon color="#0CC4ED">
                                    mdi-water
                                </v-icon>
                                <div class="white--text"> Water </div>
                            </div>
                        </div>
                        <div v-else-if="data.submitType == 'energy' || data.submitType === 'power' || data.submitType === 'gas'">
                            <div class="d-flex mb-2">
                                <div v-if="data.submitType === 'energy' || data.submitType === 'power'" class="d-flex mx-2">
                                    <div class="d-flex px-2 py-1" style="background-color: #542E89; border-radius: 5px;">
                                        <v-icon color="#FFC107" size="15">
                                            mdi-flash
                                        </v-icon>
                                        <div class="white--text font-weight-bold"> Power </div>
                                    </div>
                                </div>

                                <div v-if="data.submitType === 'energy' || data.submitType === 'gas'" class="d-flex">
                                    <div class="d-flex px-2 py-1" style="background-color: #542E89; border-radius: 5px;">
                                        <v-icon color="#FF5722" size="15">
                                            mdi-fire
                                        </v-icon>
                                        <div class="white--text font-weight-bold"> Gas </div>
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
                                        :value="getProvider"
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
                                    :value="selectedPlan"
                                    readonly
                                ></v-text-field>
                                </div>
                            </div>
                        </div>

                    </v-col>
                    <v-col cols="12" v-if="isAuthorizedPersonExist">
                        <v-row>
                            <v-col cols="12">
                                <p class="sub-title title-align">Authorized Person</p>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Title</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value=" this.authorizedPerson.title"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Date of Birth</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value=" this.authorizedPerson.dob"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>First Name</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value=" this.authorizedPerson.first_name"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Mobile</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="authorizedPerson.phone"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Middle Name</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="authorizedPerson.middle_name"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Email</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="authorizedPerson.email"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="6" class="py-0">
                            <div class="crm-text-field">
                                <div class="field-label">
                                    <span>Last Name</span>
                                </div>
                                <div class="text-field">
                                    <v-text-field
                                        outlined
                                        dense
                                        hide-details="auto"
                                        :value="authorizedPerson.last_name"
                                        readonly
                                    ></v-text-field>
                                </div>
                            </div>
                        </v-col>
                            <v-col cols="6" class="py-0">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Role</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="authorizedPerson.role"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="12">
                                <p class="sub-title title-align">Authorized Person's Identification</p>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Identification Type</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="authorizedPerson.identification_type"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>{{authorizedPerson.card_title}} No.</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="authorizedPerson.card_number"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="6" class="py-0" v-if="authorizedPerson.card_title === 'Card'">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Special Number</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="authorizedPerson.special_number"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col class="py-0" v-if="authorizedPerson.card_title === 'License'" cols="6">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>State</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="authorizedPerson.state"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col  class="py-0" cols="6"  v-if="this.authorizedPerson.card_title === 'Passport'">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Issuing Country</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="this.authorizedPerson.country"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col class="py-0" cols="6">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Expire Date</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="this.authorizedPerson.expire_date"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="6"  class="py-0" v-if="this.authorizedPerson.card_title === 'Card'">
                                <div class="crm-text-field">
                                    <div class="field-label">
                                        <span>Card Colour</span>
                                    </div>
                                    <div class="text-field">
                                        <v-text-field
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :value="this.authorizedPerson.card_color"
                                            readonly
                                        ></v-text-field>
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="12">
                        <v-divider></v-divider>
                    </v-col>
                    <v-col cols="12">
                        <v-checkbox v-model="is_life_support" label='I have asked the applicant "Does anyone in the household require the use of medical equipment for life support?”'></v-checkbox>
                    </v-col>
                    <v-col cols="12">
                        <v-checkbox v-model="is_temp_condition" label='I have asked the applicant "Do you understand and agree with everything we have discussed today"?'></v-checkbox>
                    </v-col>
                    <p v-if="isLifeSupportAndEA" class="life-support">Sorry you cannot submit connection application for this customer.</p>
                </v-row>
            </section>

            <v-footer  class="text-right">
                <v-col class="text-right" cols="12">
                    <v-btn @click="backToEdit">Back to Edit</v-btn>
                    <v-btn  color="primary" @click="confirmSubmit" :disabled="!allOk">Confirm and Submit</v-btn>
                </v-col>
            </v-footer>
        </v-card>
</template>

<script>
import SPECIAL_NUMBER from "@scripts/data/constants/SPECIAL_NUMBER";
import IDENTIFICATION from "@scripts/data/constants/IDENTIFICATION";
import dayJs from "dayjs";
import { titlesMapperForDropdown } from  "@scripts/data/titleMapper";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import SecondaryContactMapper from "@scripts/api/mappers/crm/SecondaryContactMapper";
import {isNull} from "lodash-es";
export default {
  name: "ConfirmSubmission",
    props:{
        data: {
            require: true
        }
    },
    data() {
      return {
          authorizedPerson: null,
          isAuthorizedPersonExist: false,
          is_temp_condition:null,
          is_discount_condition:null,
          is_credit_condition:null,
          is_credit2_condition:null,
          is_life_support: null,
          row:null,
          titlesDD:titlesMapperForDropdown,
          emailBillingDD: [
              {
                text: 'Yes',
                value: 1
              },
              {
                  text: 'No',
                  value: 2
              }
            ],
          statesDD: [
              {text: 'NSW', value: 'New South Wales'},
              {text: 'VIC', value: 'Victoria'},
              {text: 'QLD', value: 'Queensland'},
              {text: 'SA', value: 'South Australia'},
              {text: 'NT', value: 'Northern Territory'},
              {text: 'TAS', value: 'Tasmania'},
              {text: 'ACT', value: 'Australian Capital Territory'},
              {text: 'WA', value: 'Western Australia'},
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
          specialNumberDD:SPECIAL_NUMBER,
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
          provider: null
      }
    },
    computed: {
        allOk() {
           return this.is_temp_condition  && this.is_life_support && !this.isLifeSupportAndEA;
        },
        selectedPlan() {
            
            return this.data.selectedPlan = LeadApplicationService.mapPlan(this.data.selectedPlan);
        },
        getProvider() {
            let providerData = this.data.selectedProvider;
            return this.provider = LeadApplicationService.mapProvider(providerData);
        },
        expire_date() {
            if(this.data.identification.type === IDENTIFICATION.MEDICARE) {
                return dayJs(this.data.identification.expire_date,'DD/MM/YYYY').format('MM/YY');
            } else {
                return this.data.identification.expire_date;
            }
        },
        isLifeSupportAndEA() {
            return this.data.selectedProvider === 'ea'
               && (this.data.is_gas_life_support || this.data.is_power_life_support);
        }
    },
    methods: {
        backToEdit() {
            this.$emit('backToEdit');
        },
        confirmSubmit() {
            this.$emit('confirmSubmitLead');
        },
        async loadAuthorizedPerson() {
            let unMappedSecondaryContact = await LeadApplicationService.loadAuthorizedPerson(this.$route.params.id);
            if(isNull(unMappedSecondaryContact)) {
                this.isAuthorizedPersonExist = false;
                return;
            }
            this.authorizedPerson = SecondaryContactMapper.mapServerData(unMappedSecondaryContact);
            this.isAuthorizedPersonExist = true;
        },
    },
    mounted() {
      this.loadAuthorizedPerson();
    }
};
</script>

<style scoped>
.life-support {
    color: red;
    margin-left: 18px;
}
</style>
