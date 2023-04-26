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
                    Edit Application
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
                    <p class="item-value">{{ application.date_of_birth }}</p>
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
                    <p class="item-value">{{ application.billing }}</p>
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Property Details</h4>
                <div class="item">
                    <p class="item-title">Occupancy Type</p>
                    <p class="item-value">{{ application.occupancy_type }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Service Address</p>
                    <p class="item-value">{{ application.address_text ? application.address_text : '' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Billing Address</p>
                    <p class="item-value">{{
                            application.billing_address_text ? application.billing_address_text : ''
                        }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Shipping Address</p>
                    <p class="item-value">
                        {{
                            (application.internet_service_info && application.internet_service_info.address_text) ?
                                application.internet_service_info.address_text : ''
                        }}
                    </p>
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Additional Information</h4>

                <div class="item">
                    <p class="item-title">Modem Type</p>
                    <p class="item-value">{{ getModemType+' Modem' }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Charity</p>
                    <p class="item-value">{{ charityMapper[application.internet_service_info.charity] }}</p>
                </div>
                <div class="item">
                    <p class="item-title">CIS Delivery</p>
                    <p class="item-value">
                        Shipping Address
                    </p>
                </div>
            </v-col>
            <v-col cols="3" class="hr-bar pl-2">
                <h4 class="header">Connection Details</h4>
                <div class="item">
                    <p class="item-title">Connection Date</p>
                    <p class="item-value">{{ application.connection_date }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Supplier</p>
                    <p class="item-value">{{ application.supplier }}</p>
                </div>
                <div class="item">
                    <p class="item-title">Service Type</p>
                    <p class="item-value">{{ application.service_type }}</p>
                </div>
                <div class="item">
                    <p class="item-title"> Internet Plan</p>
                    <p class="item-value">{{ application.internet_plan }}</p>
                </div>
                <div class="item">
                    <p class="item-title"> Home Phone</p>
                    <p class="item-value">{{ application.internet_service_info.is_need_home_phone ? 'Y' : 'N' }}</p>
                </div>
                <div class="item" v-if="isExistingLandline">
                    <p class="item-title"> Selected Phone Plan</p>
                    <p class="item-value" >{{ application.internet_service_info.home_phone_plan.toUpperCase() }}</p>
                </div>
                <div class="item" v-if="isExistingLandline">
                    <p class="item-title"> Phone Number to Transfer</p>
                    <p class="item-value" >{{ application.internet_service_info.home_phone_number }}</p>
                </div>
                <div class="item" v-if="isExistingLandline">
                    <p class="item-title"> Current Provider</p>
                    <p class="item-value" >{{ application.internet_service_info.current_provider }}</p>
                </div>
                <div class="item">
                    <p class="item-title"> Back to Base Alarm</p>
                    <p class="item-value">{{ application.internet_service_info.is_back_to_base ? 'Y' : 'N' }}</p>
                </div>
                <div class="item">
                    <p class="item-title"> Medical Alarm</p>
                    <p class="item-value">{{ application.internet_service_info.is_security_alarm ? 'Y' : 'N' }}</p>
                </div>
            </v-col>
        </v-row>

    </div>
</template>

<script>


import InternetServiceConstant from "@scripts/modules/internet/constants/InternetServiceConstant";

export default {
    name: "NbnApplicationCafFileDetails",
    components: {},
    props: ["application"],

    data() {
        return {
            modemMapper: InternetServiceConstant.MODEM_TYPE_MAP,
            charityMapper: InternetServiceConstant.CHARITY_MAP,
        };
    },
    computed: {
        isExistingLandline(){
            return !!this.application.internet_service_info.is_existing_landline
        },
        getModemType(){
            return this.application.internet_service_info.modem_type.toLowerCase() === 'none'
                ? 'BYO'
                : this.application.internet_service_info.modem_type.charAt(0).toUpperCase() + this.application.internet_service_info.modem_type.slice(1)
        }
    },
    watch: {},
    async mounted() {
    },
    methods: {
        editApplication() {
            this.$router.push({name: 'applications.details', params: {id: this.application.id}});
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
