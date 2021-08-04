<template>
    <v-container>
        <v-card class="pa-4 new-application">
            <v-row>
                <v-col cols="12 pb-0">
                    <v-btn @click="onCancel"><v-icon left dark>mdi-arrow-left</v-icon>Back to my Dashboard</v-btn>
                    <h3 class="page-title my-5 pt-5">Add a new Application</h3>
                    <p class="sub-title mb-0">Contact Details  <small class="font-weight-thin">Personal details or your applicant.</small>  <small class="font-weight-thin float-right">All fields are mandatory*</small></p>
                </v-col>

                <v-col cols="6">
                    <v-text-field
                        label="Firstname*"
                        outlined
                        dense
                        placeholder="Firstname"
                        v-model="application.first_name"
                    ></v-text-field>
                    <v-text-field
                        label="Email*"
                        outlined
                        dense
                        placeholder="example@domain.com"
                        v-model="application.email"
                    ></v-text-field>
                    <v-select outlined dense
                              v-model="application.tenancy_type"
                              :items="tenancy_types"
                              label="Tenancy Type*"
                              placeholder="Please Select">
                    </v-select>
                </v-col>

                <v-col cols="6">

                    <v-text-field
                        label="Lastname*"
                        outlined
                        dense
                        placeholder="Lastname*"
                        v-model="application.last_name"
                    ></v-text-field>
                    <v-text-field
                        label="Mobile number*"
                        outlined
                        dense
                        placeholder="+61 410"
                        v-model="application.phone"
                    ></v-text-field>
                    <v-menu
                        v-model="showDOB"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on, attrs }">
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
                            ></v-text-field>
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
                                    <v-text-field
                                        label="Moving Date*"
                                        placeholder="DD/MM/YYYY"
                                        outlined
                                        dense
                                        append-icon="mdi-calendar"
                                        v-model="application.moving_date"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="application.moving_date" @input="showMovingDate = false"></v-date-picker>
                            </v-menu>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col cols="6" class="pt-0">
                    <v-text-field
                        label="To Address Unit"
                        outlined
                        dense
                        placeholder="2/56"
                        v-model="application.address_unit"
                    ></v-text-field>
                    <v-text-field
                        label="City"
                        outlined
                        dense
                        placeholder="Camberwell"
                        v-model="application.city"
                    ></v-text-field>
                    <v-text-field
                        label="Country"
                        outlined
                        dense
                        placeholder="Australia"
                        v-model="application.country"
                    ></v-text-field>
                </v-col>

                <v-col cols="6" class="pt-0">
                    <v-text-field
                        label="Apartment, suite, etc.."
                        outlined
                        dense
                        placeholder="House / Apartment, Bldg, etc"
                        v-model="application.address_apartment"
                    ></v-text-field>
                    <v-select outlined dense
                              v-model="application.state"
                              :items="states"
                              label="State"
                              placeholder="Please Select">
                    </v-select>
                    <v-text-field
                        label="Postcode"
                        outlined
                        dense
                        placeholder="3429"
                        v-model="application.postcode"
                    ></v-text-field>
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
                {text: 'Renter', value: 'renter'},
                {text: 'Home Owner', value: 'home_owner'},
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
        }
    },
    methods: {
        onCancel() {
            this.$router.push({name: 'agent.application.dashboard'});
        },
        onSubmit() {
            this.confirmApplicationModal = true;
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
