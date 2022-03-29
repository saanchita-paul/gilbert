<template>
    <v-container v-if="user">
        <v-card  class="hood-card new-application">
            <ValidationObserver ref="create_application">
                <v-row>
                    <v-col cols="12 pb-0">
                        <v-btn @click="onCancel"><v-icon left dark>mdi-arrow-left</v-icon>Back to my Dashboard</v-btn>
                        <h3 class="page-title my-5 pt-5">Add a new Application</h3>
                        <p class="sub-title mb-0">Contact Details  <small class="font-weight-thin">Personal details or your applicant.</small>  <small class="font-weight-thin float-right">All fields are mandatory*</small></p>
                    </v-col>

                     <!-- <v-col cols="6" class="py-0">
                                         <ValidationProvider name="Title" rules="required" v-slot="{ errors }">
                                            <v-select
                                                outlined
                                                dense
                                                :items="titlesDD"
                                                v-model="authorized_person.title"
                                                :error-messages="errors[0]"
                                                placeholder="Mr"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                    </v-col> -->

                    <v-col cols="6" class="pb-0">
                        <ValidationProvider name="Title" rules="required" v-slot="{ errors }">
                            <v-select
                                    outlined
                                    dense
                                    :items="titlesDD"
                                    v-model="application.title"
                                    :error-messages="errors[0]"
                                    placeholder="Please choose one"
                            >
                            </v-select>
                        </ValidationProvider>
                    </v-col>

                    <v-col cols="6" class="pb-0">
                        <ValidationProvider name="Firstname" rules="required"  v-slot="{ errors }">
                            <v-text-field
                                label="Firstname*"
                                outlined
                                dense
                                placeholder="Firstname"
                                v-model="application.first_name"
                                :error-messages=" errors[0]"
                            ></v-text-field>
                        </ValidationProvider>
                    </v-col>

                    <v-col cols="6" class="pb-0">
                        <ValidationProvider name="Middlename"  v-slot="{ errors }">
                            <v-text-field
                                label="Middlename"
                                outlined
                                dense
                                placeholder="Middlename"
                                v-model="application.middle_name"
                                :error-messages=" errors[0]"
                            ></v-text-field>
                        </ValidationProvider>
                    </v-col>

                    <v-col cols="6" class="pb-0">
                    <ValidationProvider name="Lastname" rules="required"  v-slot="{ errors }">
                        <v-text-field
                            label="Lastname*"
                            outlined
                            dense
                            placeholder="Lastname*"
                            v-model="application.last_name"
                            :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                    </v-col>
                    <v-col cols="6" class="py-0 mt-3">
                    <ValidationProvider name="Email" rules="required|email"  v-slot="{ errors }">
                        <v-text-field
                            label="Email*"
                            outlined
                            dense
                            placeholder="example@domain.com"
                            v-model="application.email"
                            :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                    </v-col>

                    <v-col cols="6" class="py-0 mt-3">
                        <ValidationProvider name="Phone Types" rules="required"  v-slot="{ errors }">
                            <v-select outlined dense
                                      v-model="application.phone_type"
                                      :items="phone_types"
                                      label="Phone Type*"
                                      :error-messages=" errors[0]"
                                      placeholder="Please Select">
                            </v-select>
                        </ValidationProvider>
                    </v-col>

                    <v-col cols="6" class="py-0" v-if="application.phone_type == 1">
                    <ValidationProvider name="Mobile number" rules="required|cv-phone|length:10"  v-slot="{ errors }">
                        <v-text-field
                            label="Mobile number*"
                            :maxlength="10"
                            outlined
                            dense
                            placeholder="04XX XXX XXX"
                            v-model="application.phone"
                            :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                    </v-col>

                    <v-col cols="6" class="py-0" v-else>
                    <ValidationProvider name="Homephone number" rules="required|cv-phone|length:10"  v-slot="{ errors }">
                        <v-text-field
                            label="Homephone number*"
                            :maxlength="10"
                            outlined
                            dense
                            placeholder="XXXX XXX XXX"
                            v-model="application.homephone"
                            :error-messages=" errors[0]"
                        ></v-text-field>
                    </ValidationProvider>
                    </v-col>

                    <v-col cols="6" class="py-0">
                        <ValidationProvider name="Occupancy Type" rules="required"  v-slot="{ errors }">
                            <v-select outlined dense
                                      v-model="application.tenancy_type"
                                      :items="tenancy_types"
                                      label="Occupancy Type*"
                                      :error-messages=" errors[0]"
                                      placeholder="Please Select">
                            </v-select>
                        </ValidationProvider>
                    </v-col>


                    <v-col cols="6" class="py-0">
                            <v-menu
                                v-model="showDOB"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <ValidationProvider name="Date of Birth" :rules="`${isTenancyHomeOwner?'':'required|'}valid-date|adult`"  v-slot="{ errors }">
                                        <v-text-field
                                            :label="`Date of Birth ${isTenancyHomeOwner?'':'*'}`"
                                            placeholder="DD/MM/YYYY"
                                            outlined
                                            dense
                                            v-model="application.date_of_birth"
                                            v-bind="attrs"
                                            :error-messages=" errors[0]"
                                            @blur="syncDob"
                                        >
                                            <template slot="append">
                                                <v-icon  v-on="on">mdi-calendar</v-icon>
                                            </template>
                                        </v-text-field>
                                    </ValidationProvider>
                                </template>
                                <v-date-picker v-model="dob" @input="showDOB = false"></v-date-picker>
                            </v-menu>

                    </v-col>


                    <v-col cols="12" class="pb-0">
                        <v-row class="my-0 py-0">
                            <v-col class="my-0 py-0">
                                <v-checkbox
                                    v-model="application.is_contacted"
                                    :label="`Applicant consents to be contacted by HOOD`"
                                ></v-checkbox>
                            </v-col>
                        </v-row>
                    </v-col>


                    <v-col cols="12 pb-0">
                        <p class="sub-title mb-3">Identification <small class="font-weight-thin">Applicant’s ID.</small>  </p>
                    </v-col>

                    <v-col cols="6" class="py-0">
                        <ValidationProvider name="Identification Types" :rules="`${isTenancyHomeOwner?'':'required'}`"  v-slot="{ errors }">
                            <v-select outlined dense
                                      v-model="indentification.type"
                                      :items="idenficationTypeDD"
                                      item-text="text"
                                      item-value="value"
                                      :label="`Id Type${isTenancyHomeOwner?'':'*'}`"
                                      :error-messages=" errors[0]"
                                      placeholder="Please select one">
                            </v-select>
                        </ValidationProvider>
                    </v-col>
                    <IdentificationDetail :indentification="indentification"
                                          :isTenancyHomeOwner="isTenancyHomeOwner"
                                          @updateIdentification="updateIdentification">
                    </IdentificationDetail>

                    <v-col cols="12" class="pb-0">
                        <v-row class="my-0 py-0">
                            <v-col class="my-0 py-0">
                                <v-checkbox
                                    v-model="has_authorized"
                                    :label="`Add an authorised person on the account`"
                                ></v-checkbox>
                            </v-col>
                        </v-row>
                        <v-row  v-if="has_authorized">
                            <v-col cols="6">
                                <p class="sub-title mb-0">Authorised Person’s Details <small class="font-weight-thin">Personal details</small></p>
                            </v-col>
                            <v-col cols="6">
                                <p class="sub-title mb-0"><small class="font-weight-thin">All fields marked with * are mandatory</small></p>
                            </v-col>
                        </v-row>
                        <v-row  v-if="has_authorized">


                            <v-col cols="6">
                                <v-row>

                                    <v-col cols="12" class="py-0">
                                         <ValidationProvider name="Title" rules="required" v-slot="{ errors }">
                                            <v-select
                                                outlined
                                                dense
                                                :items="titlesDD"
                                                v-model="authorized_person.title"
                                                :error-messages="errors[0]"
                                                placeholder="Please choose one"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </v-col>

                                    <v-col cols="12" class="py-0">
                                        <ValidationProvider name="Middle Name"  v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                outlined
                                                label="Middle Name"
                                                v-model="authorized_person.middle_name"
                                                dense
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>

                                    <v-col cols="12" class="py-0">
                                        <ValidationProvider name="Email address" rules="required|email"  v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                outlined
                                                label="Email address*"
                                                v-model="authorized_person.email"
                                                dense
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>

                                    <v-col cols="12" class="py-0">
                                        <ValidationProvider name="Date Of Birth" rules="required"  v-slot="{ errors }">
                                            <v-menu
                                                v-model="showAuthoritydob"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="290px"
                                            >
                                                <template v-slot:activator="{ on, attrs }">

                                                    <ValidationProvider name="Date Of Birth" rules="required|valid-date"  v-slot="{ errors }">
                                                        <v-text-field
                                                            label="Date Of Birth*"
                                                            placeholder="DD/MM/YYYY"
                                                            outlined
                                                            dense
                                                            v-model="authorized_person.dob"
                                                            v-bind="attrs"
                                                            :error-messages=" errors[0]"
                                                            @blur="syncMovingDate"
                                                        >

                                                            <template slot="append">
                                                                <v-icon  v-on="on">mdi-calendar</v-icon>
                                                            </template>

                                                        </v-text-field>
                                                    </ValidationProvider>
                                                </template>
                                                <v-date-picker v-model="authorized_person_dob"
                                                               @input="showAuthoritydob = false"></v-date-picker>
                                            </v-menu>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="6">
                                <v-row>
                                    <v-col cols="12" class="py-0">
                                        <ValidationProvider name="First Name" rules="required"  v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                outlined
                                                label="First Name*"
                                                v-model="authorized_person.first_name"
                                                dense
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="12" class="py-0">
                                        <ValidationProvider name="Last Name" rules="required"  v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                outlined
                                                label="Last Name*"
                                                v-model="authorized_person.last_name"
                                                dense

                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>

                                    <v-col cols="12" class="py-0">
                                        <ValidationProvider name="Mobile Number" rules="cv-phone|length:10" v-slot="{ errors }">
                                            <v-text-field
                                                indentification
                                                :error-messages=" errors[0]"
                                                outlined
                                                label="Mobile Number (Optional)"
                                                v-model="authorized_person.phone"
                                                dense
                                                placeholder="+61"

                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>


                                    <v-col cols="12" class="py-0">
                                        <ValidationProvider name="Authorised Person's role" rules="required"  v-slot="{ errors }">
                                            <v-select
                                                outlined dense hide-details="auto"
                                                :items="roles"
                                                item-text="text"
                                                item-value="value"
                                                v-model="authorized_person.role"
                                                :error-messages=" errors[0]"
                                                placeholder="Role">
                                            </v-select>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                            </v-col>
                        </v-row>

                        <v-row  v-if="has_authorized">
                            <v-col cols="12">
                                <p class="sub-title mb-0">Authorised Person’s Identification <small class="font-weight-thin">Personal details</small></p>
                            </v-col>
                        </v-row>
                        <v-row  v-if="has_authorized">
                            <v-col cols="12">
                                <v-row>
                                    <v-col cols="6" class="py-0">
                                        <ValidationProvider name="Title" rules="required" v-slot="{ errors }">
                                            <v-select outlined dense
                                                      v-model="authorized_person.identification_type"
                                                      :items="idenficationTypeDD"
                                                      item-text="text"
                                                      item-value="value"
                                                      :label="`Id Type *`"
                                                      :error-messages=" errors[0]"
                                                      placeholder="Please select one">
                                            </v-select>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col v-if="authorized_person.identification_type === 3" cols="12">
                                <v-row>
                                    <v-col cols="6" class="pb-0">
                                        <ValidationProvider :rules="'required'" name="Medicare Card Number" v-slot="{ errors }">
                                            <v-text-field
                                                :error-messages="errors[0]"
                                                v-model="authorized_person.card_number"
                                                outlined
                                                dense
                                                placeholder="Medicare Card Number"
                                                :label="`Medicare Card Number *`"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="6" class="pb-0">
                                        <ValidationProvider
                                            name="Special Number"
                                            :rules="'required'"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                v-model="authorized_person.special_number"
                                                :error-messages="errors[0]"
                                                :label="'Special Number *'"
                                                placeholder="1/2"
                                                :items="specialNumberDD"
                                                outlined
                                                dense


                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="6" class="pb-0">
                                        <v-menu
                                            v-model="showAuthorizedIDExpire"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <ValidationProvider
                                                    name="Expiry Date"
                                                    :rules="'required|medicare-date|medi-expire'"
                                                    v-slot="{ errors }"
                                                >
                                                    <v-text-field
                                                        placeholder="MM/YY"
                                                        :label="'Expiry Date *'"
                                                        outlined
                                                        dense
                                                        v-model="authorized_person.expire_date"
                                                        v-bind="attrs"
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
                                                @input="showAuthorizedIDExpire = false"
                                                type="month"
                                                :min="minExpiredate"
                                            ></v-date-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col cols="6" class="pb-0">
                                        <ValidationProvider
                                            name="Card Colour"
                                            :rules="'required'"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                v-model="authorized_person.card_color"
                                                placeholder="Yellow"
                                                :label="'Card Colour *'"
                                                item-text="text"
                                                item-value="value"
                                                :items="colorDD"
                                                outlined
                                                dense
                                                :error-messages="errors[0]"
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col v-if="authorized_person.identification_type === 1" cols="12">
                                <v-row>
                                    <v-col cols="6" class="pb-0">
                                        <ValidationProvider :rules="`required`" name="Passport Number" v-slot="{ errors }">
                                            <v-text-field
                                                :error-messages="errors[0]"
                                                v-model="authorized_person.card_number"
                                                outlined
                                                dense
                                                placeholder="Passport Number"
                                                :label="`Passport Number *`"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="6" class="pb-0">
                                        <ValidationProvider :rules="'required'" name="Issuing Country" v-slot="{ errors }">
                                            <v-text-field
                                                :error-messages="errors[0]"
                                                v-model="authorized_person.country"
                                                outlined
                                                dense
                                                placeholder="AUS"
                                                :label="`Issuing Country *`"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="6" class="pb-0">
                                        <v-menu
                                            v-model="showAuthorizedIDExpire"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <ValidationProvider
                                                    name="Expiry Date"
                                                    :rules="`required|valid-date`"
                                                    v-slot="{ errors }"
                                                >
                                                    <v-text-field
                                                        placeholder="DD/MM/YYYY"
                                                        :label="`Expiry Date *`"
                                                        outlined
                                                        dense
                                                        v-model="authorized_person.expire_date"
                                                        v-bind="attrs"
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
                                                @input="showAuthorizedIDExpire = false"
                                            ></v-date-picker>
                                        </v-menu>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col v-if="authorized_person.identification_type === 2" cols="12">
                                <v-row>
                                    <v-col cols="6" class="pb-0">
                                        <ValidationProvider :rules="'required'" name="Driver’s License*" v-slot="{ errors }">
                                            <v-text-field
                                                :error-messages="errors[0]"
                                                v-model="authorized_person.card_number"
                                                outlined
                                                dense
                                                placeholder="License Number"
                                                :label="'Driver’s License *'"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="6" class="pb-0">
                                        <ValidationProvider
                                            name="State"
                                            :rules="'required'"
                                            v-slot="{ errors }"
                                        >
                                            <v-select
                                                :error-messages="errors[0]"
                                                v-model="authorized_person.state"
                                                placeholder="Victoria"
                                                :label="`State *`"
                                                item-text="text"
                                                item-value="value"
                                                :items="states"
                                                outlined
                                                dense
                                            >
                                            </v-select>
                                        </ValidationProvider>
                                    </v-col>
                                    <v-col cols="6" class="pb-0">
                                        <v-menu
                                            v-model="showAuthorizedIDExpire"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <ValidationProvider
                                                    name="Expiry Date"
                                                    :rules="`required`"
                                                    v-slot="{ errors }"
                                                >
                                                    <v-text-field
                                                        placeholder="DD/MM/YYYY"
                                                        :label="`Expiry Date *`"
                                                        outlined
                                                        dense
                                                        v-model="authorized_person.expire_date"
                                                        v-bind="attrs"
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
                                                @input="showAuthorizedIDExpire = false"
                                            ></v-date-picker>
                                        </v-menu>
                                    </v-col>
                                </v-row>
                            </v-col>

                        </v-row>


                    </v-col>


                    <v-col cols="12" class="pb-0">
                        <p class="sub-title mb-0">Moving Details <small class="font-weight-thin">Information about your lead’s move.</small></p>
                    </v-col>

<!-- temporary starts here -->

                     <v-col cols="12" class="pb-0">
                        <v-row class="my-0 py-0">
                            <v-col class="my-0 py-3">
                                <v-checkbox
                                    v-model="application.is_temporary_connection"
                                    :label="`I want to set a temporary power connection for this property.`"
                                ></v-checkbox>
                            </v-col>
                        </v-row>
                    </v-col>


                            <v-col cols="12" class="py-0" v-if="application.is_temporary_connection">
                                <v-row>
                                    <v-col cols="6" class="py-0">
                                        <v-menu
                                            v-model="showMovingDate"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <ValidationProvider name="Connection Start Date*" rules="required|valid-date|not-holiday:@State/Territory"  v-slot="{ errors }">
                                                    <v-text-field
                                                        label="Connection Start Date*"
                                                        placeholder="DD/MM/YYYY"
                                                        outlined
                                                        dense
                                                        v-model="application.moving_date"
                                                        v-bind="attrs"
                                                        :error-messages=" errors[0]"
                                                        @blur="syncMovingDate"
                                                    >
                                                        <template slot="append">
                                                            <v-icon  v-on="on">mdi-calendar</v-icon>
                                                        </template>
                                                    </v-text-field>
                                                </ValidationProvider>
                                            </template>
                                            <v-date-picker v-model="moving_date"
                                                           @input="showMovingDate = false"></v-date-picker>
                                        </v-menu>
                                    </v-col>
                                    <v-col cols="6" class="py-0">
                                        <v-menu
                                            v-model="showConnectionEndDate"
                                            :close-on-content-click="false"
                                            :nudge-right="40"
                                            transition="scale-transition"
                                            offset-y
                                            min-width="290px"
                                        >
                                            <template v-slot:activator="{ on, attrs }">
                                                <ValidationProvider name="Connection End date" rules="valid-date:@State/Territory"  v-slot="{ errors }">
                                                    <v-text-field
                                                        label="Connection End Date"
                                                        placeholder="DD/MM/YYYY"
                                                        outlined
                                                        dense
                                                        v-model="application.connection_end_date"
                                                        v-bind="attrs"
                                                        :error-messages=" errors[0]"
                                                        @blur="syncConnectionEndDate"
                                                    >
                                                        <template slot="append">
                                                            <v-icon  v-on="on">mdi-calendar</v-icon>
                                                        </template>
                                                    </v-text-field>
                                                </ValidationProvider>
                                            </template>
                                            <v-date-picker v-model="connection_end_date" :min="moving_date"
                                                           @input="showConnectionEndDate = false"></v-date-picker>
                                        </v-menu>
                                    </v-col>
                                </v-row>
                            </v-col>
<!-- temporary end here -->

                    <v-col cols="12" class="pb-0" v-if="!application.is_temporary_connection">
                        <v-row>
                            <v-col cols="6" class="py-0">
                                <v-menu
                                    v-model="showMovingDate"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">

                                        <ValidationProvider name="Connection date" rules="required|valid-date|not-holiday:@State/Territory"  v-slot="{ errors }">
                                            <v-text-field
                                                label="Connection Date*"
                                                placeholder="DD/MM/YYYY"
                                                outlined
                                                dense
                                                v-model="application.moving_date"
                                                v-bind="attrs"
                                                :error-messages=" errors[0]"
                                                @blur="syncMovingDate"
                                            >

                                                <template slot="append">
                                                    <v-icon  v-on="on">mdi-calendar</v-icon>
                                                </template>

                                            </v-text-field>
                                        </ValidationProvider>
                                    </template>
                                    <v-date-picker v-model="moving_date" :min="minDate"
                                                   @input="showMovingDate = false"></v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                    </v-col>


                    <template>
                        <v-col cols="12" class="pb-0 mt-2" v-if="!showSearchFields">
                                    <v-menu offset-y v-model="showMenu">
                                        <template v-slot:activator="{ on }">
                                        <ValidationProvider name="Service Address" rules="required"  v-slot="{ errors }">
                                            <v-text-field
                                                label="Search address*"
                                                outlined
                                                dense
                                                @focus="serviceSearchFocusOn"
                                                @blur="serviceSearchFocusOff"
                                                placeholder="Type house address here"
                                                append-icon="mdi-magnify"
                                                :error-messages=" errors[0]"
                                                v-model="application.address_text"
                                                @keyup.native="onStreetChanged"
                                            ></v-text-field>
                                        </ValidationProvider>
                                        </template>
                                        <v-list v-if="searchResult.length">
                                            <v-list-item
                                                v-for="place in searchResult"
                                                :key="place.id"
                                                @click="onAddressSelected(place)"
                                            >
                                                <v-list-item-title v-text="place.address_text">
                                                </v-list-item-title>
                                            </v-list-item>
                                            <v-list-item>
                                            <v-list-item-title >
                                                <div class="mannualAddress" @click="selectMannual"> Enter my address manually </div>
                                            </v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                        </v-col>
                        <v-col cols="12" v-if="showSearchFields">
                            <v-row>
                                <v-col cols="3" class="py-0">
                                    <ValidationProvider name="Unit No"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Unit No"
                                            outlined
                                            dense
                                            :readonly="!application.mannual_address"
                                            placeholder="Unit No"
                                            v-model="application.unit_number"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="3" class="py-0">
                                    <ValidationProvider name="Street No" rules="required"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Street No.*"
                                            outlined
                                            dense
                                            :readonly="!application.mannual_address"
                                            placeholder="2/56, Bradman Drive"
                                            v-model="application.street_number"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="6" class="py-0">
                                    <ValidationProvider name="Street Name" rules="required"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Street Name.*"
                                            outlined
                                            dense
                                            :readonly="!application.mannual_address"
                                            placeholder="2/56, Bradman Drive"
                                            v-model="application.street_name_only"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                 <v-col cols="3" class="py-0">
                                    <ValidationProvider name="Street Type" rules="required"  v-slot="{ errors }">
                                        <v-select outlined dense
                                                  v-model="application.street_type"
                                                  :items="street_type"
                                                  :readonly="!application.mannual_address"
                                                  label="Street Type*"
                                                  :error-messages=" errors[0]"
                                                  placeholder="Please Select">
                                        </v-select>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="6" class="py-0">
                                    <ValidationProvider name="City/Suburb" rules="required"  v-slot="{ errors }">
                                        <v-text-field
                                            label="City/Suburb*"
                                            outlined
                                            dense
                                            :readonly="!application.mannual_address"
                                            placeholder="Sunbury"
                                            v-model="application.city"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="6" class="py-0">
                                    <ValidationProvider name="State/Territory" rules="required"  v-slot="{ errors }">
                                        <v-select outlined dense
                                                  v-model="application.state"
                                                  :items="states"
                                                  :readonly="!application.mannual_address"
                                                  label="State/Territory*"
                                                  :error-messages=" errors[0]"
                                                  placeholder="Please Select">
                                        </v-select>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="6" class="py-0">
                                    <ValidationProvider name="Postcode" rules="required"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Postcode*"
                                            outlined
                                            dense
                                            :readonly="!application.mannual_address"
                                            placeholder="3429"
                                            v-model="application.postcode"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                            </v-row>
                        </v-col>
                    </template>

                    <v-col cols="12" class="py-0" v-if="showSearchFields">
                        <p class="newAddress" @click="newAddress"> <span style="text-decoration: underline;"> I want to search for a new address </span> </p>
                    </v-col>

                    <v-col cols="12" class="py-0" v-if="showSearchFields">
                        <p class="billingAddress" @click="billingAddress">  <v-icon small style="text-decoration: none;  padding-bottom: 4px;"> mdi-plus-circle </v-icon> <span style="text-decoration: underline;"> {{ application.is_billing_same ? 'Add a different billing address' : 'Keep the billing address same as service address' }} </span> </p>
                    </v-col>



                    <!-- billing address starts -->
                    <template v-if="!application.is_billing_same && showSearchFields">
                        <v-col cols="12" class="pb-0 mt-2" v-if="!showSearchFieldsBilling">
                                    <v-menu offset-y v-model="showMenu">
                                        <template v-slot:activator="{ on }">
                                        <ValidationProvider name="Billing Address" rules="required"  v-slot="{ errors }">
                                            <v-text-field
                                                label="Search address"
                                                outlined
                                                dense
                                                @focus="billingSearchFocusOn"
                                                @blur="billingSearchFocusOff"
                                                :error-messages=" errors[0]"
                                                placeholder="Type house address here"
                                                append-icon="mdi-magnify"
                                                v-model="application.billing_address_text"
                                                @keyup.native="onBillingStreetChanged"
                                            ></v-text-field>
                                        </ValidationProvider>
                                        </template>
                                        <v-list v-if="searchResultBilling.length">
                                            <v-list-item
                                                v-for="place in searchResultBilling"
                                                :key="place.id"
                                                @click="onBillingAddressSelected(place)"
                                            >
                                                <v-list-item-title v-text="place.address_text">
                                                </v-list-item-title>
                                            </v-list-item>
                                            <v-list-item>
                                            <v-list-item-title >
                                                <div class="mannualAddress" @click="selectMannualBilling"> Enter my address manually </div>
                                            </v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                        </v-col>
                        <v-col cols="12" v-if="showSearchFieldsBilling && showSearchFields">
                            <v-row>
                                <v-col cols="3" class="py-0">
                                    <ValidationProvider name="Unit No"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Unit No"
                                            outlined
                                            dense
                                            :readonly="!application.billing_mannual_address"
                                            placeholder="Unit No"
                                            v-model="application.billing_unit_number"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="3" class="py-0">
                                    <ValidationProvider name="StreetNo" rules="required"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Street No.*"
                                            outlined
                                            dense
                                            :readonly="!application.billing_mannual_address"
                                            placeholder="2/56, Bradman Drive"
                                            v-model="application.billing_street_number"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="6" class="py-0">
                                    <ValidationProvider name="Street Name" rules="required"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Street Name.*"
                                            outlined
                                            dense
                                            :readonly="!application.billing_mannual_address"
                                            placeholder="2/56, Bradman Drive"
                                            v-model="application.billing_street_name_only"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                 <v-col cols="3" class="py-0">
                                    <ValidationProvider name="Street Type" rules="required"  v-slot="{ errors }">
                                        <v-select outlined dense
                                                  v-model="application.billing_street_type"
                                                  :items="street_type"
                                                  :readonly="!application.billing_mannual_address"
                                                  label="Street Type*"
                                                  :error-messages=" errors[0]"
                                                  placeholder="Please Select">
                                        </v-select>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="6" class="py-0">
                                    <ValidationProvider name="City/Suburb" rules="required"  v-slot="{ errors }">
                                        <v-text-field
                                            label="City/Suburb*"
                                            outlined
                                            dense
                                            :readonly="!application.billing_mannual_address"
                                            placeholder="Sunbury"
                                            v-model="application.billing_city"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="6" class="py-0">
                                    <ValidationProvider name="State/Territory" rules="required"  v-slot="{ errors }">
                                        <v-select outlined dense
                                                  v-model="application.billing_state"
                                                  :items="states"
                                                  :readonly="!application.billing_mannual_address"
                                                  label="State/Territory*"
                                                  :error-messages=" errors[0]"
                                                  placeholder="Please Select">
                                        </v-select>
                                    </ValidationProvider>
                                </v-col>
                                <v-col cols="6" class="py-0">
                                    <ValidationProvider name="Postcode" rules="required"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Postcode*"
                                            outlined
                                            dense
                                            :readonly="!application.billing_mannual_address"
                                            placeholder="3429"
                                            v-model="application.billing_postcode"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </v-col>
                            </v-row>
                        </v-col>
                    </template>

                    <v-col cols="12" class="py-0" v-if="showSearchFieldsBilling && showSearchFields">
                        <p class="newAddress" @click="newAddressBilling"> <span style="text-decoration: underline;"> I want to search for a new address </span> </p>
                    </v-col>


                <!-- billing address ends -->








                    <v-col cols="12" class="pb-0">
                        <p class="sub-title mb-0"> Service Interests </p>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center service-radius" :class="service_types.power ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('power')">
                            <h4 :class="service_types.power ? 'enabled' : 'disabled'">Power</h4>
                            <v-icon :readonly="!service_types.power" color="yellow">mdi-flash</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center service-radius" :class="service_types.gas ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('gas')">
                            <h4 :class="service_types.gas ? 'enabled' : 'disabled'">Gas</h4>
                            <v-icon :readonly="!service_types.gas" color="orange">mdi-fire</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center service-radius" :class="service_types.water ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('water')">
                            <h4 :class="service_types.water ? 'enabled' : 'disabled'">Water</h4>
                            <v-icon :readonly="!service_types.water" color="blue">mdi-water</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center service-radius" :class="service_types.internet ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('internet')">
                            <h4 :class="service_types.internet ? 'enabled' : 'disabled'">Internet</h4>
                            <v-icon :readonly="!service_types.internet" color="#9C27B0">mdi-wifi</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="12" class="pb-0">
                        <p class="sub-title  mt-5">Additional Instructions</p>
                        <v-textarea
                            outlined
                            placeholder="Additional Instructions goes here."
                            v-model="application.additional_instruction"
                        ></v-textarea>
                    </v-col>



                    <v-col cols="12">
                        <div class="d-flex  flex-row-reverse">
                            <v-btn @click="onSubmit" :readonly="isUserActive" :loading="loadSubmit" color="primary">Submit</v-btn>
                            <v-btn @click="onCancel" class="mx-4">Cancel</v-btn>
                        </div>
                    </v-col>
                </v-row>
            </ValidationObserver>
        </v-card>
        <AgentConfirmApplicationModal
            v-if="confirmApplicationModal" :dialog="confirmApplicationModal"
            :application="application"
            :authorisedPerson="authorized_person"
            :identification="indentification"
            :has_authorized="has_authorized"
            @cancelApplicationModal="cancelApplicationModal"
            @saveApplication="saveApplication">
        </AgentConfirmApplicationModal>
        <LeadCreateSuccessfulModal  v-if="createSuccessfulModal" :dialog="createSuccessfulModal" @done="done" :title="title">

        </LeadCreateSuccessfulModal>
    </v-container>
</template>

<script>
import ApplicationSummary from "@scripts/models/crm/ApplicationSummary";
import AgentConfirmApplicationModal from "@scripts/components/crm/modals/agent/AgentConfirmApplicationModal";
import AgentApplicationService from "@scripts/services/crm/AgentApplicationService";
import debounce from 'lodash-es/debounce';
import GoogleMapService from "@scripts/services/GoogleMapService";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import DayJs from "dayjs";
import LeadCreateSuccessfulModal from "@scripts/components/crm/modals/LeadCreateSuccessfulModal";
import {isNull} from "lodash-es";
import {isEmpty} from "lodash-es";
import IdentificationDetail from "@scripts/components/crm/agent/IdentificationDetail";
import IDENTIFICATION from "@scripts/data/constants/IDENTIFICATION";
import { tenancyTypeMapper } from '@scripts/data/ConnectionApplicationMapper';
import { formatDate } from "@scripts/services/others/DateService"

import AuthService from '@scripts/services/AuthService';
import { titlesMapperForDropdown } from  "@scripts/data/titleMapper";
import SPECIAL_NUMBER from "@scripts/data/constants/SPECIAL_NUMBER";
import dayJs from "dayjs";
import MEDICARE_COLOR_DD from "@scripts/data/constants/MEDICARE_COLOR_DD";
import IDENTIFICATION_DD from "@scripts/data/constants/IDENTIFICATION_DD";
import STATES_DD from "@scripts/data/constants/STATES_DD";
import MapService from "@scripts/services/MapService";
import { street_type } from "@scripts/data/constants/StreetType"; 

export default {
    name: "AgentCreateNewApplication",
    components: {
        IdentificationDetail,
        LeadCreateSuccessfulModal,
        AgentConfirmApplicationModal,
    },
    data() {
        return {
            confirmApplicationModal: false,
            application: new ApplicationSummary({}),
            tenancy_types:  [
                {text: 'Renter', value: 1},
                {text: 'Home Owner', value: 2},
            ],
            phone_types:  [
                {text: 'Mobile', value: 1},
                {text: 'Homephone', value: 2},
            ],
            states: STATES_DD,
            showMovingDate: false,
            showDOB: false,
            service_types: {
                power: true,
                gas: true,
                water: true,
                internet: true,
            },
            showMenu: false,
            searchResult: [],
            minDate: LeadApplicationService.getMinConnectionDate(),
            range: null,
            disabledDates: [
              { start: new Date(2021, 0, 2), end: new Date(2021, 9, 19) },
            ],
            dob: (new DayJs((new Date()).setFullYear(2000))).format('YYYY-MM-DD'),
            moving_date: null,
            showAuthoritydob: false,
            createSuccessfulModal: false,
            title: '',
            authorized_person_dob:  (new DayJs((new Date()).setFullYear(2000))).format('YYYY-MM-DD'),
            showConnectionEndDate: null,
            connection_end_date: null,
            expire_date: null,
            minExpiredate: new Date().toISOString(),
            colorDD: MEDICARE_COLOR_DD,
            specialNumberDD: SPECIAL_NUMBER,
            showAuthorizedIDExpire: false,
            authorized_person:{
                title :'',
                first_name :'',
                middle_name :'',
                last_name:'',
                email:'',
                role: '',
                phone: '',
                dob: null,
                identification_type: '',
                card_number: '',
                state: '',
                country: '',
                card_color: '',
                special_number: '',
                expire_date: null,
            },
            showAuthIdExpireDate: false,
            roles:[
                {
                    value: 1,
                    text:'Enquiry Only',
                },
                {
                    value: 2,
                    text:'Fully Authorised',
                },
                {
                    value: 3,
                    text:'Financially Responsible'
                }
            ],
            has_authorized: false,
            titlesDD: titlesMapperForDropdown,
            idenficationTypeDD: IDENTIFICATION_DD,
            indentification: {
                type: IDENTIFICATION.PASSPORT,
                card_number: "",
                special_number: "",
                expire_date: "",
                card_color: "",
                state: "",
                country: "",
            },
            loadSubmit: false,
            user: null,
            showSearchFields: false,
            showSearchFieldsBilling: false,
            isBillingAddressSame: true,
            searchResultBilling: [],
            searchFocus: false,
            billingSearchFocus: false, 
        }

    },
    created() {
        this.onStreetChanged = debounce(() => {
            if (this.application.address_text.length > 0) {
            MapService.getStreetAddressesByKeyword(this.application.address_text)
                .then((data)=>{
                    console.log("search result" , data)
                    this.searchResult = data;
                    this.showMenu = this.searchResult.length > 0
                });
                
            }
        }, 250);

        this.onBillingStreetChanged = debounce(() => {
            if (this.application.billing_address_text.length > 0) {
            MapService.getStreetAddressesByKeyword(this.application.billing_address_text)
                .then((data)=>{
                    console.log("search result" , data)
                    this.searchResultBilling = data;
                    this.showMenu = this.searchResultBilling.length > 0
                });
                
            }
        }, 250);

    },
    computed:{
        street_type(){
            return street_type;
        },
        tenancyTypeMapper(){
            return tenancyTypeMapper;
        },
        isTenancyHomeOwner(){
            return this.application.tenancy_type === tenancyTypeMapper.HomeOwner;
            // return AuthService.getRoles().includes("agency_office_property_manager") &&
            // this.application.tenancy_type===tenancyTypeMapper.HomeOwner;
        },
        isUserActive() {
            return this.user.is_active === 0 ;
        },
    },
    methods: {
        serviceSearchFocusOn(){
            this.searchFocus = true;
        },
        serviceSearchFocusOff(){
            this.searchFocus = false;
        },
        billingSearchFocusOn(){
            this.billingSearchFocus = true;
        },
        billingSearchFocusOff(){
            this.billingSearchFocus = false;
        },
        billingAddress(){
            // this.isBillingAddressSame = !this.isBillingAddressSame;
            this.application.is_billing_same = !this.application.is_billing_same;
        },
        selectAddress(){
            this.showSearchFields = true;
        },
        selectBillingAddress(){
            this.showSearchFieldsBilling = true;
        },
        clearBillingAddress(){
            this.application.billing_address_text = null;
            this.application.billing_street_address = null;
            this.application.billing_city = null;
            this.application.billing_postcode = null;
            this.application.billing_state = null;
            this.application.billing_street_number = null;
            this.application.billing_unit_number = null;
            this.application.billing_street_name_only = null;
            this.application.billing_street_name = null;
            this.application.billing_street_type = null;
            this.application.billing_mannual_address = true;
        },
        selectMannualBilling(){
            this.showSearchFieldsBilling = true;

            this.clearBillingAddress()
        },
        clearServiceAddress(){
            this.application.address_text = null;
            this.application.street_address = null;
            this.application.city = null;
            this.application.postcode = null;
            this.application.state = null;
            this.application.street_number = null;
            this.application.unit_number = null;
            this.application.street_name_only = null;
            this.application.street_name = null;
            this.application.street_type = null;
            this.application.mannual_address = true;
        },
        selectMannual(){
            this.showSearchFields = true;

            this.clearServiceAddress()
        },
        newAddress(){
            this.showSearchFields = false;
            this.application.mannual_address = false;
            this.application.address_text = null;
            this.searchResult = [];
        },
        newAddressBilling(){
            this.showSearchFieldsBilling = false;
            this.application.billing_mannual_address = false;
            this.application.billing_address_text = null;
            this.searchResultBilling = [];
        },
        onAddressSelected(place) {
            console.log("place id" , place)
            this.searchResult = []
            MapService.getAddressDetailsById(place.id)
                .then((data) => {
                    this.application = { ...this.application, ...data }
                    let unit_number = isEmpty(this.application.unit_number) ? "" : this.application.unit_number + " /";
                    this.application.street_address = unit_number + ' ' + this.application.street_number + ' ' + this.application.street_name_only;
                    this.selectAddress();
                });
        },
        onBillingAddressSelected(place) {
            console.log("place id" , place)
            this.searchResultBilling = []
            MapService.getAddressDetailsById(place.id)
                .then((data) => {
                 
                    this.application.billing_unit_number = data.unit_number 
                    this.application.billing_street_number = data.street_number
                    this.application.billing_street_name_only = data.street_name_only
                    this.application.billing_address_text = data.address_text
                    this.application.billing_state = data.state
                    this.application.billing_street_type = data.street_type
                    this.application.billing_street_number = data.street_number
                    this.application.billing_address_unit = data.address_unit
                    this.application.billing_city = data.city
                    this.application.billing_postcode = data.postcode

                    let unit_number = isEmpty(this.application.unit_number) ? "" : this.application.unit_number + " /";
                    this.application.billing_street_address = unit_number + ' ' + this.application.billing_street_number + ' ' + this.application.billing_street_name_only;


                    this.selectBillingAddress();
                });
        },
        onCancel() {
            this.$router.push({name: 'agent.application.dashboard'});
        },
        async onSubmit() {
            this.setAddressTextAndStreetAddress()
            let v = await this.$refs.create_application.validate();
            if (v) {
                this.confirmApplicationModal = true;
            }
            return v;
        },
        cancelApplicationModal() {
            this.confirmApplicationModal = false;
        },
        saveApplication() {
            this.confirmApplicationModal = false;
            this.loadSubmit = true;
            AgentApplicationService.createApplication({
                'application': this.application,
                'authorized_person': this.authorized_person,
                'identification': this.indentification
            })
                .then(res =>  {
                    this.title = res.data.data.first_name + ' ' + res.data.data.last_name;
                    this.createSuccessfulModal = true;
                    // this.$router.push({name: 'agent.application.dashboard'});
                })
        },
        serviceInsert(item) {
            let itemNotExists = this.application.service_interests.indexOf(item) === -1;
            if (itemNotExists) {
                this.application.service_interests.push(item);
                this.service_types[item]= true;
            }
            else {
                this.application.service_interests.splice(this.application.service_interests.indexOf(item), 1);
                this.service_types[item] = false;
            }
        },

        done() {
            this.$router.push({name: 'agent.application.dashboard'});
        },
        syncDob() {
            if(DayJs(this.application.date_of_birth,'DD/MM/YYYY').isValid())
            {
                this.dob = (DayJs(this.application.date_of_birth,'DD/MM/YYYY')).format('YYYY-MM-DD');
                this.authorized_person_dob = (DayJs(this.application.date_of_birth,'DD/MM/YYYY')).format('YYYY-MM-DD');
            }
        },
        syncMovingDate() {
            if(DayJs(this.application.moving_date,'DD/MM/YYYY').isValid())
            {
                this.moving_date = (DayJs(this.application.moving_date,'DD/MM/YYYY')).format('YYYY-MM-DD');
            }
        },
        syncConnectionEndDate() {
            if(DayJs(this.application.connection_end_date,'DD/MM/YYYY').isValid())
            {
                this.connection_end_date = (DayJs(this.application.connection_end_date,'DD/MM/YYYY')).format('YYYY-MM-DD');
            }
        },
        updateIdentification(identification) {
            this.indentification = identification;
        },
        isSecondaryIdMedicare() {
            console.log('identification type', this.authorized_person.identification_type);
            return this.authorized_person.identification_type === IDENTIFICATION.MEDICARE;
        },
      setAddressTextAndStreetAddress()
      {

        //   if(!this.checkIfAddressIsValid())
        //   {
        //     console.log("invalid address");
        //     this.propertyDetails.address_text = "";
        //     return;
        //   }
        if(!this.showSearchFields){
                this.application.address_text = ""
                this.application.billing_address_text = ""
                return;
            }

          let unit_number = isEmpty(this.application.billing_unit_number) ? "" : this.application.billing_unit_number + " /";
          this.application.billing_street_address = unit_number + ' ' + this.application.billing_street_number + ' ' + this.application.billing_street_name_only;
          if(this.application.billing_mannual_address || this.application.billing_address_text == "" )
          {
              let unit_number = isEmpty(this.application.billing_unit_number) ? "" : this.application.billing_unit_number + " /";
              this.application.billing_address_text = unit_number + ' ' + this.application.billing_street_number + ' ' + this.application.billing_street_name_only + ' ' + ' ' + this.application.billing_city + this.application.billing_state + ' ' + this.application.billing_postcode + ' ' + this.application.billing_country;
          }

          if(!this.application.is_billing_same && !this.showSearchFieldsBilling){
              this.application.billing_address_text = ""
          }            

          unit_number = isEmpty(this.application.unit_number) ? "" : this.application.unit_number + " /";
          this.application.street_address = unit_number + ' ' + this.application.street_number + ' ' + this.application.street_name_only;
          if(this.application.mannual_address || this.application.address_text == "" ){
              this.application.address_text = unit_number + ' ' + this.application.street_number + ' ' + this.application.street_name_only + ' ' + this.application.city + ' ' + this.application.state + ' ' + this.application.postcode + ' ' + this.application.country ;
          }
      },
    },
    watch: {
        showSearchFields(value){
            if(!value){
                this.clearServiceAddress()
                this.clearBillingAddress()
            }
        },
        dob() {
            this.application.date_of_birth = (new DayJs(this.dob).format('DD/MM/YYYY'));
        },

        moving_date() {
            this.connection_end_date = null;
            this.application.connection_end_date = null;
            this.application.moving_date = (new DayJs(this.moving_date).format('DD/MM/YYYY'));
        },
        connection_end_date() {
            this.application.connection_end_date = formatDate(this.connection_end_date);
        },
        authorized_person_dob() {
            this.authorized_person.dob = (new DayJs(this.authorized_person_dob).format('DD/MM/YYYY'));
        },

        expire_date() {
            if (isNull(this.expire_date)) return;
            if(this.isSecondaryIdMedicare()) {
                this.authorized_person.expire_date = dayJs(this.expire_date).format("MM/YY");
            } else {
                this.authorized_person.expire_date = dayJs(this.expire_date).format(
                    "DD/MM/YYYY"
                );
            }
        },

    },
    async mounted() {
        this.user = await AuthService.getAuthUser();
    }
};
</script>

<style scoped lang="scss">
    .enabled {
      font-size: 18px;
      font-style: normal;
      font-weight: 400;
      letter-spacing: 0.03em;
      text-align: center;
      color: #FFFFFF;
    }
    .disabled {
      font-size: 18px;
      font-style: normal;
      font-weight: 400;
      letter-spacing: 0.03em;
      text-align: center;
        color: gray;
    }
    .div_enabled {
        border-color: transparent;
        cursor: pointer;
        background: #5C229A ;
    }
    .div_disabled {
        border-color: gray;
        cursor: pointer;
    }
    .service-radius{
      border-radius: 8px !important;
    }

    .mannualAddress{
        font-weight: bold;
        &:hover{
            cursor: pointer;
        }
    }
    .newAddress{
        font-weight: bold;
        &:hover{
            cursor: pointer;
        }
    }
    .billingAddress{
        &:hover{
            cursor: pointer;
        }
    }
</style>
