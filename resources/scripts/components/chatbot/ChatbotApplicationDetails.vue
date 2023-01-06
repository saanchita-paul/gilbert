<template>
    <div v-if="chatbot_app" class="hood-card" style="padding: 0px !important; max-height: 100%;" >

        <v-expansion-panels v-model="expansionPanel" @change="handleExpansionPanel" multiple accordion style="box-shadow: none !important;">
            <v-expansion-panel style="box-shadow: none !important;">
                <v-row style=" padding: 20px">
                    <v-col cols="12">
                        <p style="margin-bottom: unset">Chatbot Application Details</p>
                        <p class="font-weight-bold" style="font-size: 25px; margin-bottom: unset">{{ chatbot_app.personal_details.title.charAt(0).toUpperCase() + chatbot_app.personal_details.title.slice(1) }}. {{ chatbot_app.personal_details.first_name.charAt(0).toUpperCase() + chatbot_app.personal_details.first_name.slice(1) }} {{ chatbot_app.personal_details.last_name.charAt(0).toUpperCase() + chatbot_app.personal_details.last_name.slice(1) }}</p>
                        <IdCopyToClipboard :applicationId="app_id"></IdCopyToClipboard>
                    </v-col>
                </v-row>
                <v-expansion-panel-header style="font-size: 18px; font-weight: bold">
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
                                        ></v-text-field>
                                    </ValidationProvider>
                                </div>
                            </v-col>

                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">Concession Card</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <ValidationProvider name="Concession Card"  v-slot="{ errors }">
                                        <v-select
                                            v-model="chatbot_app.personal_details.concession_card_type"
                                            :items="concessionCard"
                                            item-text="text"
                                            item-value="value"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            :error-messages="errors[0]"
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
                                            v-model="chatbot_app.personal_details.concession_card_number"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            placeholder="Card Number"
                                            :error-messages="errors[0]"
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
                                                v-model="chatbot_app.personal_details.concession_card_start_date"
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
                                        v-model="concession_start_date"
                                        @input="isConcessionStartDate = false"
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
                                                v-model="chatbot_app.personal_details.concession_end_date"
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
                                        ></v-text-field>
                                    </ValidationProvider>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row class="pa-3 d-flex justify-end" style="gap: 10px">
                        <v-btn small @click="cancelPersonalDetails" :loading="cancelPersonalLoading"> Cancel</v-btn>
                        <v-btn color="primary" small right @click="savePersonalDetails"  :loading="savePersonDloading"
                        > Save</v-btn>
                    </v-row>
                    </ValidationObserver>
                </v-expansion-panel-content>
            </v-expansion-panel>
            <v-divider ></v-divider>

            <v-expansion-panel  style="box-shadow:none !important;">
                    <v-expansion-panel-header style="font-size: 18px; font-weight: bold">
                        Property Details
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <ValidationObserver ref="property_details_ref">
                            <v-row>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Property Type</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider
                                            name="Property Type"
                                            rules="required"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                v-model="chatbot_app.property_details.rent"
                                                :items="tenantTypeDD"
                                                item-text="text"
                                                item-value="value"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="errors[0]"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Solar Power</p>
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
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">NMI (Power)</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider
                                            name="NMI"
                                            rules="numeric"
                                            v-slot="{ errors }"
                                        >
                                            <v-text-field
                                                v-model="chatbot_app.property_details.nmi"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="NMI"
                                                :error-messages="errors[0]"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">MIRN (Gas)</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider
                                            name="NMI"
                                            rules="numeric"
                                            v-slot="{ errors }"
                                        >
                                            <v-text-field
                                                v-model="chatbot_app.property_details.mirn"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                placeholder="MIRN"
                                                :error-messages="errors[0]"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Is renovation going on?</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <v-select
                                            v-model="chatbot_app.property_details.is_renovation_on"
                                            :items="accessRequirement"
                                            item-text="text"
                                            item-value="value"
                                            outlined
                                            dense
                                            hide-details="auto"
                                        >
                                        </v-select>
                                    </div>
                                </v-col>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Access requirement</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <v-select
                                            v-model="chatbot_app.property_details.has_access_req"
                                            :items="accessRequirement"
                                            item-text="text"
                                            item-value="value"
                                            outlined
                                            dense
                                            hide-details="auto"
                                        >
                                        </v-select>
                                    </div>
                                </v-col>
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p  class="font-weight-bold">Moving Date</p>
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
                                                            @change="updateDobPicker"
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
                                                ></v-date-picker>
                                            </v-menu>
                                        </ValidationProvider>
                                    </div>
                                </v-col>

                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Service Address:</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <ValidationProvider
                                            name="Service Address"
                                            rules="required"
                                            v-slot="{ errors }"
                                        >
                                            <v-text-field
                                                v-model="chatbot_app.property_details.to_address"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="errors[0]"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                            </v-row>
                            <v-row class="pa-3 d-flex justify-end" style="gap: 10px">
                            <v-btn small @click="cancelPropertyDetails" :loading="cancelPropertyLoading"> Cancel</v-btn>
                            <v-btn color="primary" small right @click="savePropertyDetails" :loading="saveProDloading"> Save</v-btn>
                        </v-row>
                        </ValidationObserver>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            <v-divider ></v-divider>

            <v-expansion-panel style="box-shadow:none !important;">
                <v-expansion-panel-header style="font-size: 18px; font-weight: bold">
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
                                                item-value="text"
                                                :items="statesDD"
                                                outlined
                                                dense
                                                hide-details="auto"
                                                :error-messages="errors[0]"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </div>
                                </v-col>
                            </template>

                        </v-row>
                        <v-row class="pa-3 d-flex justify-end" style="gap: 10px">
                        <v-btn small  @click="cancelIdDetails"  :loading="cancelIdLoading"> Cancel</v-btn>
                        <v-btn color="primary" small right @click="saveIdDetails"  :loading="saveIDDloading"> Save</v-btn>
                    </v-row>
                    </ValidationObserver>
                </v-expansion-panel-content>
            </v-expansion-panel>
            <v-divider></v-divider>

            <v-expansion-panel style="box-shadow: none !important;">
                <v-expansion-panel-header style="font-size: 18px; font-weight: bold">
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
                                <!--                                    <p class="item-title">Electricity</p>-->
                                <!--                                    <p class="item-value">{{ electricityService.status }}</p>-->
                                <v-btn v-if="chatbot_app.eleService.status === 'Rejected'"
                                       @click="openRejection(electricityService)"
                                       small
                                       style="height: 25px; min-width: 90px; color: #5c229a; border: 3px solid #5c229a; margin-top: 15px;"
                                       outlined
                                >
                                    Reason
                                </v-btn>
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
                                <!--                                <p class="item-title">Gas</p>-->
                                <!--                                <p class="item-value">{{ gasService.status }}</p>-->
                                <v-btn v-if="chatbot_app.gasService.status === 'Rejected'"
                                       @click="openRejection(gasService)"
                                       small
                                       style="height: 25px; min-width: 90px; color: #5c229a; border: 3px solid #5c229a;margin-top: 15px;"
                                       outlined
                                >
                                    Reason
                                </v-btn>

                            </div>
                        </v-col>


                    </v-row>
                </v-expansion-panel-content>
            </v-expansion-panel>
            <v-divider></v-divider>

            <v-expansion-panel>
                <v-expansion-panel-header style="font-size: 18px; font-weight: bold">
                    Application Notes
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                    <v-row>
                        <ChatbotApplicationNote @newNote="loadApplication"  :applications="chatbot_app.application_notes" ></ChatbotApplicationNote>
                    </v-row>
                </v-expansion-panel-content>
            </v-expansion-panel>
        </v-expansion-panels>

        <RejectionReasonModal v-if="dialog" :dialog="dialog" :service="selectedRejectedService"  @close="onCloseReject" ></RejectionReasonModal>

    </div>
</template>

<script>

import DayJs from "dayjs";
const {titlesMapperForDropdownCb} = require("@scripts/data/titleMapper");
import ChatbotApplicationNote from "@scripts/components/chatbot/ChatbotApplicationNote";
import ChatbotApplicationService from "@scripts/services/chatbot/ChatbotApplicationService";
import {isNull} from "lodash-es";
import CustomerService from "@scripts/services/CustomerService";
import RejectionReasonModal from "@scripts/components/crm/modals/RejectionReasonModal";
import IdCopyToClipboard from "@scripts/components/common/IdCopyToClipboard";
import CHATBOT_APP_DATA from "@scripts/data/constants/CHATBOT_APP_DATA";
export default {
    name: "ChatbotApplicationDetails",
    components: {
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
            expire_date:  '',
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
            cancelIdLoading : false
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

        },

        async cancelPersonalDetails(){
            this.cancelPersonalLoading = true;
            await ChatbotApplicationService.updatePersonalDetails(this.app_id, this.chatbot_app_backup.personal_details);
            this.chatbot_app = JSON.parse(JSON.stringify(this.chatbot_app_backup));
            this.cancelPersonalLoading = false;
        },

        async savePropertyDetails() {
            if(!await this.validateFormData('property_details_ref')) return;
            this.saveProDloading = true;
            await ChatbotApplicationService.updatePropertyDetails(this.app_id, this.chatbot_app.property_details);
            this.saveProDloading = false;
        },

        async cancelPropertyDetails(){
            this.cancelPropertyLoading = true;
            await ChatbotApplicationService.updatePropertyDetails(this.app_id, this.chatbot_app_backup.property_details);
            this.chatbot_app = JSON.parse(JSON.stringify(this.chatbot_app_backup));
            this.cancelPropertyLoading = false;
        },

        async saveIdDetails() {
            if(!await this.validateFormData('id_details_ref')) return;
            this.saveIDDloading = true;
            await ChatbotApplicationService.updateIdDetails(this.app_id, this.chatbot_app.id_detail);
            this.saveIDDloading = false;
        },

        async cancelIdDetails(){
            this.cancelIdLoading = true;
            await ChatbotApplicationService.updateIdDetails(this.app_id, this.chatbot_app_backup.id_detail);
            this.chatbot_app = JSON.parse(JSON.stringify(this.chatbot_app_backup));
            this.cancelIdLoading = false;
        },

        async validateFormData(reference){
            return await this.$refs[reference].validate();
        },

        propertyDetails(){
            this.isProfileEditMode = false;
        },
        updateExpireDatePicker() {
            if (DayJs(this.application.expire_date, "DD/MM/YYYY").isValid()) {
                this.expire_date = DayJs(
                    this.this.application.expire_date,
                    "DD/MM/YYYY"
                ).format("YYYY-MM-DD");
            }
        },
        updateDobPicker() {
            if (DayJs(this.application.dob, "DD/MM/YYYY").isValid()) {
                this.chatbot_app.personal_details.dob = DayJs(
                    this.this.application.dob,
                    "DD/MM/YYYY"
                ).format("YYYY-MM-DD");
            }
        },
        async loadApplication() {
           this.chatbot_app =  await CustomerService.getMovingData(this.app_id);
           this.chatbot_app_backup = JSON.parse(JSON.stringify(this.chatbot_app));
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
        }
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
        },
        dob() {
            if (isNull(this.dob) || this.dob === '' || this.dob === undefined ) return;
            this.chatbot_app.personal_details.dob = new DayJs(this.dob).format(
                "DD/MM/YYYY"
            );
        },
        moved_at(){
            if (isNull(this.moved_at) || this.moved_at === '' || this.moved_at === undefined ) return;
            this.chatbot_app.property_details.moved_at = new DayJs(this.moved_at).format(
                "DD/MM/YYYY"
            );
        },

        concession_start_date() {
            if (isNull(this.concession_start_date) || this.concession_start_date === '' || this.concession_start_date === undefined ) return;
            this.chatbot_app.personal_details.concession_card_start_date = new DayJs(this.concession_start_date).format(
                "DD/MM/YYYY"
            );
        },
        concession_end_date() {
            if (isNull(this.concession_end_date) || this.concession_end_date === '' || this.concession_end_date === undefined ) return;
            this.chatbot_app.personal_details.concession_end_date = new DayJs(this.concession_end_date).format(
                "DD/MM/YYYY"
            );
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

</style>
