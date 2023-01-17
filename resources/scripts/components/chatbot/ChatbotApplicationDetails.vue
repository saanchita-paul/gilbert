<template>
    <div  >
        <v-skeleton-loader
            v-bind="skeletonAttribute"
            :type="skeletonType"
            v-if="isLoadSkeleton"
        ></v-skeleton-loader>
        <div v-if="shouldShowExpansionPanel" class="pa-0 id-details-panel">
            <v-row class="pa-5">
                <v-col cols="12">
                    <p style="margin-bottom: unset">Chatbot Application Details</p>
                    <p class="font-weight-bold" style="font-size: 25px; margin-bottom: unset">{{ chatbot_app.personal_details.fullName }}</p>
                    <v-row style="margin-top: unset">
                        <v-col cols="4">
                            <IdCopyToClipboard :applicationId="app_id"></IdCopyToClipboard>
                        </v-col>
                        <v-col cols="8">
                            <p :hidden="shouldShowConnectionType">Temporary Connection</p>
                        </v-col>
                    </v-row>
                </v-col>

            </v-row>

            <v-divider ></v-divider>
            <v-expansion-panels class="overflow-auto" v-model="expansionPanel" @change="handleExpansionPanel" multiple accordion style="box-shadow: none !important; max-height: 53vh;">
                <v-expansion-panel  elevation="0" >
                    <v-expansion-panel-header class="expansion-header">
                        Profile Details
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <ValidationObserver ref="personal_details_ref">
                            <v-row>
                                <v-col cols="5" class="py-0 my-1">
                                    <p class="font-weight-bold">Title</p>
                                </v-col>
                                <v-col cols="7"  class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider name="Title" rules="required" v-slot="{ errors }">
                                        <v-select
                                            outlined
                                            dense
                                            :items="titlesDropDown"
                                            v-model="chatbot_app.personal_details.title"
                                            class="item-value"
                                            item-text="text"
                                            item-value="value"
                                            hide-details="auto"
                                            :error-messages="errors[0]"
                                            @change="personalDetailsChanged('title')"
                                        ></v-select>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col cols="5" class="py-0 my-1">
                                    <p class="font-weight-bold">FirstName</p>
                                </v-col>
                                <v-col cols="7"  class="py-0 my-1">
                                    <div  class="text-field">
                                        <ValidationProvider name="Firstname" rules="required" v-slot="{ errors }">
                                        <v-text-field
                                            v-model="chatbot_app.personal_details.first_name"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            placeholder="First Name"
                                            :error-messages="errors[0]"
                                            @keyup="personalDetailsChanged('first_name')"
                                        ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col cols="5" class="py-0 my-1">
                                    <p class="font-weight-bold">LastName</p>
                                </v-col>
                                <v-col cols="7"  class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider name="Lastname" rules="required" v-slot="{ errors }">
                                        <v-text-field
                                            v-model="chatbot_app.personal_details.last_name"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            placeholder="Last Name"
                                            :error-messages="errors[0]"
                                            @keyup="personalDetailsChanged('last_name')"
                                        ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>

                                <v-col cols="5" class="py-0 my-1">
                                    <p class="font-weight-bold">Email</p>
                                </v-col>
                                <v-col cols="7"  class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider name="Email" rules="required|email" v-slot="{ errors }">
                                            <v-text-field
                                                v-model="chatbot_app.personal_details.email"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="Email"
                                                :error-messages="errors[0]"
                                                @keyup="personalDetailsChanged('email')"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>

                                <v-col cols="5" class="py-0 my-1">
                                    <p class="font-weight-bold">Date of Birth</p>
                                </v-col>
                                <v-col cols="7"  class="py-0 my-1">
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
                                                <ValidationProvider name="Bate Of Birth" rules="required|valid-date" v-slot="{ errors }">
                                                    <v-text-field
                                                        placeholder="DD/MM/YYYY"
                                                        outlined
                                                        dense
                                                        append-icon="mdi-calendar"
                                                        v-model="chatbot_app.personal_details.dob"
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
                                                @change="personalDetailsChanged('dob')"
                                            ></v-date-picker>
                                        </v-menu>
                                    </div>
                                </v-col>

                                <v-col   cols="5"  class="py-0 my-1">
                                    <p class="font-weight-bold"> Mobile </p>
                                </v-col>
                                <v-col  cols="7"  class="py-0 my-1">
                                    <div  class="text-field">
                                        <ValidationProvider name="Mobile" rules="required|cv-phone|length:10" v-slot="{ errors }">
                                            <v-text-field
                                                v-model="chatbot_app.personal_details.phone"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="Mobile"
                                                :error-messages="errors[0]"
                                                @keyup="personalDetailsChanged('phone')"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col cols="5" class="py-0 my-1">
                                    <p class="font-weight-bold">Business Name</p>
                                </v-col>
                                <v-col cols="7"  class="py-0 my-1">
                                    <div  class="text-field">
                                        <ValidationProvider name="Business Name" v-slot="{ errors }">
                                            <v-text-field
                                                v-model="chatbot_app.personal_details.business_name"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="Please type..."
                                                :error-messages="errors[0]"
                                                @keyup="personalDetailsChanged('business_name')"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col cols="5" class="py-0 my-1">
                                    <p class="font-weight-bold">ABN</p>
                                </v-col>
                                <v-col cols="7"  class="py-0 my-1">
                                    <div  class="text-field">
                                        <ValidationProvider name="ABN"  v-slot="{ errors }">
                                            <v-text-field
                                                v-model="chatbot_app.personal_details.abn"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="Please type..."
                                                :error-messages="errors[0]"
                                                @keyup="personalDetailsChanged('abn')"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row class="pa-3 d-flex justify-end" style="gap: 10px" v-if="shouldActivePersonalDetailsAction">
                                <v-btn small @click="cancelPersonalDetails" :loading="cancelPersonalLoading" > Cancel</v-btn>
                                <v-btn color="primary" small right @click="savePersonalDetails"  :loading="savePersonDloading" > Save</v-btn>
                            </v-row>
                        </ValidationObserver>
                    </v-expansion-panel-content>
                </v-expansion-panel>
                <v-divider ></v-divider>

                <v-expansion-panel   elevation="0">
                        <v-expansion-panel-header class="expansion-header">
                            Property Details
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <ValidationObserver ref="property_details_ref">
                                <v-row>
                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p  class="font-weight-bold">Connection Date*</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div  class="text-field">
                                            <ValidationProvider
                                                name="Moving date"
                                                rules="required"
                                                v-slot="{ errors }"
                                            >
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
                                                            name="Moving Date"
                                                            rules="required|valid-date|not-holiday:@h_state"
                                                            v-slot="{ errors }"
                                                        >
                                                            <v-text-field
                                                                placeholder="DD/MM/YYYY"
                                                                outlined
                                                                dense
                                                                append-icon="mdi-calendar"
                                                                v-model="chatbot_app.property_details.moved_at"
                                                                v-bind="attrs"
                                                                :error-messages="errors[0]"
                                                                hide-details="auto"
                                                                @change="updateMovingDatePicker"
                                                            >
                                                                <template slot="append">
                                                                    <v-icon v-on="on">mdi-calendar</v-icon>
                                                                </template>
                                                            </v-text-field>
                                                        </ValidationProvider>
                                                    </template>
                                                    <v-date-picker
                                                        v-model="moved_at"
                                                        @input="showMovingDate = false"
                                                        @change="propertyDetailsChanged('moved_at')"
                                                    ></v-date-picker>
                                                </v-menu>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>
                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">Service Address*</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider
                                                name="Service Address"
                                                rules="required"
                                                v-slot="{ errors }"
                                            >
                                                <v-textarea
                                                    v-model="chatbot_app.property_details.to_address"
                                                    @click="openGbGAddress"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    :error-messages="errors[0]"
                                                    :loading="address_loader"
                                                ></v-textarea>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>
                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">Solar Power*</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider
                                                name="Solar Power"
                                                rules="required"
                                                v-slot="{ errors }"
                                            >
                                                <v-select
                                                    v-model="chatbot_app.property_details.solar_panel"
                                                    :items="solarPowerDD"
                                                    item-text="text"
                                                    item-value="value"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    :error-messages="errors[0]"
                                                    @change="propertyDetailsChanged('solar_panel')"
                                                >
                                                </v-select>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>

                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">NMI (Power)*</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider
                                                name="NMI"
                                                rules="required"
                                                v-slot="{ errors }"
                                            >
                                                <v-text-field
                                                    v-model="chatbot_app.property_details.nmi"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    placeholder="NMI"
                                                    :error-messages="errors[0]"
                                                    @keyup="propertyDetailsChanged('nmi')"
                                                    :loading="nmi_loader"
                                                ></v-text-field>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>
                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">MIRN (Gas) <span v-if="isMIRNRequired">*</span> </p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider
                                                name="MIRN"
                                                :rules="`${isMIRNRequired ? 'required|' : ''}`"
                                                v-slot="{ errors }"
                                            >
                                                <v-text-field
                                                    v-model="chatbot_app.property_details.mirn"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    placeholder="MIRN"
                                                    :error-messages="errors[0]"
                                                    @keyup="propertyDetailsChanged('mirn')"
                                                    :loading="mirn_loader"
                                                ></v-text-field>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>
                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">Renovation going on?</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider
                                                name="Access requirement"
                                                v-slot="{ errors }"
                                            >
                                            <v-select
                                                v-model="chatbot_app.property_details.is_renovation_on"
                                                :items="accessRequirement"
                                                item-text="text"
                                                item-value="value"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="errors[0]"
                                                @change="propertyDetailsChanged('is_renovation_on')"
                                            >
                                            </v-select>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>
                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">Access requirement</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider
                                                name="Access requirement"
                                                v-slot="{ errors }"
                                            >
                                            <v-select
                                                v-model="chatbot_app.property_details.has_access_req"
                                                :items="accessRequirement"
                                                item-text="text"
                                                item-value="value"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="errors[0]"
                                                @change="propertyDetailsChanged('has_access_req')"
                                            >
                                            </v-select>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>

                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">Property Type*</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider
                                                name="Property Type"
                                                rules="required"
                                                v-slot="{ errors }"
                                            >
                                                <v-select
                                                    v-model="chatbot_app.property_details.account_type"
                                                    :items="tenantTypeDD"
                                                    item-text="text"
                                                    item-value="value"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    :error-messages="errors[0]"
                                                    @change="propertyDetailsChanged('account_type')"
                                                >
                                                </v-select>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>

                                </v-row>
                                <v-row class="pa-3 d-flex justify-end" style="gap: 10px" v-if="shouldActivePropertyDetailsAction">
                                    <v-btn small @click="cancelPropertyDetails" :loading="cancelPropertyLoading" > Cancel</v-btn>
                                    <v-btn color="primary" small right @click="savePropertyDetails" :loading="saveProDloading" > Save</v-btn>
                                </v-row>
                            </ValidationObserver>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                <v-divider ></v-divider>

                <v-expansion-panel elevation="0">
                    <v-expansion-panel-header class="expansion-header">
                        Service Preference
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <v-col cols="12" v-if="chatbot_app.eleService">
                                <div  class="my-0 py-0 mx-0 border-all">
                                    <p class="pt-2 pb-1 mb-0 services">
                              <span class="ml-0">
                                  <v-icon  color="yellow" size="17">mdi-flash</v-icon> Power
                              </span>
                                    </p>
                                    <p class="py-0 my-0 service-status" >
                                        <small>Current Status</small>
                                        <v-select @change="changeServiceStatus(chatbot_app.eleService)"
                                                  placeholder="Please select"
                                                  v-model="chatbot_app.eleService.status"
                                                  item-text="text"
                                                  item-value="text"
                                                  :items="powerStatus"
                                                  outlined
                                                  dense
                                                  hide-details="auto"
                                        >
                                        </v-select>
                                    </p>
                                </div>

                                <div class="item" v-if="chatbot_app.eleService.service_type === 'electricity'">
                                    <v-btn v-if="chatbot_app.eleService.status === 'Rejected'"
                                           @click="openRejection(electricityService)"
                                           small
                                           style="height: 25px; min-width: 90px; color: #5c229a; border: 3px solid #5c229a; margin-top: 15px;"
                                           outlined
                                    > Reason </v-btn>
                                </div>
                            </v-col>
                            <v-col cols="12" v-if="chatbot_app.gasService">
                                <div  class="my-0 py-0 mx-0 border-all">
                                    <p class="pt-2 pb-1 mb-0 services">
                              <span class="ml-0">
                                  <v-icon color="red" size="17">mdi-fire</v-icon> Gas
                              </span>
                                    </p>
                                    <p class="py-0 my-0 service-status" >
                                        <small>Current Status</small>
                                        <v-select @change="changeServiceStatus(chatbot_app.gasService)"
                                                  placeholder="Please select"
                                                  v-model="chatbot_app.gasService.status"
                                                  item-text="text"
                                                  item-value="text"
                                                  :items="gasStatus"
                                                  outlined
                                                  dense
                                                  hide-details="auto"
                                        >
                                        </v-select>
                                    </p>
                                </div>
                                <div class="item" v-if="chatbot_app.gasService.service_type === 'gas'">
                                    <v-btn v-if="chatbot_app.gasService.status === 'Rejected'"
                                           @click="openRejection(gasService)"
                                           small
                                           style="height: 25px; min-width: 90px; color: #5c229a; border: 3px solid #5c229a;margin-top: 15px;"
                                           outlined
                                    >Reason</v-btn>
                                </div>
                            </v-col>
                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
                <v-divider></v-divider>

                <v-expansion-panel  elevation="0">
                    <v-expansion-panel-header class="expansion-header">
                        Identification Details
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <ValidationObserver ref="id_details_ref">
                            <v-row>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Identification</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider name="Identification" rules="required" v-slot="{ errors }">
                                            <v-select
                                                v-model="chatbot_app.id_detail.identification_type"
                                                item-text="text"
                                                item-value="value"
                                                :items="idenficationTypeDD"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="errors[0]"
                                                @change="idDetailsChanged('identification_type')"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Card Number</p>
                                </v-col>
                                <v-col v-if="chatbot_app.id_detail.identification_type === 'identity_driving_license'" cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider name="Card Number" rules="required" v-slot="{ errors }">
                                            <v-text-field
                                                v-model="chatbot_app.id_detail.driving_license_number"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="errors[0]"
                                                @keyup="idDetailsChanged('driving_license_number')"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col v-if="chatbot_app.id_detail.identification_type === 'identity_medicare'" cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider name="Card Number" rules="required" v-slot="{ errors }">
                                            <v-text-field
                                                v-model="chatbot_app.id_detail.medicare_card_number"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="errors[0]"
                                                @keyup="idDetailsChanged('medicare_card_number')"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col v-if="chatbot_app.id_detail.identification_type === 'identity_passport'" cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider name="Card Number" rules="required" v-slot="{ errors }">
                                            <v-text-field
                                                v-model="chatbot_app.id_detail.passport_number"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="errors[0]"
                                                @keyup="idDetailsChanged('passport_number')"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Expiry Date</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field" >
                                        <v-menu
                                            v-model="showExpireDate"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <ValidationProvider name="Expiry Date" rules="required|valid-date" v-slot="{ errors }">
                                                    <v-text-field
                                                        placeholder="DD/MM/YYYY"
                                                        outlined
                                                        dense
                                                        v-model="chatbot_app.id_detail.identification_expire_date"
                                                        v-bind="attrs"
                                                        hide-details="auto"
                                                        @change="updateExpireDatePicker"
                                                        :error-messages="errors[0]"
                                                    >
                                                        <template slot="append">
                                                            <v-icon v-on="on">mdi-calendar</v-icon>
                                                        </template>
                                                    </v-text-field>
                                                </ValidationProvider>
                                            </template>
                                            <v-date-picker
                                                v-model="expire_date"
                                                @input="showExpireDate = false"
                                                @change="idDetailsChanged('identification_expire_date')"
                                            ></v-date-picker>
                                        </v-menu>
                                    </div>
                                </v-col>

                                <template v-if="chatbot_app.id_detail.identification_type === 'identity_medicare'" >
                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">Special Number</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider name="Special Number"  v-slot="{ errors }">
                                                <v-select
                                                    v-model="chatbot_app.id_detail.individual_reference_number"
                                                    :items="specialNumberDD"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    :error-messages="errors[0]"
                                                    @change="idDetailsChanged('individual_reference_number')"
                                                >
                                                </v-select>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>
                                </template>

                                <template v-if="chatbot_app.id_detail.identification_type === 'identity_passport'" >
                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">Passport Country</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider name="Passport Country"  v-slot="{ errors }">
                                                <v-text-field
                                                    v-model="chatbot_app.id_detail.passport_country"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    placeholder="Passport Country"
                                                    :error-messages="errors[0]"
                                                    @keyup="idDetailsChanged('passport_country')"
                                                ></v-text-field>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>
                                </template>
                                <template v-if="chatbot_app.id_detail.identification_type === 'identity_medicare'" >
                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">Card Colour</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider name="Card Colour"  v-slot="{ errors }">
                                                <v-select
                                                    v-model="chatbot_app.id_detail.medicare_card_color"
                                                    item-text="text"
                                                    item-value="value"
                                                    :items="colorDD"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    :error-messages="errors[0]"
                                                    @change="idDetailsChanged('medicare_card_color')"
                                                >
                                                </v-select>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>
                                </template>
                                <template v-if="chatbot_app.id_detail.identification_type === 'identity_driving_license'" >
                                    <v-col cols ="5"  class="py-0 my-1">
                                        <p class="font-weight-bold">State</p>
                                    </v-col>
                                    <v-col cols ="7" class="py-0 my-1">
                                        <div class="text-field">
                                            <ValidationProvider name="State"  v-slot="{ errors }">
                                                <v-select
                                                    v-model="chatbot_app.id_detail.driving_license_state"
                                                    item-text="text"
                                                    item-value="value"
                                                    :items="statesDD"
                                                    outlined
                                                    dense
                                                    hide-details="auto"
                                                    :error-messages="errors[0]"
                                                    @change="idDetailsChanged('driving_license_state')"
                                                >
                                                </v-select>
                                            </ValidationProvider>
                                        </div>
                                    </v-col>
                                </template>

                            </v-row>
                            <v-row class="pa-3 d-flex justify-end" style="gap: 10px" v-if="shouldActiveIdDetailsAction">
                                <v-btn small  @click="cancelIdDetails"  :loading="cancelIdLoading"> Cancel</v-btn>
                                <v-btn color="primary" small right @click="saveIdDetails"  :loading="saveIDDloading"> Save</v-btn>
                            </v-row>
                        </ValidationObserver>
                    </v-expansion-panel-content>
                </v-expansion-panel>
                <v-divider></v-divider>

                <v-expansion-panel   elevation="0">
                    <v-expansion-panel-header class="expansion-header">
                        Concession Card
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <ValidationObserver ref="concession_card_ref">
                            <v-row>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Concession Card</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider name="Concession Card"  v-slot="{ errors }">
                                            <v-select
                                                v-model="chatbot_app.concession_details.concession_card_type"
                                                :items="concessionCard"
                                                item-text="text"
                                                item-value="value"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="errors[0]"
                                                @change="concessionDetailsChanged('concession_card_type')"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>
                                </v-col>


                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Card Number*</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider name="Card Number" rules="required" v-slot="{ errors }">
                                            <v-text-field
                                                v-model="chatbot_app.concession_details.concession_card_value"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="Card Number"
                                                :error-messages="errors[0]"
                                                @keyup="concessionDetailsChanged('concession_card_value')"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>


                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Start Date*</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <v-menu
                                        v-model="isConcessionStartDate"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <ValidationProvider
                                                name="Start Date" rules="required"
                                                v-slot="{ errors }"
                                            >
                                                <v-text-field
                                                    placeholder="DD/MM/YYYY"
                                                    outlined
                                                    dense
                                                    append-icon="mdi-calendar"
                                                    v-model="chatbot_app.concession_details.concession_card_start_date"
                                                    v-bind="attrs"
                                                    :error-messages="errors[0]"
                                                    hide-details="auto"
                                                    @change="updateConcessionStartDatePicker"
                                                >
                                                    <template slot="append">
                                                        <v-icon v-on="on">mdi-calendar</v-icon>
                                                    </template>
                                                </v-text-field>
                                            </ValidationProvider>
                                        </template>
                                        <v-date-picker
                                            v-model="concession_start_date"
                                            @input="isConcessionStartDate = false"
                                            @change="concessionDetailsChanged('concession_card_start_date')"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>


                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">End Date</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <v-menu
                                        v-model="isConcessionEndDate"
                                        :close-on-content-click="false"
                                        :nudge-right="40"
                                        transition="scale-transition"
                                        offset-y
                                        min-width="290px"
                                    >
                                        <template v-slot:activator="{ on, attrs }">
                                            <ValidationProvider
                                                name="End Date"
                                                v-slot="{ errors }"
                                            >
                                                <v-text-field
                                                    placeholder="DD/MM/YYYY"
                                                    outlined
                                                    dense
                                                    append-icon="mdi-calendar"
                                                    v-model="chatbot_app.concession_details.concession_end_date"
                                                    v-bind="attrs"
                                                    :error-messages="errors[0]"
                                                    hide-details="auto"
                                                >
                                                    <template slot="append">
                                                        <v-icon v-on="on">mdi-calendar</v-icon>
                                                    </template>
                                                </v-text-field>
                                            </ValidationProvider>
                                        </template>
                                        <v-date-picker
                                            v-model="concession_end_date"
                                            @input="isConcessionEndDate = false"
                                        ></v-date-picker>
                                    </v-menu>
                                </v-col>
                            </v-row>
                            <v-row class="pa-3 d-flex justify-end" style="gap: 10px" v-if="shouldActiveConcessionDetailsAction">
                                <v-btn small @click="cancelConcessionDetails" :loading="cancelConcessionLoading" > Cancel</v-btn>
                                <v-btn color="primary" small right @click="saveConcessionDetails" :loading="saveConcessionLoading"> Save</v-btn>
                            </v-row>
                        </ValidationObserver>
                    </v-expansion-panel-content>
                </v-expansion-panel>
                <v-divider ></v-divider>

                <v-expansion-panel class="expansion-panel-radius" >
                    <v-expansion-panel-header class="expansion-header">
                        Application Notes
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <ChatbotApplicationNote  :applications="chatbot_app.application_notes" ></ChatbotApplicationNote>
                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>

        <GgbService v-if="showGbg" :dialog="showGbg"  :propertyDetails="chatbot_app.property_address" @close="showGbg = false" @saveAddress="saveAddress" :saveButtonLoader="address_loader"></GgbService>

            <RejectionReasonModal v-if="dialog" :dialog="dialog" :service="selectedRejectedService"  @close="onCloseReject" ></RejectionReasonModal>
        </div>

    </div>
</template>

<script>

import DayJs from "dayjs";
const {titlesMapperForDropdownCb} = require("@scripts/data/titleMapper");
import ChatbotApplicationNote from "@scripts/components/chatbot/ChatbotApplicationNote";
import ChatbotApplicationService from "@scripts/services/chatbot/ChatbotApplicationService";
import {isNull, cloneDeep} from "lodash-es";
import CustomerService from "@scripts/services/CustomerService";
import RejectionReasonModal from "@scripts/components/crm/modals/RejectionReasonModal";
import IdCopyToClipboard from "@scripts/components/common/IdCopyToClipboard";
import CHATBOT_APP_DATA from "@scripts/data/constants/CHATBOT_APP_DATA";

import GgbService from "@scripts/components/chatbot/GbgService";import SkeletonLoaderData from "@scripts/data/SkeletonLoaderData";
export default {
    name: "ChatbotApplicationDetails",
    components: {
        GgbService,
        RejectionReasonModal,
        ChatbotApplicationNote,
        IdCopyToClipboard
    },
    data() {
        return {
            dialog: false,
            chatbot_app: null,
            chatbot_app_backup : null,
            app_id: null,
            isProfileEditMode: false,
            specialNumberDD: CHATBOT_APP_DATA.SPECIAL_NUMBER_DD,
            showExpireDate: false,
            expire_date:  null,
            titlesDropDown: titlesMapperForDropdownCb,
            accessRequirement: CHATBOT_APP_DATA.ACCESS_REQUIREMENT,
            solarPowerDD: CHATBOT_APP_DATA.SOLAR_POWER_DD,
            idenficationTypeDD: CHATBOT_APP_DATA.IDENTIFICATION_TYPE_DD,
            colorDD: CHATBOT_APP_DATA.COLOR_DD,
            statesDD: CHATBOT_APP_DATA.STATES_DD,
            tenantTypeDD: CHATBOT_APP_DATA.TENANT_TYPE_DD,
            homeRenovationDD: CHATBOT_APP_DATA.HOME_RENOVATION_DD,
            electricityDD: CHATBOT_APP_DATA.ELECTRICITY_DD,
            formData : CHATBOT_APP_DATA.FORM_DATA,
            powerStatus : CHATBOT_APP_DATA.POWER_STATUS,
            gasStatus : CHATBOT_APP_DATA.GAS_STATUS,
            showDateOfBirth : false,
            dob : null,
            showMovingDate : false,
            moved_at : null,
            expansionPanel : CHATBOT_APP_DATA.EXPANSION_PANEL,
            concessionCard: CHATBOT_APP_DATA.CONCESSION_CARD,
            isConcessionStartDate: false,
            isConcessionEndDate: false,
            concession_start_date: null,
            concession_end_date: null,
            selectedRejectedService : null,
            savePersonDloading: false,
            saveProDloading: false,
            saveIDDloading: false,
            cancelPersonalLoading : false,
            cancelPropertyLoading : false,
            cancelIdLoading : false,
            personalDetailsFlag : [],
            propertyDetailsFlag : [],
            idDetailsFlag : [],
            applicationNoteFlag : [],
            concessionDetailsFlag : [],
            cancelConcessionLoading: false,
            saveConcessionLoading: false,
            showGbg: false,
            sticky : true,
            isLoadSkeleton : false,
            skeletonAttribute: SkeletonLoaderData.attribute,
            skeletonType : SkeletonLoaderData.type,
            address_loader: false,
            nmi_loader: false,
            mirn_loader: false

        }
    },
    computed: {

        electricityService()
        {
            return this.chatbot_app.connection_services.find((dt)=>  {
                return dt.service_type === 'electricity';
            });
        },

        gasService()
        {
            return this.chatbot_app.connection_services.find((dt)=>  {
                return dt.service_type === 'gas';
            });
        },

        shouldActivePersonalDetailsAction(){
            return this.personalDetailsFlag.length > 0;
        },

        shouldActivePropertyDetailsAction(){
            return this.propertyDetailsFlag.length > 0;
        },

        shouldActiveIdDetailsAction(){
            return this.idDetailsFlag.length > 0;
        },

        shouldActiveConcessionDetailsAction(){
            return this.concessionDetailsFlag.length > 0;
        },
        shouldShowExpansionPanel(){
            return this.chatbot_app;
        },
        shouldShowConnectionType(){
            return !(this.chatbot_app.personal_details.connection_type === 'Temporary');
        },
        isMIRNRequired(){
            if(this.chatbot_app.property_details.which_utility === 'electricity_and_gas' || this.chatbot_app.property_details.which_utility === 'gas'){
                return true;
            }
        }


    },
    methods: {
        handleExpansionPanel(){
            if(this.expansionPanel.length > 1){
                this.expansionPanel.shift();
            }
        },
        async savePersonalDetails() {
            if(!await this.validateFormData('personal_details_ref')) return;
            this.savePersonDloading = true;
            await ChatbotApplicationService.updatePersonalDetails(this.app_id, this.chatbot_app.personal_details);
            this.savePersonDloading = false;
            this.$emit("applicationDetailsUpdated");

        },

        async cancelPersonalDetails(){
            this.cancelPersonalLoading = true;
            this.chatbot_app = cloneDeep(this.chatbot_app_backup);
            this.cancelPersonalLoading = false;
            this.personalDetailsFlag = [];
        },

        async savePropertyDetails() {
            if(!await this.validateFormData('property_details_ref')) return;
            this.saveProDloading = true;
            await ChatbotApplicationService.updatePropertyDetails(this.app_id, this.chatbot_app.property_details);
            this.saveProDloading = false;
            this.$emit("applicationDetailsUpdated");
        },

        async cancelPropertyDetails(){
            this.cancelPropertyLoading = true;
            this.chatbot_app = cloneDeep(this.chatbot_app_backup);
            this.cancelPropertyLoading = false;
            this.propertyDetailsFlag = [];
        },

        async saveIdDetails() {
            if(!await this.validateFormData('id_details_ref')) return;
            this.saveIDDloading = true;
            await ChatbotApplicationService.updateIdDetails(this.app_id, this.chatbot_app.id_detail);
            this.saveIDDloading = false;
            this.$emit("applicationDetailsUpdated");
        },

        async cancelIdDetails(){
            this.cancelIdLoading = true;
            this.chatbot_app = cloneDeep(this.chatbot_app_backup);
            this.cancelIdLoading = false;
            this.idDetailsFlag = [];
        },
        async saveConcessionDetails() {
            if(!await this.validateFormData('concession_card_ref')) return;
            this.saveConcessionLoading = true;
            await ChatbotApplicationService.updateConcessionDetails(this.app_id, this.chatbot_app.concession_details);
            this.saveConcessionLoading = false;
            this.$emit("applicationDetailsUpdated");
        },

        async cancelConcessionDetails(){
            this.cancelConcessionLoading = true;
            this.chatbot_app = cloneDeep(this.chatbot_app_backup);
            this.cancelConcessionLoading = false;
            this.concessionDetailsFlag = [];
        },

        async validateFormData(reference){
            return await this.$refs[reference].validate();
        },

        updateExpireDatePicker() {
            if (DayJs(this.chatbot_app.id_detail.identification_expire_date, "DD/MM/YYYY").isValid()) {
                this.expire_date = DayJs(this.chatbot_app.id_detail.identification_expire_date,"DD/MM/YYYY").format("YYYY-MM-DD");
            }
            this.idDetailsChanged('identification_expire_date');
        },

        updateDobPicker() {
            if (DayJs(this.chatbot_app.personal_details.dob, "DD/MM/YYYY").isValid()) {
                this.dob = DayJs(this.chatbot_app.personal_details.dob,"DD/MM/YYYY").format("YYYY-MM-DD");
            }
            this.personalDetailsChanged('dob');
        },

        updateMovingDatePicker() {
            if (DayJs(this.chatbot_app.property_details.moved_at, "DD/MM/YYYY").isValid()) {
                this.moved_at = DayJs(this.chatbot_app.property_details.moved_at,"DD/MM/YYYY").format("YYYY-MM-DD");
            }
            this.propertyDetailsChanged('moved_at');
        },

        updateConcessionStartDatePicker() {
            if (DayJs(this.chatbot_app.concession_details.concession_card_start_date, "DD/MM/YYYY").isValid()) {
                this.concession_card_start_date = DayJs(this.chatbot_app.concession_details.concession_card_start_date,"DD/MM/YYYY").format("YYYY-MM-DD");
            }
            this.concessionDetailsChanged('concession_card_start_date');
        },

        updateConcessionEndDatePicker() {
            if (DayJs(this.chatbot_app.concession_details.concession_card_end_date, "DD/MM/YYYY").isValid()) {
                this.concession_card_end_date = DayJs(this.chatbot_app.concession_details.concession_card_end_date,"DD/MM/YYYY").format("YYYY-MM-DD");
            }
            this.concessionDetailsChanged('concession_card_end_date');
        },

        async loadApplication() {
            this.isLoadSkeleton = true;
            this.chatbot_app =  await CustomerService.getMovingData(this.app_id);
            this.dob = new DayJs(this.chatbot_app.personal_details.dob).format("YYYY-MM-DD");
            //this.moved_at = this.generateInitialDate(this.chatbot_app.property_details.moved_at);
            //this.expire_date = this.generateInitialDate(this.chatbot_app.id_detail.identification_expire_date);
            // this.concession_start_date = this.generateInitialDate(this.chatbot_app.concession_details.concession_card_start_date);
            // this.concession_end_date = this.generateInitialDate(this.chatbot_app.concession_details.concession_card_end_date);
            this.chatbot_app_backup = cloneDeep(this.chatbot_app);
            this.isLoadSkeleton  = false;
        },

        generateInitialDate(date){
            return date ? new DayJs(date).format("YYYY-MM-DD") : null;
        },

        handleNewApplication(){
            const app_id = this.$route.query?.app_id;
            if(this.app_id !== app_id) {
                this.app_id = app_id;
                this.loadApplication();
            }
        },

        onCloseReject() {
            this.dialog = false;
            this.selectedRejectedService = null;
        },

        openRejection(service) {
            this.selectedRejectedService = service;
            this.dialog = true;
        },

        async changeServiceStatus(service) {
            const res  =  await ChatbotApplicationService.saveServiceStatus(service);
        },

        personalDetailsChanged(attribute){
            if(this.chatbot_app.personal_details[attribute] !== this.chatbot_app_backup.personal_details[attribute]){
                if(!this.personalDetailsFlag.includes(attribute))
                    this.personalDetailsFlag.push(attribute);
            }else{
                if(this.personalDetailsFlag.includes(attribute)){
                    let index = this.personalDetailsFlag.indexOf(attribute);
                    this.personalDetailsFlag.splice(index, 1);
                }
            }
        },

        propertyDetailsChanged(attribute){
            if(this.chatbot_app.property_details[attribute] !== this.chatbot_app_backup.property_details[attribute]){
                if(!this.propertyDetailsFlag.includes(attribute))
                    this.propertyDetailsFlag.push(attribute);
            }else{
                if(this.propertyDetailsFlag.includes(attribute)){
                    let index = this.propertyDetailsFlag.indexOf(attribute);
                    this.propertyDetailsFlag.splice(index, 1);
                }
            }
        },

        idDetailsChanged(attribute){
            if(this.chatbot_app.id_detail[attribute] !== this.chatbot_app_backup.id_detail[attribute]){
                if(!this.idDetailsFlag.includes(attribute))
                    this.idDetailsFlag.push(attribute);
            }else{
                if(this.idDetailsFlag.includes(attribute)){
                    let index = this.idDetailsFlag.indexOf(attribute);
                    this.idDetailsFlag.splice(index, 1);
                }
            }
        },
        openGbGAddress() {
            this.showGbg = true;
        },

        async saveAddress(address) {
            this.address_loader = true;
            this.nmi_loader = true;
            this.mirn_loader = true;
            await ChatbotApplicationService.updatePropertyAddress(this.app_id, address);
            await this.loadApplication();
            this.nmi_loader = false;
            this.mirn_loader = false;
            this.address_loader = false;
            this.showGbg = false;
        },

        concessionDetailsChanged(attribute){
            if(this.chatbot_app.concession_details[attribute] !== this.chatbot_app_backup.concession_details[attribute]){
                if(!this.concessionDetailsFlag.includes(attribute))
                    this.concessionDetailsFlag.push(attribute);
            }else{
                if(this.concessionDetailsFlag.includes(attribute)){
                    let index = this.concessionDetailsFlag.indexOf(attribute);
                    this.concessionDetailsFlag.splice(index, 1);
                }
            }
        },


    },
    mounted(){
        this.handleNewApplication()
    },
    watch: {
        expire_date() {
            if (isNull(this.expire_date) || this.expire_date === '' || this.expire_date === undefined ) return;
            this.chatbot_app.id_detail.identification_expire_date = new DayJs(this.expire_date).format(
                "DD/MM/YYYY"
            );
            this.idDetailsChanged('identification_expire_date');
        },
        dob() {
            if (isNull(this.dob) || this.dob === '' || this.dob === undefined ) return;
            this.chatbot_app.personal_details.dob = new DayJs(this.dob).format(
                "DD/MM/YYYY"
            );
            this.personalDetailsChanged('dob');
        },
        moved_at(){
            if (isNull(this.moved_at) || this.moved_at === '' || this.moved_at === undefined ) return;
            this.chatbot_app.property_details.moved_at = new DayJs(this.moved_at).format(
                "DD/MM/YYYY"
            );
            this.propertyDetailsChanged('moved_at')
        },

        concession_start_date() {
            if (isNull(this.concession_start_date) || this.concession_start_date === '' || this.concession_start_date === undefined ) return;
            this.chatbot_app.concession_details.concession_card_start_date = new DayJs(this.concession_start_date).format(
                "DD/MM/YYYY"
            );
            this.concessionDetailsChanged('concession_card_start_date');
        },
        concession_end_date() {
            if (isNull(this.concession_end_date) || this.concession_end_date === '' || this.concession_end_date === undefined ) return;
            this.chatbot_app.concession_details.concession_end_date = new DayJs(this.concession_end_date).format(
                "DD/MM/YYYY"
            );
            this.concessionDetailsChanged('concession_card_end_date');
        },
        '$route': {
            handler() {
                this.handleNewApplication()
            },
            deep : true
        }

    },

};
</script>

<style scoped>
.service-status{
    font-size: 14px !important;
}
.services{
    font-size: 15px !important;
    font-weight: 700;
}
.border-all{
    /* border: 1px solid black; */
    flex-basis: 31%;
}

.heading.col.col-12 {
    padding: 28px;
}

.custom-card-color{
    background-color: #f8f8f8 !important;
}

.expansion-header{
    font-size: 18px;
    font-weight: bold;
}
.v-application .pa-4 {
    padding: 0 20px !important;
}

.id-details-panel{
    background-color: white;
    border-radius: 16px 16px 0px 0px;
}
.expansion-panel-radius{
    border-radius: 0px 0px 16px 16px !important;
}

</style>
