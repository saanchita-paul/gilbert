<template>
    <v-container>
        <v-card class="pa-4 new-application">
            <ValidationObserver ref="create_application">
                <v-row>
                    <v-col cols="12 pb-0">
                        <v-btn @click="onCancel"><v-icon left dark>mdi-arrow-left</v-icon>Back to my Dashboard</v-btn>
                        <h3 class="page-title my-5 pt-5">Add a new Application</h3>
                        <p class="sub-title mb-0">Contact Details  <small class="font-weight-thin">Personal details or your applicant.</small>  <small class="font-weight-thin float-right">All fields are mandatory*</small></p>
                    </v-col>

                    <v-col cols="6">
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

                    <v-col cols="6">
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
                        <ValidationProvider name="Mobile number" rules="required|cv-phone|length:10"  v-slot="{ errors }">
                            <v-text-field
                                label="Mobile number*"
                                :maxlength="10"
                                outlined
                                dense
                                placeholder="+61 410"
                                v-model="application.phone"
                                :error-messages=" errors[0]"
                            ></v-text-field>
                        </ValidationProvider>
                            <v-menu
                                v-model="showDOB"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <ValidationProvider name="Date of Birth" rules="required"  v-slot="{ errors }">
                                        <v-text-field
                                            label="Date of Birth*"
                                            placeholder="DD/MM/YYYY"
                                            outlined
                                            dense
                                            append-icon="mdi-calendar"
                                            v-model="application.date_of_birth"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            :error-messages=" errors[0]"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </template>
                                <v-date-picker v-model="application.date_of_birth" @input="showDOB = false"></v-date-picker>
                            </v-menu>

                    </v-col>

                    <v-col cols="12" class="py-0">
                        <p class="sub-title mb-0">Moving Details <small class="font-weight-thin">Information about your lead’s move.</small></p>
                    </v-col>
                    <v-col cols="12" class="pb-0">
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
                                        <ValidationProvider name="Moving Date" rules="required"  v-slot="{ errors }">
                                            <v-text-field
                                                label="Connection Date*"
                                                placeholder="DD/MM/YYYY"
                                                outlined
                                                dense
                                                append-icon="mdi-calendar"
                                                v-model="application.moving_date"
                                                readonly
                                                v-bind="attrs"
                                                v-on="on"
                                                :error-messages=" errors[0]"
                                            ></v-text-field>
                                        </ValidationProvider>
                                    </template>
                                    <v-date-picker v-model="application.moving_date" @input="showMovingDate = false"></v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                    </v-col>

                    <v-col cols="12" class="pb-0">
                        <v-row>
                            <v-col cols="12" class="py-0">
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
                        </v-row>
                    </v-col>

                    <v-col cols="6" class="pt-0">
                        <ValidationProvider name="Address" rules="required"  v-slot="{ errors }">
                            <v-text-field
                                label="Address*"
                                outlined
                                dense
                                placeholder="2/56, Bradman Drive"
                                v-model="application.street_address"
                            ></v-text-field>
                        </ValidationProvider>
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

                    <v-col cols="6" class="pt-0">
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

                    <v-col cols="12" class="pb-0">
                        <p class="sub-title mb-0">Service Interests</p>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center" :class="service_types.power ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('power')">
                            <h4 :class="service_types.power ? 'enabled' : 'disabled'">Power</h4>
                            <v-icon :disabled="!service_types.power" color="yellow">mdi-flash</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center" :class="service_types.gas ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('gas')">
                            <h4 :class="service_types.gas ? 'enabled' : 'disabled'">Gas</h4>
                            <v-icon :disabled="!service_types.gas" color="red">mdi-fire</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center" :class="service_types.water ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('water')">
                            <h4 :class="service_types.water ? 'enabled' : 'disabled'">Water</h4>
                            <v-icon :disabled="!service_types.water" color="blue">mdi-water</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="3">
                        <div class="leade-badge text-center" :class="service_types.internet ? 'div_enabled' : 'div_disabled' " @click="serviceInsert('internet')">
                            <h4 :class="service_types.internet ? 'enabled' : 'disabled'">Internet</h4>
                            <v-icon :disabled="!service_types.internet" color="green">mdi-wifi</v-icon>
                        </div>
                    </v-col>

                    <v-col cols="12">
                        <p class="sub-title  mt-5">Additional Instructions</p>
                        <v-textarea
                            outlined
                            placeholder="Additional Instructions goes here."
                            v-model="application.additional_instruction"
                        ></v-textarea>
                    </v-col>



                    <v-col cols="12">
                        <div class="d-flex justify-end">
                            <v-btn @click="onCancel" class="mx-4">Cancel</v-btn>
                            <v-btn @click="onSubmit" color="primary">Submit</v-btn>
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
    </v-container>
</template>

<script>
import ApplicationSummary from "@scripts/models/crm/ApplicationSummary";
import AgentConfirmApplicationModal from "@scripts/components/crm/modals/agent/AgentConfirmApplicationModal";
import AgentApplicationService from "@scripts/services/crm/AgentApplicationService";
import debounce from 'lodash-es/debounce';
import GoogleMapService from "@scripts/services/GoogleMapService";

export default {
    name: "AgentCreateNewApplication",
    components: {
        AgentConfirmApplicationModal,
    },
    data() {
        return {
            confirmApplicationModal: false,
            application: new ApplicationSummary(),
            tenancy_types:  [
                {text: 'Renter', value: 1},
                {text: 'Home Owner', value: 2},
            ],
            states: [
                {text: 'NSW', value: 'NSW'},
                {text: 'VIC', value: 'VIC'},
                {text: 'QLD', value: 'QLD'},
                {text: 'SA', value: 'SA'},
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
            searchResult: []
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
                    console.log('Application Saved Successfully');
                    this.$router.push({name: 'agent.application.dashboard'});
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
        }
    }
};
</script>

<style scoped>
    .enabled {
        color: black;
    }
    .disabled {
        color: gray;
    }
    .div_enabled {
        border-color: black;
        cursor: pointer;
    }
    .div_disabled {
        border-color: gray;
        cursor: pointer;
    }
</style>
