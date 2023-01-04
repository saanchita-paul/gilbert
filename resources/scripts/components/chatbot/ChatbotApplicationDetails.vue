<template>
    <div v-if="chatbot_app" class="hood-card" style="padding: 0px !important; max-height: 100%;" >
        <template >
            <v-expansion-panels  v-model="expansionPanel.profile" multiple style="box-shadow: none !important;">
                <v-expansion-panel  style="box-shadow: none !important;">
                    <v-expansion-panel-header style="font-size: 18px; font-weight: bold">
                        Profile Details
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <v-col cols="5" class="py-0 my-1">
                                <p class="font-weight-bold">Title</p>
                            </v-col>
                            <v-col cols="7"  class="py-0 my-1">
                                <div class="text-field">

                                    <v-select
                                        outlined
                                        dense
                                        :items="titlesDropDown"
                                        v-model="chatbot_app.personal_details.title"
                                        class="mr-2 item-value"
                                    ></v-select>
                                </div>


                            </v-col>
                            <v-col cols="5" class="py-0 my-1">
                                <p class="font-weight-bold">FirstName</p>
                            </v-col>
                            <v-col cols="7"  class="py-0 my-1">
                                <div  class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.personal_details.first_name"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
                                </div>
                            </v-col>
                            <v-col cols="5" class="py-0 my-1">
                                <p class="font-weight-bold">LastName</p>
                            </v-col>
                            <v-col cols="7"  class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.personal_details.last_name"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
                                </div>
                            </v-col>

                            <v-col cols="5" class="py-0 my-1">
                                <p class="font-weight-bold">Email</p>
                            </v-col>
                            <v-col cols="7"  class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.personal_details.email"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
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
                                            <ValidationProvider
                                                name="Bate Of Birth"

                                                v-slot="{ errors }"
                                            >
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
                                    <v-text-field
                                        v-model="chatbot_app.personal_details.phone"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
                                </div>
                            </v-col>

                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">Concession Card</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-select
                                        v-model="chatbot_app.personal_details.concession_card_type"
                                        :items="concessionCard"
                                        item-text="text"
                                        item-value="value"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        clearable
                                    >
                                    </v-select>
                                </div>
                            </v-col>


                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">Card Number</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.personal_details.concession_card_number"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                </div>
                            </v-col>


                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">Card Start Date</p>
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
                                            name="Start Date"
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
                                <p class="font-weight-bold">Card End Date</p>
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
                                            name="Start Date"
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

                        </v-row>
                        <v-row>
                            <v-col cols="9">

                            </v-col>
                            <v-col cols="3">
                                <v-btn small right @click="savePersonalDetails"  :loading="savePersonDloading"
                                       :disabled="isloading"> Save</v-btn>
                            </v-col>

                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </template>

        <v-divider ></v-divider>

        <template>
            <v-expansion-panels  v-model="expansionPanel.property" multiple>
                <v-expansion-panel  style="box-shadow:none !important;">
                    <v-expansion-panel-header style="font-size: 18px; font-weight: bold">
                        Property Details
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">Property Type</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-select
                                        v-model="chatbot_app.property_details.rent"
                                        :items="tenantTypeDD"
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
                                <p class="font-weight-bold">Solar Power</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-select
                                        v-model="chatbot_app.property_details.solar_panel"
                                        :items="solarPowerDD"
                                        item-text="text"
                                        item-value="value"
                                        outlined
                                        dense
                                        hide-details="auto">
                                    </v-select>
                                </div>
                            </v-col>
                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">NMI (Power)</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.property_details.nmi"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
                                </div>
                            </v-col>
                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">MIRN (Gas)</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.property_details.mirn"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
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
                                                name="Bate Of Birth"
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
                                </div>
                            </v-col>

                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">Service Address:</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.property_details.to_address"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="9">

                            </v-col>
                            <v-col cols="3">
                                <v-btn small right @click="savePropertyDetails" :loading="saveProDloading"> Save</v-btn>
                            </v-col>

                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </template>

        <v-divider></v-divider>

        <template>
            <v-expansion-panels  v-model="expansionPanel.identification" multiple>
                <v-expansion-panel style="box-shadow:none !important;">
                    <v-expansion-panel-header style="font-size: 18px; font-weight: bold">
                        Identification Details
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">ID Type</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-select
                                        v-model="chatbot_app.id_detail.identification_type"
                                        item-text="text"
                                        item-value="value"
                                        :items="idenficationTypeDD"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    >
                                    </v-select>
                                </div>
                            </v-col>
                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">ID Number</p>
                            </v-col>
                            <v-col v-if="chatbot_app.id_detail.identification_type === 'identity_driving_license'" cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.id_detail.driving_license_number"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                </div>
                            </v-col>
                            <v-col v-if="chatbot_app.id_detail.identification_type === 'identity_medicare'" cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.id_detail.medicare_card_number"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                </div>
                            </v-col>
                            <v-col v-if="chatbot_app.id_detail.identification_type === 'identity_passport'" cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.id_detail.passport_number"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    ></v-text-field>
                                </div>
                            </v-col>
                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">ID Expire Date</p>
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
                                                <v-text-field
                                                    placeholder="DD/MM/YYYY"
                                                    outlined
                                                    dense
                                                    v-model="chatbot_app.id_detail.identification_expire_date"
                                                    v-bind="attrs"
                                                    hide-details="auto"
                                                    @change="updateExpireDatePicker"
                                                >
                                                    <template slot="append">
                                                        <v-icon v-on="on">mdi-calendar</v-icon>
                                                    </template>
                                                </v-text-field>
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
                                <p class="font-weight-bold">ID Special Number</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-select
                                        v-model="chatbot_app.id_detail.individual_reference_number"
                                        :items="specialNumberDD"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    >
                                    </v-select>
                                </div>
                            </v-col>
                            </template>

                            <template v-if="chatbot_app.id_detail.identification_type === 'identity_passport'" >
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Passport Country</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <v-text-field
                                            v-model="chatbot_app.id_detail.passport_country"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            placeholder="Card Number"
                                        ></v-text-field>
                                    </div>
                                </v-col>
                            </template>
                            <template v-if="chatbot_app.id_detail.identification_type === 'identity_medicare'" >
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">Card Color</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <v-select
                                            v-model="chatbot_app.id_detail.medicare_card_color"
                                            item-text="text"
                                            item-value="value"
                                            :items="colorDD"
                                            outlined
                                            dense
                                            hide-details="auto"
                                        >
                                        </v-select>
                                    </div>
                                </v-col>
                            </template>
                            <template v-if="chatbot_app.id_detail.identification_type === 'identity_driving_license'" >
                                <v-col cols ="5"  class="py-0 my-1">
                                    <p class="font-weight-bold">DL</p>
                                </v-col>
                                <v-col cols ="7" class="py-0 my-1">
                                    <div class="text-field">
                                        <v-select
                                            v-model="chatbot_app.id_detail.driving_license_state"
                                            item-text="text"
                                            item-value="text"
                                            :items="statesDD"
                                            outlined
                                            dense
                                            hide-details="auto"
                                        >
                                        </v-select>
                                    </div>
                                </v-col>
                            </template>

                        </v-row>
                        <v-row>
                            <v-col cols="9">

                            </v-col>
                            <v-col cols="3">
                                <v-btn small right @click=" saveIdDetails"  :loading="saveIDDloading"> Save</v-btn>
                            </v-col>

                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </template>

        <v-divider></v-divider>

        <template >
            <v-expansion-panels v-model="expansionPanel.service" multiple style="box-shadow: none !important;">
                <v-expansion-panel style="box-shadow: none !important;">
                    <v-expansion-panel-header style="font-size: 18px; font-weight: bold">
                        Service Preference
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <v-col cols="12">
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
                            <v-col cols="12">
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
            </v-expansion-panels>
        </template>

        <v-divider ></v-divider>

        <template>
            <v-expansion-panels v-model="expansionPanel.application" multiple style="box-shadow:none !important;">
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
        </template>
        <RejectionReasonModal v-if="dialog" :dialog="dialog"
                              :service="selectedRejectedService"  @close="onCloseReject" ></RejectionReasonModal>

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
export default {
    name: "ChatbotApplicationDetails",
    components: {
        RejectionReasonModal,
        ChatbotApplicationNote,
    },
    data() {
        return {
            isloading: false,
            dialog: false,
            chatbot_app: null,
            app_id: null,
            isProfileEditMode: false,
            specialNumberDD: [1,2,3,4,5,6,7,8],
            showExpireDate: false,
            expire_date:  '',
            titlesDropDown: titlesMapperForDropdownCb,
            accessRequirement: [
                {
                    text: "Yes",
                    value: 1,
                },
                {
                    text: "No",
                    value: 0,
                },
            ],
            solarPowerDD: [
                {
                    text: "Yes",
                    value: 'solar',
                },
                {
                    text: "No",
                    value: "no_solar",
                },
            ],
            idenficationTypeDD: [
                {
                    text: "Passport",
                    value: 'identity_passport',
                },
                {
                    text: "Driver's License",
                    value: 'identity_driving_license',
                },
                {
                    text: "Medicare Card",
                    value: 'identity_medicare',
                },
            ],
            colorDD: [
                {
                    text: "Green",
                    value: "green",
                },
                {
                    text: "Blue",
                    value: "blue",
                },
                {
                    text: "Yellow",
                    value: "yellow",
                },
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
                    value: "1",
                },
                {
                    text: "Owner",
                    value: "0",
                },
                {
                    text: "Rea Partner",
                    value: "2",
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
            formData : {
                power_status: null,
                gas_status: null,
            },
            powerStatus : [
                {
                    id: 13,
                    type: "service",
                    display_text: "Accepted",
                    display_text_alias: "Connected",
                    status_value: 5,
                    text: "Accepted",
                    value: 5
                },
                {
                    id: 15,
                    type: "service",
                    display_text: "Not Submitted",
                    display_text_alias: "In progress",
                    status_value: 7,
                    text: "Not Submitted",
                    value: 7
                },
                {
                    id: 17,
                    type: "service",
                    display_text: "Rejected",
                    display_text_alias: "Rejected",
                    status_value: 9,
                    text: "Rejected",
                    value: 9
                },
                {
                    id: 19,
                    type: "service",
                    display_text: "Manual Processing",
                    display_text_alias: "Manual Processing",
                    status_value: 11,
                    text: "Manual Processing",
                    value: 11
                },
                {
                    id: 20,
                    type: "service",
                    display_text: "In Progress",
                    display_text_alias: "In Progress",
                    status_value: 12,
                    text: "In Progress",
                    value: 12
                }
            ],
            gasStatus : [
                {
                    id: 13,
                    type: "service",
                    display_text: "Accepted",
                    display_text_alias: "Connected",
                    status_value: 5,
                    text: "Accepted",
                    value: 5
                },
                {
                    id: 15,
                    type: "service",
                    display_text: "Not Submitted",
                    display_text_alias: "In progress",
                    status_value: 7,
                    text: "Not Submitted",
                    value: 7
                },
                {
                    id: 17,
                    type: "service",
                    display_text: "Rejected",
                    display_text_alias: "Rejected",
                    status_value: 9,
                    text: "Rejected",
                    value: 9
                },
                {
                    id: 19,
                    type: "service",
                    display_text: "Manual Processing",
                    display_text_alias: "Manual Processing",
                    status_value: 11,
                    text: "Manual Processing",
                    value: 11
                },
                {
                    id: 20,
                    type: "service",
                    display_text: "In Progress",
                    display_text_alias: "In Progress",
                    status_value: 12,
                    text: "In Progress",
                    value: 12
                }
            ],
            showDateOfBirth : false,
            dob : null,
            showMovingDate : false,
            moved_at : null,
            expansionPanel : {
                profile: [0],
                property: [],
                identification: [],
                service : [],
                application : [],
                concession: []
            },
            concessionCard: [
                {
                    text: "DVA Health",
                    value: "1",
                },
                {
                    text: "Health Care Card",
                    value: "2",
                },
                {
                    text: "Pensioner Concession",
                    value: "3",
                },
                {
                    text: "Queensland Seniors",
                    value: "4",
                },
            ],
            isConcessionStartDate: false,
            isConcessionEndDate: false,
            concession_start_date: null,
            concession_end_date: null,
            selectedRejectedService : null,
            savePersonDloading: false,
            saveProDloading: false,
            saveIDDloading: false,

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

        async savePersonalDetails() {
            this.savePersonDloading = true;
            await ChatbotApplicationService.updatePersonalDetails(this.app_id, this.chatbot_app.personal_details);
            this.savePersonDloading = false;

        },

        async savePropertyDetails() {
            this.saveProDloading = true;
            await ChatbotApplicationService.updatePropertyDetails(this.app_id, this.chatbot_app.property_details);
            this.saveProDloading = false;
        },

        async saveIdDetails() {
            this.saveIDDloading = true;
            await ChatbotApplicationService.updateIdDetails(this.app_id, this.chatbot_app.id_detail);
            this.saveIDDloading = true;
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
        },

        handleNewApplication(){
            const app_id = this.$route.query?.app_id;
            if(this.app_id !== app_id) {
                this.app_id = app_id;
                this.loadApplication();
            }
        },
        updateExpansionPanel(){
            if("profile" in this.$route.query){
                this.expansionPanel.profile = [0]
            }

            if("property" in this.$route.query){
                this.expansionPanel.property = [0]
            }

            if("identification" in this.$route.query){
                this.expansionPanel.identification = [0]
            }

            if("service" in this.$route.query){
                this.expansionPanel.service = [0]
            }

            if("application" in this.$route.query){
                this.expansionPanel.application = [0]
            }
        },
        handleExpansionPanel(value, query){
            //for personal details
            if(value.profile.length > 0){
                this.$router.replace({query: {...query, profile:'true'}});
            }else if(query.profile){
                delete query.profile
                this.$router.replace({query: {...query}});
            }

            // for property details
            if(value.property.length > 0){
                this.$router.replace({query: {...query, property:'true'}});
            }else if(query.property){
                delete query.property;
                this.$router.replace({query: {...query}});
            }

            //for identification details
            if(value.identification.length > 0){
                this.$router.replace({query: {...query, identification:'true'}});
            }else if(query.identification){
                delete query.identification;
                this.$router.replace({query: {...query}});
            }

            //for service details
            if(value.service.length > 0){
                this.$router.replace({query: {...query, service:'true'}});
            }else if(query.service){
                delete query.service;
                this.$router.replace({query: {...query}});
            }

            //for application details
            if(value.application.length > 0){
                this.$router.replace({query: {...query, application:'true'}});
            }else if(query.application){
                delete query.application;
                this.$router.replace({query: {...query}});
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
        this.updateExpansionPanel()
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

        expansionPanel:{
            handler(newValue){
                const query = Object.assign({}, this.$route.query);
                this.handleExpansionPanel(newValue, query)
            },
            deep : true
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

</style>
