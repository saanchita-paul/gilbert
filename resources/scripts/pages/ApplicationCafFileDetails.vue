<template>
    <div v-if="cafFileData" class="containerRootClass">
        <v-row no-gutters>
            <v-col cols="6">
                <h1>{{ cafFileData.full_name }}</h1>
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
                        :loading = "loading"
                        style="height: 25px; min-width: 90px; background: #5c229a; color: white"
                        @click.prevent="updateCafFile(cafFileData.id)"
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
                        <p class="item-value" v-if="isEdit">{{ cafFileData.title }}</p>
                        <v-select
                            v-else
                            outlined
                            dense
                            :items="titlesDropDown"
                            v-model="caf_detail.title"
                            class="mr-2 item-value"
                        ></v-select>
                    </div>
                    <div class="item">
                        <p class="item-title">First Name</p>
                        <p class="item-value" v-if="isEdit">{{ cafFileData.first_name }}</p>
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
                        <p class="item-value" v-if="isEdit">{{ cafFileData.middle_name == null ? '-' : cafFileData.middle_name }}</p>
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
                        <p class="item-value" v-if="isEdit">{{ cafFileData.last_name }}</p>
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
                    <p class="item-value">{{ date_of_birth }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Mobile</p>
                    <p class="item-value">{{ cafFileData.phone }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Email</p>
                    <p class="item-value">{{ cafFileData.email }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Billing</p>
                    <p class="item-value">{{ billing(cafFileData.billing_preference)  }}</p>
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Property Details</h4>
                <div class="item">
                    <p class="item-title">Occupancy Type</p>
                    <p class="item-value">{{ cafFileData.occupancy_type ? cafFileData.occupancy_type : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Service Address</p>
                    <p class="item-value">{{ cafFileData.to_address ? cafFileData.to_address : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Billing Address</p>
                    <p class="item-value">{{ cafFileData.to_address ? cafFileData.to_address : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">NMI (Power) *</p>
                    <p class="item-value" v-if="isEdit">{{ cafFileData.nmi ? cafFileData.nmi : '' }}</p>
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
                    <p class="item-value" v-if="isEdit">{{ cafFileData.mirn ? cafFileData.mirn : '' }}</p>
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
                    <p class="item-value" v-if="isEdit">{{ cafFileData.business_name ? cafFileData.business_name : '' }}</p>
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
                    <p class="item-value" v-if="isEdit">{{ cafFileData.abn ? cafFileData.abn : '' }}</p>
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
                    <p class="item-value"></p>
                </div>
                <div class="item">
                    <p class="item-title">Agent Name</p>
                    <p class="item-value"></p>
                </div>
                <div class="item">
                    <p class="item-title">Additional Instructions</p>
                    <p class="item-value">{{ cafFileData.additional_instruction ? cafFileData.additional_instruction : '' }}</p>
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Connection Details</h4>
                <div class="item">
                    <p class="item-title">Connection Date</p>
                    <p class="item-value" v-if="isEdit">{{ cafFileData.connection_date ? cafFileData.connection_date : '' }}</p>
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
                                        v-model="caf_detail.connection_date"
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
                    <p class="item-value">{{ cafFileData.supplier ? cafFileData.supplier : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Service Type</p>
                    <p class="item-value" v-if="isEdit">{{ cafFileData.service_type ? cafFileData.service_type : '' }}</p>
                    <v-select
                        v-else
                        outlined
                        dense
                        :items="serviceDropDown"
                        v-model="selectedService"
                        @change="changeServiceType"
                    ></v-select>
                </div>
                <div class="item">
                    <p class="item-title">Plan</p>
                    <p class="item-value" v-if="isEdit">{{ cafFileData.plan ? cafFileData.plan : '' }}</p>
                    <v-select
                        v-else
                        outlined
                        dense
                        :items="planDropDown"
                        v-model="selectedPlan"
                        @change="updateSelelectedService"
                    ></v-select>
                </div>
                <div class="item" v-if="electricityService.service_type === 'electricity'">
                    <p class="item-title">Electricity</p>
                    <p class="item-value">{{ electricityService.status }}</p>
                    <v-btn v-if="electricityService.status === 'Rejected'"
                        @click="dialog=true"
                        small
                        style="height: 25px; min-width: 90px; color: #5c229a; border: 3px solid #5c229a;"
                        outlined
                    >
                        Reason
                    </v-btn>


                </div>

                <div class="item" v-if="gasService.service_type === 'gas'">
                    <p class="item-title">Gas</p>
                    <p class="item-value">{{ gasService.status }}</p>
                    <v-btn v-if="gasService.status === 'Rejected'"
                        @click="dialog=true"
                        small
                        style="height: 25px; min-width: 90px; color: #5c229a; border: 3px solid #5c229a;"
                        outlined
                    >
                        Reason
                    </v-btn>

                    <RejectionReasonModal :dialog="dialog" @close="onCloseReject"></RejectionReasonModal>

                </div>
            </v-col>
        </v-row>

        <SuccessfullyUpdateCloseConfirmModal v-if="closeConfirm" :dialog="closeConfirm"></SuccessfullyUpdateCloseConfirmModal>

    </div>
</template>

<script>

import dayJs from "dayjs";
import ApplicationCafFileService from "@scripts/services/crm/ApplicationCafFileService";
import SuccessfullyUpdateCloseConfirmModal from "@scripts/components/crm/modals/SuccessfullyUpdateCloseConfirmModal";
import {capitalize} from "lodash-es";
import {titlesMapperForDropdown} from "@scripts/data/titleMapper";
import DayJs from "dayjs";
import RejectionReasonModal from "@scripts/components/crm/modals/RejectionReasonModal";

export default {
    name: "ApplicationCafFileDetails",
    components: {SuccessfullyUpdateCloseConfirmModal, RejectionReasonModal},
    props: ["cafFileData"],

    data() {
        return {
            dialog: false,
            isEdit: true,
            isRejected: "Rejected",
            titlesDropDown: titlesMapperForDropdown,
            serviceDropDown: [],
            planDropDown: [
                {
                    text: "Basic - Home",
                    value: "Basic - Home",
                },
                {
                    text: "Flexi Plan (Home)",
                    value: "Flexi Plan (Home)",
                },
                {
                    text: "Balance Plan (Home)",
                    value: "Balance Plan (Home)",
                },
            ],
            connectionDate: false,
            closeConfirm: false,
            showModal:false,
            loading: false,
            connection_date: null,
            selectedService: '',
            selectedPlan: '',

            caf_detail: {
                title: "",
                first_name: "",
                middle_name: "",
                last_name: "",
                nmi: "",
                mirn: "",
                business_name: "",
                abn: "",
                connection_date: "",
                service: {
                    connection_date: '',
                    plan: '',
                    service_type : ''

                },
                service_type: "",
                // plan: ""
            },
        };
    },
    computed: {
        date_of_birth() {
            return dayJs(dayJs(this.cafFileData.dob,'YYYY-MM-DD').format('DD/MM/YYYY')).isValid() ? dayJs(this.cafFileData.dob,'YYYY-MM-DD').format('DD/MM/YYYY') : null;
        },

        electricityService()
        {
            return this.cafFileData.service.find((dt)=>  {
                return dt.service_type === 'electricity';
            });
        },

        gasService()
        {
            return this.cafFileData.service.find((dt)=>  {
                return dt.service_type === 'gas';
            });
        }
    },
    watch: {
        cafFileData: {
            async handler() {
                await this.syncData();
                this.updateServiceDropDown();
            },
            deep: true,
        },

        connection_date() {
            this.caf_detail.connection_date = (new DayJs(this.connection_date).format('DD/MM/YYYY'));
            this.caf_detail.service.connection_date = this.connection_date;
        }
    },
    async mounted() {
        await this.syncData();
        this.updateServiceDropDown();
    },
    methods: {


        updateSelelectedService() {
            this.caf_detail.service.service_type = this.selectedService;
            this.caf_detail.service.plan = this.selectedPlan;


        },

        updateServiceDropDown()
        {
            this.serviceDropDown = this.cafFileData.service_dropdown;
            this.selectedService = this.cafFileData.selected_service;

        },

        syncData() {
            this.caf_detail.title = this.cafFileData.title;
            this.caf_detail.first_name = this.cafFileData.first_name;
            this.caf_detail.middle_name = this.cafFileData.middle_name;
            this.caf_detail.last_name = this.cafFileData.last_name;
            this.caf_detail.nmi = this.cafFileData.nmi;
            this.caf_detail.mirn = this.cafFileData.mirn;
            this.caf_detail.business_name = this.cafFileData.business_name;
            this.caf_detail.abn = this.cafFileData.abn;
            this.caf_detail.connection_date = this.cafFileData.connection_date;
            this.caf_detail.service_type = this.cafFileData.selected_service;
            this.selectedPlan = this.cafFileData.plan;
            this.caf_detail.service.plan = this.selectedPlan;
            this.caf_detail.service.service_type = this.cafFileData.selected_service;
            this.caf_detail.service.connection_date = dayJs(this.cafFileData.connection_date,'DD/MM/YYYY').format('YYYY-MM-DD');
            this.caf_detail.service.status = this.cafFileData.status;



            // this.caf_detail.plan = this.cafFileData.plan;
        },
        billing(value) {
            return value ? capitalize(value) : '';
        },
        async updateCafFile(cafId) {
            this.loading = true;
            let response = await ApplicationCafFileService.updateApplicationCafFileData(cafId, this.caf_detail);
            this.$emit('refreshTable', response);


            console.log(response);
            this.loading = false;
            // this.closeConfirm = true;
        },

        changeServiceType() {
            this.caf_detail.service.service_type = this.selectedService;
            this.$emit('updateServiceType', this.selectedService, this.cafFileData.id)
        },
        onCloseReject() {
            this.dialog = false;
        },

        // isDisabled(services) {
        //    return ApplicationCafFileService.isPossibleToCreateCaf(this.cafFileData.selected_service, services);
        // }
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
