<template>
    <v-container>
        <v-card  class="hood-card new-application">
            <ValidationObserver ref="create_application">
                <v-row>
                    <v-col cols="12 pb-0">
                        <v-btn @click="onCancel"><v-icon left dark>mdi-arrow-left</v-icon>Back to my Dashboard</v-btn>
                        <h3 class="page-title my-5 pt-5">Add a new Application</h3>
                        <p class="sub-title mb-0">Contact Details  <small class="font-weight-thin">Personal details or your applicant.</small>  <small class="font-weight-thin float-right">All fields are mandatory*</small></p>
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
                        <ValidationProvider name="Middlename" rules="required"  v-slot="{ errors }">
                            <v-text-field
                                label="Middlename*"
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
                    <v-col cols="6" class="py-0">
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
                    <v-col cols="6" class="py-0">
                        <ValidationProvider name="Tenancy Types" rules="required"  v-slot="{ errors }">
                            <v-select outlined dense
                                      v-model="application.tenancy_type"
                                      :items="tenancy_types"
                                      label="Tenancy Type*"
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
                                    <ValidationProvider name="Date of Birth" rules="required|valid-date|adult"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Date of Birth*"
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
                        <p class="sub-title mb-0">Moving Details <small class="font-weight-thin">Information about your lead’s move.</small></p>
                    </v-col>

                    <v-col cols="12" class="pb-0 mt-2">
                                <v-menu offset-y v-model="showMenu">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            label="Search address"
                                            outlined
                                            dense
                                            placeholder="Type house address here"
                                            append-icon="mdi-magnify"
                                            v-model="application.address_text"
                                            @keyup.native="onStreetChanged"
                                        ></v-text-field>
                                    </template>
                                    <v-list>
                                        <v-list-item
                                            v-for="place in searchResult"
                                            :key="place.place_id"
                                            @click="onAddressSelected(place)"
                                        >
                                            <v-list-item-title v-text="place.description">
                                            </v-list-item-title>
                                        </v-list-item>
                                    </v-list>
                                </v-menu>
                    </v-col>

                    <v-col cols="6" class="py-0">
                        <ValidationProvider name="Address" rules="required"  v-slot="{ errors }">
                            <v-text-field
                                label="Address*"
                                outlined
                                dense
                                placeholder="2/56, Bradman Drive"
                                v-model="application.street_address"
                                :error-messages=" errors[0]"
                            ></v-text-field>
                        </ValidationProvider>

                    </v-col>

                    <v-col cols="6" class="py-0">
                        <ValidationProvider name="City/Suburb" rules="required"  v-slot="{ errors }">
                            <v-text-field
                                label="City/Suburb*"
                                outlined
                                dense
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
                                placeholder="3429"
                                v-model="application.postcode"
                                :error-messages=" errors[0]"
                            ></v-text-field>
                        </ValidationProvider>
                    </v-col>


                    <v-col v-if="application.state" cols="12" class="pb-0">
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

                                        <ValidationProvider name="Moving Date" rules="required|valid-date|not-holiday:@State/Territory"  v-slot="{ errors }">
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


                    <v-col cols="12" class="pb-0">
                        <p class="sub-title mb-0">Service Interests</p>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center service-radius" :class="service_types.power ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('power')">
                            <h4 :class="service_types.power ? 'enabled' : 'disabled'">Power</h4>
                            <v-icon :disabled="!service_types.power" color="yellow">mdi-flash</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center service-radius" :class="service_types.gas ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('gas')">
                            <h4 :class="service_types.gas ? 'enabled' : 'disabled'">Gas</h4>
                            <v-icon :disabled="!service_types.gas" color="orange">mdi-fire</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center service-radius" :class="service_types.water ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('water')">
                            <h4 :class="service_types.water ? 'enabled' : 'disabled'">Water</h4>
                            <v-icon :disabled="!service_types.water" color="blue">mdi-water</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center service-radius" :class="service_types.internet ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('internet')">
                            <h4 :class="service_types.internet ? 'enabled' : 'disabled'">Internet</h4>
                            <v-icon :disabled="!service_types.internet" color="#9C27B0">mdi-wifi</v-icon>
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
                            <v-btn @click="onSubmit" color="primary">Submit</v-btn>
                            <v-btn @click="onCancel" class="mx-4">Cancel</v-btn>
                        </div>
                    </v-col>
                </v-row>
            </ValidationObserver>
        </v-card>
        <AgentConfirmApplicationModal
            v-if="confirmApplicationModal" :dialog="confirmApplicationModal"
            :application="application"
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

export default {
    name: "AgentCreateNewApplication",
    components: {
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
            states: [
                {text: 'NSW', value: 'New South Wales'},
                {text: 'VIC', value: 'Victoria'},
                {text: 'QLD', value: 'Queensland'},
                {text: 'SA', value: 'South Australia'},
                {text: 'NT', value: 'Northern Territory'},
                {text: 'TAS', value: 'Tasmania'},
                {text: 'ACT', value: 'Australian Capital Territory'},
            ],
            showMovingDate: false,
            showDOB: false,
            service_types: {
                power: false,
                gas: false,
                water: false,
                internet: false,
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
            createSuccessfulModal: false,
            title: '',
        }
    },
    created() {
        this.onStreetChanged = debounce(() => {
            if (this.application.address_text.length > 0) {
                GoogleMapService.getStreetAddressesByKeyword(this.application.address_text)
                    .then((data) => {
                        this.searchResult = data;
                        this.showMenu = this.searchResult.length > 0
                    });
            }
        }, 250);

    },
    methods: {
        onAddressSelected(place) {
            GoogleMapService.getAddressDetailsByPlaceId(place.place_id)
                .then((data) => {

                    this.application.address_text = data.formatted_address;
                    this.application.street_address = data.street;
                    this.application.city = data.city;
                    this.application.postcode = data.postcode;
                    this.application.state = data.state;
                    this.application.street_number = data.street_number;
                    this.application.unit_number = data.unit_number;
                    this.application.street_name = data.street_name;
                    // this.mapToModel(data)
                });
        },
        onCancel() {
            this.$router.push({name: 'agent.application.dashboard'});
        },
        async onSubmit() {
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
            AgentApplicationService.createApplication(this.application)
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
        syncDob()
        {
            if(DayJs(this.application.date_of_birth,'DD/MM/YYYY').isValid())
            {
                this.dob = (DayJs(this.application.date_of_birth,'DD/MM/YYYY')).format('YYYY-MM-DD');
            }
        },
        syncMovingDate() {
            if(DayJs(this.application.moving_date,'DD/MM/YYYY').isValid())
            {
                this.moving_date = (DayJs(this.application.moving_date,'DD/MM/YYYY')).format('YYYY-MM-DD');
            }
        }
    },
    watch: {
        dob() {
            this.application.date_of_birth = (new DayJs(this.dob).format('DD/MM/YYYY'));
        },

        moving_date() {
            this.application.moving_date = (new DayJs(this.moving_date).format('DD/MM/YYYY'));
        },

    }
};
</script>

<style scoped>
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

</style>
