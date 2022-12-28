<template>
    <div class="hood-card" style="padding: 0px !important; max-height: 100%;" >
        <template >
            <v-expansion-panels  style="box-shadow: none !important;">
                <v-expansion-panel style="box-shadow: none !important;">
                    <v-expansion-panel-header>
                        Profile Details
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <v-col cols="5" class="py-0 my-1">
                                <p class="font-weight-bold">Title</p>
                            </v-col>
                            <v-col cols="7"  class="py-0 my-1">
                                <div class="text-field">
                                        <v-text-field
                                            v-model="chatbot_app.personal_details.title"
                                            outlined
                                            dense
                                            hide-details="auto"
                                            placeholder="Card Number"
                                        ></v-text-field>
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
                                    <v-text-field
                                        v-model="chatbot_app.personal_details.dob"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
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

                        </v-row>
                        <v-row>
                            <v-col cols="10">

                            </v-col>
                            <v-col cols="2">
                                <v-btn small right> Save</v-btn>
                            </v-col>

                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </template>

        <v-divider ></v-divider>

        <template>
            <v-expansion-panels>
                <v-expansion-panel style="box-shadow:none !important;">
                    <v-expansion-panel-header>
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
                                        v-model="application.tenancy_type"
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
                                        v-model="application.hassolar"
                                        :items="solarPowerDD"
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
                                <p class="font-weight-bold">NMI (Power)</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="application.nmi"
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
                                        v-model="application.mirn"
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
                                        v-model="application.is_renovation_on"
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
                                        v-model="application.has_access_req"
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
                                    <v-text-field
                                        v-model="chatbot_app.personal_details.connection_date"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
                                </div>
                            </v-col>

                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">Service Address:</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="chatbot_app.personal_details.to_address"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
                                </div>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col cols="10">

                            </v-col>
                            <v-col cols="2">
                                <v-btn small right> Save</v-btn>
                            </v-col>

                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </template>

        <v-divider></v-divider>

        <template>
            <v-expansion-panels>
                <v-expansion-panel style="box-shadow:none !important;">
                    <v-expansion-panel-header>
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
                                        v-model="application.type"
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
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="application.id_number"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
                                </div>
                            </v-col>
                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">ID Expire Date</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field" >
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
                                                    placeholder="DD/MM/YYYY"
                                                    outlined
                                                    dense
                                                    v-model="application.expire_date"
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
                                            @input="showMovingDate = false"
                                        ></v-date-picker>
                                    </v-menu>
                                </div>
                            </v-col>
                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">ID Special Number</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-select
                                        v-model="application.id_special_number"
                                        :items="specialNumberDD"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    >
                                    </v-select>
                                </div>
                            </v-col>
                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">Passport Country</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-text-field
                                        v-model="application.passport_country"
                                        outlined
                                        dense
                                        hide-details="auto"
                                        placeholder="Card Number"
                                    ></v-text-field>
                                </div>
                            </v-col>

                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">Card Color</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-select
                                        v-model="application.type"
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

                            <v-col cols ="5"  class="py-0 my-1">
                                <p class="font-weight-bold">DL</p>
                            </v-col>
                            <v-col cols ="7" class="py-0 my-1">
                                <div class="text-field">
                                    <v-select
                                        v-model="application.id_state"
                                        item-text="text"
                                        item-value="value"
                                        :items="statesDD"
                                        outlined
                                        dense
                                        hide-details="auto"
                                    >
                                    </v-select>
                                </div>
                            </v-col>

                        </v-row>
                        <v-row>
                            <v-col cols="10">

                            </v-col>
                            <v-col cols="2">
                                <v-btn small right> Save</v-btn>
                            </v-col>

                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </template>

        <v-divider></v-divider>

        <template >
            <v-expansion-panels  style="box-shadow: none !important;">
                <v-expansion-panel style="box-shadow: none !important;">
                    <v-expansion-panel-header>
                        Service Preference
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                            <v-col cols="4">
                                <div  class="my-0 py-0 mx-0 border-all">
                                    <p class="pt-2 pb-1 mb-0 services">
                                  <span class="ml-0">
                                      <v-icon  color="yellow" size="17">mdi-flash</v-icon> Power
                                  </span>
                                    </p>
                                    <p class="py-0 my-0 service-status" >
                                        <small>Current Status</small>
                                        <v-select
                                            placeholder="Please select"
                                            v-model="formData.power_status"
                                            item-text="text"
                                            item-value="value"
                                            :items="powerStatus"
                                            outlined
                                            dense
                                            hide-details="auto"
                                        >
                                        </v-select>
                                    </p>
                                </div>
                            </v-col>
                            <v-col cols="4">
                                <div  class="my-0 py-0 mx-0 border-all">
                                    <p class="pt-2 pb-1 mb-0 services">
                                  <span class="ml-0">
                                      <v-icon color="red" size="17">mdi-fire</v-icon> Gas
                                  </span>
                                    </p>
                                    <p class="py-0 my-0 service-status" >
                                        <small>Current Status</small>
                                        <v-select
                                            placeholder="Please select"
                                            v-model="formData.gas_status"
                                            item-text="text"
                                            item-value="value"
                                            :items="gasStatus"
                                            outlined
                                            dense
                                            hide-details="auto"
                                        >
                                        </v-select>
                                    </p>
                                </div>
                            </v-col>
                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </template>

        <v-divider ></v-divider>

        <template>
            <v-expansion-panels style="box-shadow:none !important;">
                <v-expansion-panel>
                    <v-expansion-panel-header>
                        Application Notes
                    </v-expansion-panel-header>
                    <v-expansion-panel-content>
                        <v-row>
                           <ChatbotApplicationNote ></ChatbotApplicationNote>
                        </v-row>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </template>

    </div>
</template>

<script>
import DayJs from "dayjs";

const SPECIAL_NUMBER = require('@scripts/data/constants/SPECIAL_NUMBER');
import ChatbotApplicationNote from "@scripts/components/chatbot/ChatbotApplicationNote";
import {isNull} from "lodash-es";
import CustomerService from "@scripts/services/CustomerService";
export default {
    name: "ChatbotApplicationDetails",
    props: ['application'],
    components: {
        ChatbotApplicationNote,
    },
    data() {
        return {
            chatbot_app: null,
            app_id: null,
            isProfileEditMode: false,
            specialNumberDD: SPECIAL_NUMBER,
            showMovingDate: false,
            expire_date:  '',
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
            ]
        }
    },
    methods: {
        propertyDetails()
        {
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

       async loadApplication() {
           this.chatbot_app =  await CustomerService.getMovingData(this.app_id);
        }
    },
    watch: {
        expire_date() {
            if (isNull(this.expire_date) || this.expire_date == '' || this.expire_date == undefined ) return;
            this.application.expire_date = new DayJs(this.expire_date).format(
                "DD/MM/YYYY"
            );
        },
        '$route': {
            handler() {
                const app_id = this.$route.query?.app_id;
                if(this.app_id !== app_id) {
                    this.app_id = app_id;
                    this.loadApplication();
                }
            }
        }

    }
};
</script>

<style scoped>
.layout-fixed-table{
    table-layout: fixed;
    width: 100%
}
.service-status{
    font-size: 14px !important;
}
.services{
    font-size: 15px !important;
    font-weight: 700;
}
.unassigned, .assigned, .escalated, .processing, .common_color, .closed, .can\'t_connect{
    color: black !important;
}
.submitted{
    color: #0CC4ED !important;
}
.accepted{
    color: #15DB64 !important;
}
.rejected{
    color: #E91E63 !important;
}
.need_more_info{
    color: #FF5722 !important;
}

.view_application {
    background: #FFC104
}

.border-all{
    /* border: 1px solid black; */
    flex-basis: 31%;
}

.flex-wrap-100{
    flex-wrap: wrap;
    width: 100%;
}

.v-expansion-panel::before {
    box-shadow:none !important;
}
</style>
