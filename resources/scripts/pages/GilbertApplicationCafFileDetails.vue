<template>
    <div v-if="application" class="containerRootClass">
        <v-row no-gutters>
            <v-col cols="6">
                <h1>{{ application.full_name }}</h1>
            </v-col>
            <v-col cols="6" style="text-align:right">
                <v-btn
                    @click="editApplication"
                    small
                    style="height: 25px; min-width: 90px; color: #5c229a; border: 3px solid #5c229a;"
                    outlined
                >
                    Edit
                </v-btn>
            </v-col>
        </v-row>

        <v-row no-gutters>
            <v-col cols="3">
                <h4 class="header">Personal Details</h4>
                <div>
                    <div class="item">
                        <p class="item-title">Title</p>
                        <p class="item-value">{{ application.title }}</p>
                    </div>
                    <div class="item">
                        <p class="item-title">First Name</p>
                        <p class="item-value">{{ application.first_name }}</p>
                    </div>
                    <div class="item">
                        <p class="item-title">Middle Name</p>
                        <p class="item-value">{{ application.middle_name == null ? '-' : application.middle_name }}</p>
                    </div>
                    <div class="item">
                        <p class="item-title">Last Name</p>
                        <p class="item-value">{{ application.last_name }}</p>
                    </div>
                </div>
                <div class="item">
                    <p class="item-title">Date of Birth</p>
                    <p class="item-value">{{ date_of_birth }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Mobile</p>
                    <p class="item-value">{{ application.phone }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Email</p>
                    <p class="item-value">{{ application.email }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Billing</p>
                    <p class="item-value">{{ billing(application.billing_preference)  }}</p>
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Property Details</h4>
                <div class="item">
                    <p class="item-title">Occupancy Type</p>
                    <p class="item-value">{{ application.occupancy_type ? application.occupancy_type : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Service Address</p>
                    <p class="item-value">{{ application.to_address ? application.to_address : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Billing Address</p>
                    <p class="item-value">{{ application.to_address ? application.to_address : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">NMI (Power) *</p>
                    <p class="item-value">{{ application.nmi ? application.nmi : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">MIRN (Gas) *</p>
                    <p class="item-value">{{ application.mirn ? application.mirn : '' }}</p>
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Agent's Additional Instructions</h4>

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
                    <p class="item-value">{{ application.additional_instruction ? application.additional_instruction : '' }}</p>
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Connection Details</h4>
                <div class="item">
                    <p class="item-title">Connection Date</p>
                    <p class="item-value">{{ application.connection_date ? application.connection_date : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Supplier</p>
                    <p class="item-value">{{ application.supplier ? application.supplier : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Service Type</p>
                    <p class="item-value">{{ application.service_type ? application.service_type : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Plan</p>
                    <p class="item-value">{{ application.plan ? application.plan : '' }}</p>
                </div>
            </v-col>
        </v-row>

    </div>
</template>

<script>

import dayJs from "dayjs";
import {capitalize} from "lodash-es";
import {titlesMapperForDropdown} from "@scripts/data/titleMapper";
import DayJs from "dayjs";

export default {
    name: "ApplicationCafFileDetails",
    components: {},
    props: ["application"],

    data() {
        return {
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
            ],
            connectionDate: false,
            closeConfirm: false,
            loading: false,
            connection_date: null,
            selectedService: '',
            selectedPlan: '',

            caf_detail: {
                id: "",
                title: "",
                first_name: "",
                middle_name: "",
                last_name: "",
                nmi: "",
                mirn: "",
                connection_date: "",
                service: {
                    connection_date: '',
                    plan: '',
                    service_type : ''
                },
                service_type: "",
            },
        };
    },
    computed: {
        date_of_birth() {
            return dayJs(dayJs(this.application.dob,'YYYY-MM-DD').format('DD/MM/YYYY')).isValid() ? dayJs(this.application.dob,'YYYY-MM-DD').format('DD/MM/YYYY') : null;
        },
    },
    watch: {
        application: {
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
            this.serviceDropDown = this.application.service_dropdown;
            this.selectedService = this.application.selected_service;

        },

        syncData() {
            this.caf_detail.id = this.application.id;
            this.caf_detail.title = this.application.title;
            this.caf_detail.first_name = this.application.first_name;
            this.caf_detail.middle_name = this.application.middle_name;
            this.caf_detail.last_name = this.application.last_name;
            this.caf_detail.nmi = this.application.nmi;
            this.caf_detail.mirn = this.application.mirn;
            this.caf_detail.connection_date = this.application.connection_date;
            this.caf_detail.service_type = this.application.selected_service;
            this.selectedPlan = this.application.plan;
            this.caf_detail.service.plan = this.selectedPlan;
            this.caf_detail.service.service_type = this.application.selected_service;
            this.caf_detail.service.connection_date = dayJs(this.application.connection_date,'DD/MM/YYYY').format('YYYY-MM-DD');
        },
        billing(value) {
            return value ? capitalize(value) : '';
        },

        changeServiceType() {
            this.caf_detail.service.service_type = this.selectedService;
            this.$emit('updateServiceType', this.selectedService, this.application.id)
        },

        editApplication() {
            this.$router.push({name: 'applications.details', params: {id: this.caf_detail.id}});
        },
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
