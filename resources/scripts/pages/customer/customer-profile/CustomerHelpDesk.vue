<template>
    <div v-if="customer && customer.id">
        <v-row class="body_row">
            <v-col cols="12" class="px-0">
                <v-card>
                    <v-card-title>
                        <v-row>
                            <v-col color="black--text" class="profile_body_header">Property Information</v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-row class="pb-2">
                            <v-col cols="12" class="py-0"><p class="mb-0 profile-info-title"><span class=" font-weight-bold">Id#{{customer.property_profile_id}}</span></p></v-col>
                            <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">Account Type:</span>{{customer.property_account_type}}</p></v-col>
                            <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">Life Support:</span> {{customer.property_life_support}}</p></v-col>
                            <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold  ">Tenancy Type:</span> {{customer.property_tenancy_type}}</p></v-col>
                            <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold  ">Solar Powered:</span> {{customer.property_solar_powered}}</p></v-col>
                        </v-row>
                        <v-row class="py-2">
                            <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">ID Type:</span> {{customer.property_id_type}}</p></v-col>
                            <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">EA Response Time:</span> {{customer.property_ea_response_time}}</p></v-col>
                            <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">Estimated moving period:</span> {{customer.estimeted_moving_period}}</p></v-col>
                            <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">User Agree Time:</span>{{customer.user_agree_time}}</p></v-col>
                        </v-row>

                        <v-row class="py-2">
                            <v-col cols="6"><p class="mb-0 profile-info-title"><span class="font-weight-bold profile-info-title">Is Manual Address:</span> {{customer.is_manual_address}}</p></v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" class="px-0">
                <v-card>
                    <v-card-title>
                        <v-row class="align-baseline">
                            <v-col cols="10" class="profile_body_header" >
                                Connection Information
                            </v-col>
                            <v-spacer></v-spacer>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-row>
                            <v-col cols="12" class="py-0"><p class="mb-0"><span class="font-weight-bold  profile-info-title">Id#{{customer.connection_id}}</span></p></v-col>
                            <v-col cols="6" class="py-1">
                                <v-row>
                                    <v-col cols="12" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold">Provider:</span> {{customer.connection_provider}}</p></v-col>
                                    <v-col cols="12" class="py-0"><p class="mb-0 profile-info-title "><span class="font-weight-bold">Selected Plan:</span> {{customer.connection_selected_plan}}</p></v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title "><span class="font-weight-bold">Connection Address:</span></p><p class="profile-info-title">{{customer.connection_address}}</p></v-col>
                        </v-row>
                        <v-row class="py-2">
                            <v-col cols="6" class="py-0 profile-info-title"><p class="mb-0 profile-info-title"><span class="font-weight-bold">Energy Type:</span> {{customer.connection_energy_type}}</p></v-col>
                            <v-col cols="6" class="py-0 profile-info-title"><p class="mb-0 profile-info-title"><span class="font-weight-bold">Gas Provider:</span> {{customer.connection_gas_provider}}</p></v-col>
                            <v-col cols="6" class="py-0 profile-info-title"><p class="mb-0 profile-info-title"><span class="font-weight-bold">E-Destributor:</span> {{customer.e_destributor}}</p></v-col>
                            <v-col cols="6" class="py-0 profile-info-title"><p class="mb-0"><span class="font-weight-bold">Gas meter Reading Charge:</span> {{customer.gas_meter_charge}}</p></v-col>
                            <v-col cols="6" class="py-0 profile-info-title"><p class="mb-0"><span class="font-weight-bold">Electricity Connection Fee:</span> {{customer.connection_electricy_fee}}</p></v-col>
                        </v-row>
                        <v-row class="ml-0" >
                            <v-col cols="6" class="py-0">
                                <v-row class="align-baseline">
                                    <label  class="font-weight-bold profile-info-title">MIRN no:</label>
                                    <v-col class="px-0 pb-0" cols="7"><p class="profile-info-title">{{customer.connection_mern_no}}</p></v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="6" class="py-0">
                                <v-row class="align-baseline">
                                    <label class="font-weight-bold profile-info-title">NMI no:</label>
                                    <v-col class="px-0 pb-0" cols="7"><p class="profile-info-title">{{customer.connection_nmi_no}}</p></v-col>
                                </v-row>
                            </v-col>

                        </v-row>
                        <v-row class="py-0">
                            <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold">Connection Status:</span><span class="rejected-text">{{customer.connection_status}}</span></p></v-col>
                            <v-col cols="12" class="py-0">
                                <p class="mb-0 profile-info-title">Reason:</p>
                                <div cols="12"  class="reason pa-5 profile-info-title">
                                    <p>{{customer.connection_reason}}</p>
                                </div>
                            </v-col>

                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

    </div>
</template>

<script>

import CustomerDetails from "@scripts/models/CustomerDetails";
import CustomerService from "@scripts/services/CustomerService";

export default {
name: "CustomerHelpDesk",
    data() {
        return {
        }
    },
    props: {
        customer: {
            required: true
        }
    },
    methods: {
    },

    mounted() {
    }
}
</script>

<style scoped>
.header {
    display: flex;
    align-items: center;
    line-height: 28px;
    font-size: 16px;
}

.expansion-header-background-active {
    background: linear-gradient(to right bottom, #56CCF2 -75.93%, #542E89 42.76%, #9C27B0 118.83%);
    color:white;
}
.expansion-header-background {
    background: transparent;
}

.profile-title {
    font-size: 14px !important;
    font-weight: 500 !important;
    line-height: 28px;
}

.profile-subtitle {
    font-size: 12px;
    font-weight: 400 !important;
    line-height: 14px;
}
.expand-header-info {
    font-weight: 700;
    font-size: 12px;
}

.profile_body_header {
    font-weight: 500 !important;
    font-size: 14px;
    line-height: 24px;
    color: #252733;
    padding-top: 0px;
    padding-bottom: 0px;
}

.profile-info-title {
    font-size: 12px;
    color: rgba(37, 39, 51, 1);
}

.avatar-containner {
    flex-grow: 0;
}
.body_row {

}
.reason {
    background: rgba(242, 242, 242, 1);
}

.rejected {
    border: 1px solid red;
    color:red !important;
}
.rejected-text {
    color:red !important;
}
.messenger-header {
    display: flex;
    flex-direction: row-reverse;
    align-items: center;
}

</style>
