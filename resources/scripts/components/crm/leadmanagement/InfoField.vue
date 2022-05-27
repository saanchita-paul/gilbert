<template>
  <v-row class="personal-details-row">
    <v-col cols="4">
      <p class="sub-title title-align">Personal Details</p>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Title *</span>
        </div>

        <div class="text-field">
          <ValidationProvider name="Title" rules="required" v-slot="{ errors }">
            <v-select
              @input="updateLeads"
              @blur="saveDraft('title', person_details.title)"
              outlined
              dense
              hide-details="auto"
              :items="titlesDD"
              v-model="person_details.title"
              :error-messages="errors[0]"
              placeholder="Please choose one"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Firstname *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="First Name"
            rules="required"
            v-slot="{ errors }"
          >
            <v-text-field
              v-model="person_details.first_name"
              @input="updateLeads"
              @blur="saveDraft('first_name', person_details.first_name)"
              outlined
              dense
              hide-details="auto"
              placeholder="Firstname"
              :error-messages="errors[0]"
            ></v-text-field>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Middlename</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="First Name"
            v-slot="{ errors }"
          >
            <v-text-field
              v-model="person_details.middle_name"
              @input="updateLeads"
              @blur="saveDraft('middle_name', person_details.middle_name)"
              outlined
              dense
              hide-details="auto"
              placeholder="Middlename"
              :error-messages="errors[0]"
            ></v-text-field>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Lastname *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Lastname"
            rules="required"
            v-slot="{ errors }"
          >
            <v-text-field
              @blur="saveDraft('last_name', person_details.last_name)"
              v-model="person_details.last_name"
              @input="updateLeads"
              outlined
              dense
              :error-messages="errors[0]"
              hide-details="auto"
            ></v-text-field>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Date of Birth {{ isTenancyHomeOwner?'':'*' }}</span>
        </div>
        <div class="text-field">
            <v-menu
              v-model="showDateOfBirth"
              :close-on-content-click="false"
              :nudge-right="40"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <ValidationProvider
                  name="Bate Of Birth"
                  :rules="`${isTenancyHomeOwner?'':'required|'}valid-date|adult`"
                  v-slot="{ errors }"
                >
                  <v-text-field
                    placeholder="DD/MM/YYYY"
                    outlined
                    dense
                    append-icon="mdi-calendar"
                    v-model="person_details.dob"
                    v-bind="attrs"
                    :error-messages="errors[0]"
                    hide-details="auto"
                    @change="updateDobPicker"
                  >
                    <template slot="append">
                      <v-icon v-on="on">mdi-calendar</v-icon>
                    </template>
                  </v-text-field>
                </ValidationProvider>
              </template>
              <v-date-picker
                v-model="dob"
                @input="showDateOfBirth = false"
              ></v-date-picker>
            </v-menu>
        </div>
      </div>

      <div class="crm-text-field">
        <div class="field-label">
          <span>Phone Type *</span>
        </div>
        <div class="text-field">
          <ValidationProvider name="Family Violance" v-slot="{ errors }">
            <v-select
              v-model="person_details.phone_type"
              :items="phoneTypeDD"
              item-text="text"
              item-value="value"
              :error-messages="errors[0]"
              @input="updateLeads"
              outlined
              dense
              hide-details="auto"
              @change="saveDraft('phone_type', person_details.phone_type)"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>

      <div class="crm-text-field" v-if="person_details.phone_type === 1">
        <div class="field-label">
          <span>Mobile *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Mobile Number"
            rules="required|cv-phone|length:10"
            v-slot="{ errors }"
          >
            <v-text-field
              v-model="person_details.phone"
              @input="updateLeads"
              outlined
              dense
              :error-messages="errors[0]"
              hide-details="auto"
              @blur="saveDraft('phone', person_details.phone)"
            ></v-text-field>
          </ValidationProvider>
        </div>
      </div>

      <div class="crm-text-field" v-if="person_details.phone_type === 2">
        <div class="field-label">
          <span>Homephone *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Homephone Number"
            rules="required|cv-phone|length:10"
            v-slot="{ errors }"
          >
            <v-text-field
              v-model="person_details.homephone"
              @input="updateLeads"
              outlined
              dense
              :error-messages="errors[0]"
              hide-details="auto"
              @blur="saveDraft('homephone', person_details.homephone)"
            ></v-text-field>
            <!-- TODO check save draft -->
          </ValidationProvider>
        </div>
      </div>

      <div class="crm-text-field" v-if="person_details.phone_type === 3">
        <div class="field-label">
          <span>I. Mobile Number *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="I. Mobile Number"
            rules="required"
            v-slot="{ errors }"
          >
            <v-text-field
              v-model="person_details.international_phone"
              @input="updateLeads"
              outlined
              dense
              :error-messages="errors[0]"
              hide-details="auto"
              @blur="saveDraft('international_phone', person_details.international_phone)"
            ></v-text-field>
          </ValidationProvider>
        </div>
      </div>

      <div class="crm-text-field">
        <div class="field-label">
          <span>Email *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Email"
            rules="required|email"
            v-slot="{ errors }"
          >
            <v-text-field
              v-model="person_details.email"
              @input="updateLeads"
              outlined
              dense
              hide-details="auto"
              :error-messages="errors[0]"
              @blur="saveDraft('email', person_details.email)"
            ></v-text-field>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Email Billing {{isWaterTabFocused ? '': "*"}}</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Email Billing"
            :rules="isWaterTabFocused ? '' : 'required'"
            v-slot="{ errors }"
          >
            <v-select
              v-model="person_details.is_email_billing"
              @blur="
                saveDraft('is_email_billing', person_details.is_email_billing)
              "
              :items="emailBillingDD"
              item-text="text"
              item-value="value"
              @input="updateLeads"
              :error-messages="errors[0]"
              outlined
              dense
              hide-details="auto"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Occupancy Type *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Tenant Type"
            rules="required"
            v-slot="{ errors }"
          >
            <v-select
              v-model="person_details.tenancy_type"
              :items="tenantTypeDD"
              item-text="text"
              item-value="value"
              :error-messages="errors[0]"
              @input="updateLeads"
              outlined
              dense
              hide-details="auto"
              @blur="saveDraft('tenancy_type', person_details.tenancy_type)"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>
        <AuthorizedPersonForm :lead-id="lead.id" :authorized-person-name="''"></AuthorizedPersonForm>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Family Violence </span>
        </div>
        <div class="text-field">
          <ValidationProvider name="Family Violance" v-slot="{ errors }">
            <v-select
              v-model="person_details.family_violance"
              :items="familyViolanceTypeDD"
              item-text="text"
              item-value="value"
              :error-messages="errors[0]"
              @input="updateLeads"
              outlined
              dense
              hide-details="auto"
              @blur="
                saveDraft('family_violance', person_details.family_violance)
              "
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>
    </v-col>

    <v-col cols="4" >
      <p class="sub-title title-align">Property Details</p>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Connection Date *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Connection Date"
            rules="required"
            v-slot="{ errors }"
          >
            <v-menu
              v-model="connection_date"
              :close-on-content-click="false"
              :nudge-right="40"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <ValidationProvider
                  name="Connection Date"
                  rules="required|valid-date|not-holiday:@h_state"
                  v-slot="{ errors }"
                >
                  <v-text-field
                    placeholder="DD/MM/YYYY"
                    outlined
                    dense
                    append-icon="mdi-calendar"
                    v-model="property_details.moving_date"
                    v-bind="attrs"
                    :error-messages="errors[0]"
                    hide-details="auto"
                    @input="updateLeads"
                    @change="updateConDatePicker"
                  >
                    <template slot="append">
                      <v-icon v-on="on">mdi-calendar</v-icon>
                    </template>
                  </v-text-field>
                </ValidationProvider>
              </template>
              <v-date-picker
                v-model="moving_date"
                :min="minConnectionDate"
                @input="connection_date = false"
              ></v-date-picker>
            </v-menu>
          </ValidationProvider>
        </div>
      </div>

      <ValidationProvider name="h_state">
        <v-text-field v-model="property_details.state" v-show="false" />
      </ValidationProvider>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Service Address *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Service Address"
            rules="required"
            v-slot="{ errors }"
          >
            <v-textarea
              :class="{ 'required-field': isInvalidAddress }"
              @click="openServiceAddress"
              v-model="property_details.address_text"
              style="min-height: 56px !important;"
              outlined
              auto-grow
              rows="5"
              hide-details="auto"
              :error-messages="errors[0]"
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
          <!-- <span>Same as my billing address</span> -->
          <ValidationProvider name="Billing Address"  v-slot="{ errors }">
              <v-textarea :value="billingAddressMsg"
              @click="openServiceAddress"
              outlined
              dense
              readonly
              hide-details="auto" :error-messages=" errors[0]"
          ></v-textarea>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Property Type *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Property Type"
            rules="required"
            v-slot="{ errors }"
          >
            <v-select
              @input="updateLeads"
              :error-messages="errors[0]"
              v-model="property_details.property_type"
              :items="propertyTypeDD"
              outlined
              placeholder="Residential"
              @blur="saveDraft('property_type', property_details.property_type)"
              dense
              hide-details="auto"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field" v-if="false">
        <div class="field-label">
          <span>Life Support *</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Life Support"
            rules="required"
            v-slot="{ errors }"
          >
            <v-select
              @input="updateLeads"
              :error-messages="errors[0]"
              v-model="property_details.has_life_support"
              :items="lifeSupportDD"
              outlined
              @blur="
                saveDraft('has_life_support', property_details.has_life_support)
              "
              placeholder="Yes or No"
              dense
              hide-details="auto"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Solar Power {{isWaterTabFocused ? '' : '*'}} </span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Solar Power"
            :rules="isWaterTabFocused ? '' : 'required'"
            v-slot="{ errors }"
          >
            <v-select
              @input="updateLeads"
              :error-messages="errors[0]"
              v-model="property_details.has_solar"
              :items="solarPowerDD"
              outlined
              @blur="saveDraft('has_solar', property_details.has_solar)"
              placeholder="Yes or No"
              dense
              hide-details="auto"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>
      <div v-if="!isNMIRequired" class="crm-text-field">
        <div class="field-label">
          <span>NMI (Power)</span>
        </div>
        <div class="text-field">
          <ValidationProvider name="NMI" v-slot="{ errors }">
            <v-text-field
              @input="updateLeads"
              v-model="property_details.nmi"
              @blur="saveDraft('nmi', property_details.nmi)"
              outlined
              dense
              hide-details="auto"
              :error-messages="errors[0]"
            >
              <template slot="append">
                <v-progress-circular
                  v-if="nmiMernFlag"
                  indeterminate
                  size="25"
                  color="primary"
                ></v-progress-circular>
              </template>
            </v-text-field>
          </ValidationProvider>
        </div>
      </div>


        <div v-if="isNMIRequired" class="crm-text-field">
            <div class="field-label">
                <span>NMI (Power) *</span>
            </div>
            <div class="text-field">
                <ValidationProvider name="NMI" rules="required" v-slot="{ errors }">
                    <v-text-field
                        @input="updateLeads"
                        v-model="property_details.nmi"
                        @blur="saveDraft('nmi', property_details.nmi)"
                        outlined
                        dense
                        hide-details="auto"
                        :error-messages="errors[0]"
                    >
                        <template slot="append">
                            <v-progress-circular
                                v-if="nmiMernFlag"
                                indeterminate
                                size="25"
                                color="primary"
                            ></v-progress-circular>
                        </template>
                    </v-text-field>
                </ValidationProvider>
            </div>
        </div>


      <div v-if="!isMERNRequired" class="crm-text-field">
        <div class="field-label">
          <span>MIRN (Gas)</span>
        </div>
        <div class="text-field">
          <ValidationProvider name="MIRN" rules="" v-slot="{ errors }">
            <v-text-field
              @input="updateLeads"
              v-model="property_details.mirn"
              @blur="saveDraft('mirn', property_details.mirn)"
              outlined
              dense
              hide-details="auto"
              :error-messages="errors[0]"
            >
              <template slot="append">
                <v-progress-circular
                  v-if="nmiMernFlag"
                  indeterminate
                  size="25"
                  color="primary"
                ></v-progress-circular>
              </template>
            </v-text-field>
          </ValidationProvider>
        </div>
      </div>

        <div  v-if="isMERNRequired" class="crm-text-field">
            <div class="field-label">
                <span>MIRN (Gas) *</span>
            </div>
            <div class="text-field">
                <ValidationProvider name="MIRN" rules="required" v-slot="{ errors }">
                    <v-text-field
                        @input="updateLeads"
                        v-model="property_details.mirn"
                        @blur="saveDraft('mirn', property_details.mirn)"
                        outlined
                        dense
                        hide-details="auto"
                        :error-messages="errors[0]"
                    >
                        <template slot="append">
                            <v-progress-circular
                                v-if="nmiMernFlag"
                                indeterminate
                                size="25"
                                color="primary"
                            ></v-progress-circular>
                        </template>
                    </v-text-field>
                </ValidationProvider>
            </div>
        </div>



      <div class="crm-text-field" v-if="property_details.state == 'Victoria'">
        <div class="field-label">
          <span>Is renovation going on? *</span>
        </div>
        <div class="text-field">
          <ValidationProvider name="is_renovation_on" v-slot="{ errors }">
            <v-select
              v-model="property_details.is_renovation_on"
              :items="homeRenovationDD"
              :error-messages="errors[0]"
              @input="updateLeads"
              outlined
              dense
              hide-details="auto"
              @change="saveDraft('is_renovation_on', property_details.is_renovation_on)"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>

      <div class="crm-text-field" v-if="property_details.state == 'Queensland'">
        <div class="field-label">
          <span>Is the electricity on at the property? *</span>
        </div>
        <div class="text-field">
          <ValidationProvider name="has_electricity" v-slot="{ errors }">
            <v-select
              v-model="property_details.has_electricity"
              :items="electricityDD"
              :error-messages="errors[0]"
              @input="updateLeads"
              outlined
              dense
              hide-details="auto"
              @change="saveDraft('has_electricity', property_details.has_electricity)"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>

      <div class="crm-text-field" v-if="property_details.state == 'Queensland' && property_details.has_electricity == false">
        <div class="field-label">
          <span>Inspection Time *</span>
        </div>
        <div class="text-field">
          <ValidationProvider name="Family Violance" v-slot="{ errors }">
            <v-select
              v-model="property_details.inspection_time"
              :items="inspectionTimes"
              :error-messages="errors[0]"
              @input="updateLeads"
              outlined
              dense
              hide-details="auto"
              @change="saveDraft('inspection_time', property_details.inspection_time)"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>


     <div class="crm-text-field mt-n6">
        <div class="field-label">
          <!-- <span>Inspection Time *</span> -->
        </div>
        <div class="text-field">
          <v-checkbox
              :rules="[v=>{ if(v) return `Sorry you cannot submit connection application for this customer`; else return true }]"
              v-model="$attrs.value.lifeSupportInfo.value"
              :label="`Does anyone in the household require the use of medical equipment for life support? `">
          </v-checkbox>
        </div>
      </div>


    </v-col>

    <v-col cols="4">
      <p class="sub-title title-align">Identification</p>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Identification {{ isTenancyHomeOwner?'':'*' }}</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Identification Number"
            :rules="`${isTenancyHomeOwner?'':'required'}`"
            v-slot="{ errors }"
          >
            <v-select
              v-model="indentification.type"
              :error-messages="errors[0]"
              @input="updateLeads"
              item-text="text"
              @change="changeIdentity"
              @blur="saveDraft('type', indentification.type, false, true)"
              item-value="value"
              :items="idenficationTypeDD"
              outlined
              dense
              hide-details="auto"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field">
        <div class="field-label">
          <span
            >{{
              indentification.type == 1
                ? "Passport Number"
                : indentification.type == 2
                ? "Driver’s License"
                : indentification.type == 3
                ? "Medicare Card"
                : "Card"
            }}
            {{ isTenancyHomeOwner?'':'*' }}
          </span>
        </div>
        <div class="text-field">
          <ValidationProvider
            :name="
              indentification.type == 1
                ? 'Passport Number'
                : indentification.type == 2
                ? 'Driver’s License'
                : indentification.type == 3
                ? 'Medicare Card '
                : ''
            "
            :rules="`${isTenancyHomeOwner?'':'required'}`"
            v-slot="{ errors }"
          >
            <v-text-field
              :error-messages="errors[0]"
              v-model="indentification.card_number"
              @blur="
                saveDraft(
                  'card_number',
                  indentification.card_number,
                  false,
                  true
                )
              "
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
          <span>Country {{ isTenancyHomeOwner?'':'*' }}</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Country"
            :rules="`${isTenancyHomeOwner?'':'required'}`"
            v-slot="{ errors }"
          >
            <v-text-field
              v-model="indentification.country"
              @input="updateLeads"
              @blur="saveDraft('country', indentification.country, false, true)"
              indentification
              :error-messages="errors[0]"
              outlined
              dense
              hide-details="auto"
            ></v-text-field>
          </ValidationProvider>
        </div>
      </div>

      <div class="crm-text-field" v-if="indentification.type == 2">
        <div class="field-label">
          <span>State {{ isTenancyHomeOwner?'':'*' }}</span>
        </div>
        <div class="text-field">
          <ValidationProvider name="State" :rules="`${isTenancyHomeOwner?'':'required'}`" v-slot="{ errors }">
            <v-select
              v-model="indentification.state"
              @blur="saveDraft('state', indentification.state, false, true)"
              :items="statesDD"
              item-text="text"
              item-value="value"
              :error-messages="errors[0]"
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
          <span>Special Number {{ isTenancyHomeOwner?'':'*' }}</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Special Number"
            :rules="`${isTenancyHomeOwner?'':'required'}`"
            v-slot="{ errors }"
          >
            <v-select
              v-model="indentification.special_number"
              @blur="
                saveDraft(
                  'special_number',
                  indentification.special_number,
                  false,
                  true
                )
              "
              :error-messages="errors[0]"
              @input="updateLeads"
              :items="specialNumberDD"
              outlined
              dense
              hide-details="auto"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>
      <div class="crm-text-field">
        <div class="field-label">
          <span>Expiry Date {{ isTenancyHomeOwner?'':'*' }}</span>
        </div>



        <div class="text-field"  v-if="indentification.type !== 3">
            <v-menu
              v-model="showMovingDate"
              :close-on-content-click="false"
              :nudge-right="40"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <ValidationProvider
                  name="Expired Date"
                  :rules="`${isTenancyHomeOwner?'':'required'}`"
                  v-slot="{ errors }"
                >
                  <v-text-field
                    placeholder="DD/MM/YYYY"
                    outlined
                    dense
                    v-model="indentification.expire_date"
                    v-bind="attrs"
                    :error-messages="errors[0]"
                    hide-details="auto"
                    @change="updateExpireDatePicker"
                  >
                    <template slot="append">
                      <v-icon v-on="on">mdi-calendar</v-icon>
                    </template>
                  </v-text-field>
                </ValidationProvider>
              </template>
              <v-date-picker
                v-model="expire_date"
                :min="minExpiredate"
                @input="showMovingDate = false"
              ></v-date-picker>
            </v-menu>
        </div>
        <div class="text-field"  v-if="indentification.type === 3">

            <v-menu
              v-model="showMovingDate"
              :close-on-content-click="false"
              :nudge-right="40"
              transition="scale-transition"
              offset-y
              min-width="290px"
            >
              <template v-slot:activator="{ on, attrs }">
                <ValidationProvider
                  name="Expired Date"
                  :rules="`${isTenancyHomeOwner?'':'required|'}medicare-date|medi-expire`"
                  v-slot="{ errors }"
                >
                  <v-text-field
                    placeholder="MM/YY"
                    outlined
                    dense
                    v-model="indentification.medicare_expire_date"
                    v-bind="attrs"
                    :error-messages="errors[0]"
                    hide-details="auto"
                    @change="updateExpireDateMedicare"
                  >
                    <template slot="append">
                      <v-icon v-on="on">mdi-calendar</v-icon>
                    </template>
                  </v-text-field>
                </ValidationProvider>
              </template>
              <v-date-picker
                v-model="medicare_expire_date"
                :min="minExpiredate"
                type="month"
                @input="showMovingDate = false"
              ></v-date-picker>
            </v-menu>
        </div>



      </div>
      <div class="crm-text-field" v-if="indentification.type == 3">
        <div class="field-label">
          <span>Card Colour {{ isTenancyHomeOwner?'':'*' }}</span>
        </div>
        <div class="text-field">
          <ValidationProvider
            name="Card Color"
            :rules="`${isTenancyHomeOwner?'':'required'}`"
            v-slot="{ errors }"
          >
            <v-select
              :error-messages="errors[0]"
              v-model="indentification.card_color"
              item-text="text"
              item-value="value"
              @blur="
                saveDraft('card_color', indentification.card_color, false, true)
              "
              @input="updateLeads"
              :items="colorDD"
              outlined
              dense
              hide-details="auto"
            >
            </v-select>
          </ValidationProvider>
        </div>
      </div>

      <v-row>
        <v-col cols="8">
          <p class="sub-title mt-5">Agent’s Additional Instructions</p>
        </v-col>
        <v-col cols="4">
          <v-btn
            text
            right
            class="primary--text float-right mt-5"
            @click="readMore"
            >read more ...</v-btn>
        </v-col>
      </v-row>
      <!--            <p class="sub-title mt-5">Agent’s Additional Instructions<v-btn text right class="primary&#45;&#45;text float-right" @click="readMore">read more ...</v-btn></p>-->
      <ValidationProvider name="Additional Instructions" v-slot="{ errors }">
        <v-textarea
          v-model="person_details.additional_instruction"
          @input="updateLeads"
          hide-details="auto"
          @blur="
            saveDraft(
              'additional_instruction',
              person_details.additional_instruction
            )
          "
          auto-grow
          filled
          class="pa-2"
          background-color="#FAFAFA"
          placeholder="Additional Instructions goes here."
          disabled
        ></v-textarea>
      </ValidationProvider>
    </v-col>

    <ServiceAddress
      v-if="serviceAddressFlag"
      :dialog="serviceAddressFlag"
      :propertyDetails="property_details"
      @saveAddress="saveAddress"
      @close="closeServiceAddress"
    >
    </ServiceAddress>
  </v-row>
</template>

<script>
import ServiceAddress from "@scripts/components/crm/ServiceAddress";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import DayJs from "dayjs";
import dayJs, * as dayjs from "dayjs";
import {isNull} from "lodash-es";
import AuthorizedPersonForm from "@scripts/components/crm/leadmanagement/AuthorizedPersonForm";
import SPECIAL_NUMBER from "@scripts/data/constants/SPECIAL_NUMBER";
import IDENTIFICATION from "@scripts/data/constants/IDENTIFICATION";
import ApplicationMapper from "@scripts/api/mappers/crm/ApplicationMapper";
import {titlesMapperForDropdown} from "@scripts/data/titleMapper";
import {medicareRules, mediExpireDate} from '@scripts/plugins/VeeValidate';
import {tenancyTypeMapper} from '@scripts/data/ConnectionApplicationMapper';
import {mapGetters} from "vuex";

export default {
  name: "InfoField",
  props: {
    lead: {
      require: true,
    },
    nmiMernFlag: {
      require: false,
    },
    services: {
      require: false,
    },
  },
  components: {
    ServiceAddress,
      AuthorizedPersonForm
  },
  data() {
    return {
      needLifeSupprt: false,
      loadNmi: false,
      titlesDD: titlesMapperForDropdown,
      minConnectionDate: LeadApplicationService.getMinConnectionDate(),
      minExpiredate: new Date().toISOString(),
      emailBillingDD: [
        {
          text: "Yes",
          value: 1,
        },
        {
          text: "No",
          value: 0,
        },
      ],
      inspectionTimes:[
        '8:00am - 1:00pm',
        '9:00am - 2:00pm',
        '10:00am - 3:00pm',
        '11:00am - 4:00pm',
        '12:00pm - 5:00pm',
        '1:00pm - 6:00pm',
      ],
      statesDD: [
        { text: "NSW", value: "New South Wales" },
        { text: "VIC", value: "Victoria" },
        { text: "QLD", value: "Queensland" },
        { text: "SA", value: "South Australia" },
        { text: "NT", value: "Northern Territory" },
        { text: "TAS", value: "Tasmania" },
        { text: "ACT", value: "Australian Capital Territory" },
        { text: "WA", value: "Western Australia" }, // TODO state definition can be updated
      ],
      tenantTypeDD: [
        {
          text: "Renter",
          value: 1,
        },
        {
          text: "Owner",
          value: 2,
        },
      ],
      homeRenovationDD: [
        {
          text: "No",
          value: 0,
        },
        {
          text: "Yes",
          value: 1,
        },
      ],
      electricityDD: [
        {
          text: "No",
          value: 0,
        },
        {
          text: "Yes",
          value: 1,
        },
      ],
      phoneTypeDD: [
        {
          text: "Mobile",
          value: 1,
        },
        {
          text: "Homephone",
          value: 2,
        },
        {
          text: "I. Mobile Number",
          value: 3,
        },
      ],
      familyViolanceTypeDD: [
        {
          text: "Yes",
          value: 1,
        },
        {
          text: "No",
          value: 2,
        },
        {
          text: "Not Applicable",
          value: 3,
        },
      ],

      propertyTypeDD: [
        {
          text: "Residential",
          value: 1,
        },
        {
          text: "Business",
          value: 2,
        },
      ],
      lifeSupportDD: [
        {
          text: "Yes",
          value: 1,
        },
        {
          text: "No",
          value: 2,
        },
      ],
      solarPowerDD: [
        {
          text: "Yes",
          value: 1,
        },
        {
          text: "No",
          value: 2,
        },
      ],
      idenficationTypeDD: [
        {
          text: "Passport",
          value: 1,
        },
        {
          text: "Driver's License",
          value: 2,
        },
        {
          text: "Medicare Card",
          value: 3,
        },
      ],
      specialNumberDD: SPECIAL_NUMBER,
      colorDD: [
        {
          text: "Green",
          value: "GREEN",
        },
        {
          text: "Blue",
          value: "BLUE",
        },
        {
          text: "Yellow",
          value: "YELLOW",
        },
      ],
      indentification: {
          type: "",
          card_number: "",
          special_number: "",
          expire_date: "",
          card_color: "",
          state: "",
          country: "",
          medicare_expire_date: ""
      },
      property_details: {
        moving_date: "",
        billing_address: "",
        property_type: "",
        life_support: "",
        solor_power: "",
        nmi: "",
        mirn: "",
        address_text: "",
        street_address: "",
        city: "",
        postcode: "",
        state: "",
        country: "",
        street_type:"",
        street_name:"",
        street_name_only:"",


        is_renovation_on: true,
        has_electricity: true,
        inspection_time: "",

        unit_number: "",
        street_number: "",
        is_billing_same: true,
        billing_address_text: "",
        billing_street_address: "",
        billing_city: "",
        billing_postcode: "",
        billing_state: "",
        billing_country: "",
        billing_unit_number: "",
        billing_street_number: "",
        billing_street_name: "",
        billing_street_name_only: "",
        connection_end_date: null,
        is_temporary_connection : null,
      },
      person_details: {
        title: "",
        first_name: "",
        middle_name: "",
        last_name: "",
        dob: "",
        phone: "",
        homephone: "",
        phone_type: "",
        email: "",
        is_email_billing: "",
        tenancy_type: "",
        family_violance: "",
        additional_instruction: "",
      },
        dob: null,
        moving_date: null,
        expire_date: null,
        medicare_expire_date: null,
        showMovingDate: false,
        connection_date: false,
        showDateOfBirth: false,
        serviceAddressFlag: false,
    };
  },
  methods: {
    openServiceAddress() {
      this.serviceAddressFlag = true;
    },

    closeServiceAddress() {
      this.serviceAddressFlag = false;
    },

    saveAddress(propertyDetails) {
      this.serviceAddressFlag = false;
      this.$emit("updateAddress", propertyDetails);
    },

    updateLeads() {
      // console.log('updated leads')
      // console.log(this.property_details);
      // console.log(this.lead);

      this.$emit("updateLead", {
        identification: this.indentification,
        property_details: this.property_details,
        person_details: this.person_details,
      });
    },

    readMore() {
      this.$emit("readMore");
    },

    synFormData() {
      // console.log(this.lead.identification?.expire_date);
      this.person_details.title = this.lead.title;
      this.person_details.first_name = this.lead.first_name;
      this.person_details.last_name = this.lead.last_name;
      this.person_details.middle_name = this.lead.middle_name;
      // this.person_details.dob = this.lead.dob;
      this.person_details.email = this.lead.email;
      this.person_details.phone = this.lead.phone;
      this.person_details.international_phone = this.lead.international_phone;
      this.person_details.tenancy_type = this.lead.tenancy_type;
      this.person_details.phone_type = this.lead.phone_type;
      this.person_details.homephone = this.lead.homephone;
      this.person_details.family_violance = this.lead.family_violance;
      this.person_details.is_email_billing = this.lead.is_email_billing;
      this.person_details.additional_instruction =
      this.lead.additional_instruction;

      this.dob = this.lead.dob;
      this.moving_date = this.lead.moving_date;
      // console.log('identifcation' , this.lead.identification)
      // this.expire_date = undefined;
      this.expire_date = this.lead.identification?.expire_date;
      // this.property_details.moving_date = this.lead.moving_date;
      this.property_details.is_billing_same = this.lead.is_billing_same;
      this.property_details.address_text = this.lead.address_text;
      // this.property_details.billing_address = this.lead.billing_address;
      this.property_details.property_type = this.lead.property_type;

      this.property_details.is_renovation_on = this.lead.is_renovation_on;
      this.property_details.has_electricity = this.lead.has_electricity;
      this.property_details.inspection_time = this.lead.inspection_time;


      this.property_details.has_life_support = this.lead.has_life_support;
      this.property_details.has_solar = this.lead.has_solar;
      this.property_details.nmi = this.lead.nmi;
      this.property_details.mirn = this.lead.mirn;
      this.property_details.street_address = this.lead.street_address;
      this.property_details.city = this.lead.city;
      this.property_details.street_number = this.lead.street_number;
      this.property_details.street_type = this.lead.street_type;
      this.property_details.street_name = this.lead.street_name;
      this.property_details.street_name_only = this.lead.street_name_only;
      this.property_details.unit_number = this.lead.unit_number;
      this.property_details.postcode = this.lead.postcode;
      this.property_details.state = this.lead.state;
      this.property_details.country = this.lead.country;
      this.property_details.billing_address_text =
        this.lead.billing_address_text;
      this.property_details.billing_street_address =
        this.lead.billing_street_address;
      this.property_details.billing_city = this.lead.billing_city;
      this.property_details.billing_postcode = this.lead.billing_postcode;
      this.property_details.billing_state = this.lead.billing_state;
      this.property_details.billing_unit_number = this.lead.billing_unit_number;
      this.property_details.billing_street_type = this.lead.billing_street_type;
      this.property_details.billing_street_number =
        this.lead.billing_street_number;
      this.property_details.billing_street_name = this.lead.billing_street_name;
      this.property_details.billing_street_name_only = this.lead.billing_street_name_only;

      this.indentification.type = this.lead.identification?.type;
      this.indentification.card_number = this.lead.identification?.card_number;
      this.indentification.state = this.lead.identification?.state;
      this.indentification.country = this.lead.identification?.country;
      this.indentification.special_number =
      this.lead.identification?.special_number;
      // this.indentification.expire_date = this.lead.identification?.expire_date;
      this.indentification.card_color = this.lead.identification?.card_color;
    },

    saveDraft(field, value, isDate = false, identification = false) {
      this.$emit("updateDraft", field, value, isDate, identification, true);
    },

    changeIdentity() {
      this.indentification.card_number = "";
      this.indentification.state = "";
      this.indentification.country = "";
      this.indentification.special_number = "";
      this.indentification.expire_date = "";
      this.indentification.card_color = "";
    },

    formatDate() {
      this.property_details.moving_date = dayJs(this.moving_date).format(
        "DD/MM/YYYY"
      );
        const birthdate =  dayjs(this.dob, 'YYYY-MM-DD');
        this.person_details.dob = birthdate.isValid() ? birthdate.format('DD/MM/YYYY'): null;

      if(this.indentification.type === IDENTIFICATION.MEDICARE) {
          this.indentification.medicare_expire_date = (dayJs(this.expire_date).isValid())
              ?  dayJs(this.expire_date).format("MM/YY")
              : "";
      } else {
          this.indentification.expire_date = (dayJs(this.expire_date).isValid())
              ? dayJs(this.expire_date).format("DD/MM/YYYY")
              : "";
      }


      let expire = this.expire_date == '' || this.expire_date == undefined || this.expire_date == null ? '' :  new DayJs(this.expire_date).isValid();
      this.indentification.expire_date = expire
        ? new DayJs(this.expire_date).format("DD/MM/YYYY")
        : "";
    },

    updateDobPicker() {
      if (DayJs(this.person_details.dob, "DD/MM/YYYY").isValid()) {
        this.dob = DayJs(this.person_details.dob, "DD/MM/YYYY").format(
          "YYYY-MM-DD"
        );
      }
    },

    updateConDatePicker() {
      if (DayJs(this.property_details.moving_date, "DD/MM/YYYY").isValid()) {
        this.moving_date = DayJs(
          this.property_details.moving_date,
          "DD/MM/YYYY"
        ).format("YYYY-MM-DD");
      }
    },
    updateExpireDatePicker() {
      if (DayJs(this.indentification.expire_date, "DD/MM/YYYY").isValid()) {
        this.expire_date = DayJs(
          this.indentification.expire_date,
          "DD/MM/YYYY"
        ).format("YYYY-MM-DD");
      }
    },
    updateExpireDateMedicare(){
      if( medicareRules(this.indentification.medicare_expire_date) && mediExpireDate(this.indentification.medicare_expire_date) && this.indentification.type == 3 ){
        // let dateMonth =  this.indentification.medicare_expire_date.split('/');
        // this.expire_date = '04/' + '/' + dateMonth[0] + '/20' + dateMonth[1] ;
        this.expire_date = ApplicationMapper.mapMadecareDateToServer(this.indentification.medicare_expire_date) ;
      }
    }
  },

    computed: {
        isNMIRequired() {
            return !this.isWaterTabFocused && !!(Array.isArray(this.services) && this.services.some(n => n === 'power'));
        },
        isMERNRequired() {
            return !this.isWaterTabFocused && !!(Array.isArray(this.services) && this.services.some(n => n === 'gas'));
        },
        billingAddressMsg() {
            return this.property_details.is_billing_same ? "Same as service address" : this.property_details.billing_address_text;
        },
        tenancyTypeMapper() {
            return tenancyTypeMapper;
        },
        isTenancyHomeOwner() {
            // return this.person_details.tenancy_type===tenancyTypeMapper.HomeOwner;
            return false;
        },
        isWaterTabFocused() {
            return LeadApplicationService.getActiveServiceTab() === 1;
        },
        ...mapGetters(["isInvalidAddress"])
    },

  watch: {
    lead: {
      async handler() {
        this.synFormData();
          await this.synFormData();
          await this.formatDate();
          await this.updateLeads();
      },
      deep: true,
    },


    dob() {
      this.person_details.dob = new DayJs(this.dob).format("DD/MM/YYYY");
      this.$emit("updateDraft", "dob", this.person_details.dob, true, false);
    },

    moving_date() {
      this.property_details.moving_date = new DayJs(this.moving_date).format(
        "DD/MM/YYYY"
      );


      this.$eventBus.$emit("update_temporary_date" , this.moving_date);
      this.$emit(
        "updateDraft",
        "moving_date",
        this.property_details.moving_date,
        true,
        false
      );
    },

    expire_date() {
      if (isNull(this.expire_date) || this.expire_date == '' || this.expire_date == undefined ) return;
      this.indentification.expire_date = new DayJs(this.expire_date).format(
        "DD/MM/YYYY"
      );
      this.$emit(
        "updateDraft",
        "expire_date",
        this.indentification?.expire_date,
        true,
        true
      );
    },

      medicare_expire_date() {
          this.indentification.medicare_expire_date = dayJs(this.medicare_expire_date).format("MM/YY");
          const formatedDate = ApplicationMapper.mapMadecareDateToServer( this.indentification.medicare_expire_date, false);
          this.$emit(
              "updateDraft",
              "expire_date",
              formatedDate,
              true,
              true
          );
      }

  },
  async mounted() {
    // console.log('services', this.services);
    await this.synFormData();
    await this.formatDate();
    await this.updateLeads();

    const update_moving_date = (date)=>{
      this.property_details.moving_date = date;
      this.$emit(
        "updateDraft",
        "moving_date",
        this.property_details.moving_date,
        true,
        false
      );
      this.updateLeads();
    }

    const update_connection_end_date = (date)=>{
      this.property_details.connection_end_date = date;
      this.$emit(
        "updateDraft",
        "connection_end_date",
        this.property_details.connection_end_date,
        true,
        false
      );
      this.updateLeads();
    }

    this.$eventBus.$on("update_moving_date", update_moving_date );
    this.$eventBus.$on("update_connection_end_date", update_connection_end_date );

    this.$once("hook:beforeDestroy", (date) => {
        this.$eventBus.$off("update_moving_date", update_moving_date );
        this.$eventBus.$off("update_connection_end_date", update_connection_end_date );
    });

  },
};
</script>

<style scoped>
.min-height-56 textarea {
    min-height: 132px !important;
}
.required-field {
    border: 2px solid red;
}
</style>
