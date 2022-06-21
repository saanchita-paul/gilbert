<template>
    <div v-if="cafFileData" class="containerRootClass">
        <v-row no-gutters>
            <v-col cols="6">
                <h1>Shakil Hossain</h1>
            </v-col>
            <v-col cols="6" style="text-align:right">
                <v-btn
                    v-if="isEdit"
                    @click="isEdit=false"
                    small
                    style="height: 25px; min-width: 90px; color: #5c229a; border: 3px solid #5c229a;"
                    outlined
                >
                    Edit
                </v-btn>

                <div v-else>
                    <v-btn
                        small
                        style="height: 25px; min-width: 90px;"
                        @click="isEdit=true"
                    >
                        Cancel
                    </v-btn>

                    <v-btn
                        small
                        style="height: 25px; min-width: 90px; background: #5c229a; color: white"
                        @click.prevent="updateCafFile"
                    >
                        Save
                    </v-btn>
                </div>


            </v-col>
        </v-row>

        <v-row no-gutters>
            <v-col cols="3">
                <h4 class="header">Personal Details</h4>
                <div>
                    <div class="item">
                        <p class="item-title">Title</p>
                        <p class="item-value" v-if="isEdit">Mr</p>
                        <v-select
                            v-else
                            outlined
                            dense
                            :items="titlesDD"
                            v-model="caf_detail.title"
                            class="mr-2 item-value"
                        ></v-select>
                    </div>
                    <div class="item">
                        <p class="item-title">First Name</p>
                        <p class="item-value" v-if="isEdit">Shakil</p>
                        <v-text-field
                            v-else
                            outlined
                            dense
                            hide-details="auto"
                            height="20px"
                            style="background-color: white"
                            class="mr-2 item-value"
                            v-model="caf_detail.first_name"
                        />
                    </div>
                    <div class="item">
                        <p class="item-title">Middle Name</p>
                        <p class="item-value" v-if="isEdit">-</p>
                        <v-text-field
                            v-else
                            outlined
                            dense
                            hide-details="auto"
                            style="background-color: white"
                            class="mr-2 item-value"
                            v-model="caf_detail.middle_name"
                        />
                    </div>
                    <div class="item">
                        <p class="item-title">Last Name</p>
                        <p class="item-value" v-if="isEdit">Hossain</p>
                        <v-text-field
                            v-else
                            outlined
                            dense
                            hide-details="auto"
                            style="background-color: white"
                            class="mr-2 item-value"
                            v-model="caf_detail.last_name"
                        />
                    </div>
                </div>
                <div class="item">
                    <p class="item-title">Date of Birth</p>
                    <p class="item-value">05/05/1990</p>
                </div>
                <div class="item">
                    <p class="item-title">Mobile</p>
                    <p class="item-value">0120201202</p>
                </div>
                <div class="item">
                    <p class="item-title">Email</p>
                    <p class="item-value">shakil@gmail.com</p>
                </div>
                <div class="item">
                    <p class="item-title">Billing</p>
                    <p class="item-value">Email/Paper</p>
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Property Details</h4>
                <div class="item">
                    <p class="item-title">Occupancy Type</p>
                    <p class="item-value">Renter</p>
                </div>
                <div class="item">
                    <p class="item-title">Service Address</p>
                    <p class="item-value">10 ROPE WALK, BRUNSWICK VIC 3056</p>
                </div>
                <div class="item">
                    <p class="item-title">Billing Address</p>
                    <p class="item-value">10 ROPE WALK, BRUNSWICK VIC 3056</p>
                </div>
                <div class="item">
                    <p class="item-title">NMI (Power) *</p>
                    <p class="item-value" v-if="isEdit">125252525</p>
                    <v-text-field
                        v-else
                        outlined
                        dense
                        hide-details="auto"
                        style="background-color: white"
                        class="mr-2 item-value"
                        v-model="caf_detail.nmi"
                    />
                </div>
                <div class="item">
                    <p class="item-title">MIRN (Gas) *</p>
                    <p class="item-value" v-if="isEdit">525252590</p>
                    <v-text-field
                        v-else
                        outlined
                        dense
                        hide-details="auto"
                        style="background-color: white"
                        class="mr-2 item-value"
                        v-model="caf_detail.mirn"
                    />
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Agent's Additional Instructions</h4>

                <div class="item">
                    <p class="item-title">Business Name</p>
                    <p class="item-value" v-if="isEdit">Ray white camberwell PTY LTD</p>
                    <v-text-field
                        v-else
                        outlined
                        dense
                        hide-details="auto"
                        style="background-color: white"
                        class="mr-2 item-value"
                        v-model="caf_detail.business_name"
                    />
                </div>

                <div class="item">
                    <p class="item-title">ABN</p>
                    <p class="item-value" v-if="isEdit">25256525252</p>
                    <v-text-field
                        v-else
                        outlined
                        dense
                        hide-details="auto"
                        style="background-color: white"
                        class="mr-2 item-value"
                        v-model="caf_detail.abn"
                    />
                </div>
                <div class="item">
                    <p class="item-title">Agency Office</p>
                    <p class="item-value">Ray White Camberwell</p>
                </div>
                <div class="item">
                    <p class="item-title">Agent Name</p>
                    <p class="item-value">Shakil Hossain</p>
                </div>
                <div class="item">
                    <p class="item-title">Additional Instructions</p>
                    <p class="item-value">Additional Instructions Go here...</p>
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Connection Details</h4>
                <div class="item">
                    <p class="item-title">Connection Date</p>
                    <p class="item-value" v-if="isEdit">05/05/2022</p>
                    <div class="text-field" v-else>
                        <v-menu
                            v-model="connectionDate"
                            :close-on-content-click="false"
                            :nudge-right="40"
                            transition="scale-transition"
                            offset-y
                        >
                            <template v-slot:activator="{ on, attrs }">
                                <ValidationProvider
                                    name="Connection Date"
                                    v-slot="{ errors }"
                                >
                                    <v-text-field
                                        placeholder="DD/MM/YYYY"
                                        outlined
                                        dense
                                        append-icon="mdi-calendar"
                                        v-model="connection_date"
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
                                v-model="connection_date"
                                @input="connectionDate = false"
                            ></v-date-picker>
                        </v-menu>
                    </div>
                </div>
                <div class="item">
                    <p class="item-title">Supplier</p>
                    <p class="item-value" v-if="isEdit">Energy Australia</p>
                    <p class="" v-else>Energy Australia</p>
                </div>
                <div class="item">
                    <p class="item-title">Service Type</p>
                    <p class="item-value" v-if="isEdit">Electricity Only</p>
                    <v-select
                        v-else
                        outlined
                        dense
                        :items="title"
                        class=""
                    ></v-select>
                </div>
                <div class="item">
                    <p class="item-title">Plan</p>
                    <p class="item-value" v-if="isEdit">Total Home</p>
                    <v-select
                        v-else
                        outlined
                        dense
                        :items="title"
                        class=""
                    ></v-select>
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<script>

import dayjs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import {titlesMapperForDropdown} from "@scripts/data/titleMapper";

export default {
    name: "ApplicationCafFileDetails",
    components: {},
    props: ["cafFileData"],

    data() {
        return {
            isEdit: true,
            titlesDD: titlesMapperForDropdown,
            connectionDate: false,
            connection_date: null,

            caf_detail: {
                title: "",
                first_name: "Md",
                middle_name: "Shakil",
                last_name: "Hossain",
                nmi: "125252525",
                mirn: "525252590",
                business_name: "Ray white camberwell PTY LTD",
                abn: "25256525252",
            },
        };
    },
    computed: {},
    mounted() {
    },
    methods: {
        updateCafFile() {

        }
    },
};
</script>

<style scoped>
.customClass {
    min-height: 20px;
}

.lead-name {
    font-size: 1.3em;
}

.header {
    font-size: 1.2em;
    margin-bottom: 8px;
}

.hr-bar {
    border-left: 1px solid #ccc;
    padding-left: 10px;
}

.item {
    display: flex;
}

.item-title {
    width: 40%;
    font-weight: 600;
    margin-bottom: 5px !important;
}

.item-value {
    width: 60%;
    margin-bottom: 5px !important;
}

.application-consent {
    color: green !important;
    font-size: 14px !important;
}

.application-consent-waiting {
    color: #E91E63 !important;
    font-size: 14px !important;
}

.containerRootClass {
    background-color: #FAFAFE;
    margin-left: -12px;
    padding: 20px 30px;
}

.layout-fixed-table {
    table-layout: fixed;
    width: 100%
}

.service-status {
    font-size: 10px;
    font-weight: 400;
}

.services {
    font-size: 14px !important;
    font-weight: 700;
}

.active-power-subtitle {
    color: #15DB64;
}

.active-gas-subtitle {
    color: #263238;
}

.active-water-subtitle {
    color: #E91E63;
}

.active-internet-subtitle {
    color: #263238;
}

.preferenceTitle {
    font-size: 16px;
    font-weight: 700;
}

.submitted-color {
    color: #0CC4ED;
}

.connected-color {
    color: #16A948;
}

.needinfo-color {
    color: #16A948;
}

.inprogress-color {
    color: #263238;
}

.rejected-color {
    color: #E91E63;
}

</style>
