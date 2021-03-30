<template>
    <v-app>
        <v-row>
            <v-app-bar>
                <v-icon @click="goBack">keyboard_backspace</v-icon>
            </v-app-bar>
        </v-row>
        <v-container class="pt-0 container-background">
            <v-row align-center class="header color--text">
                <v-col class="avatar-containner pr-0">
                    <v-avatar>
                        <v-img v-bind:src="customerinfo.profile_pic" class="rejected"/>
                    </v-avatar>
                </v-col>
                <v-col cols="10" class="pt-0 pt-5">
                    <h3 class="mb-0 font-weight-bold profile-title">{{customerinfo.name}}</h3>
                    <p class="mb-0 last-interactive profile-subtitle">Interact {{customerinfo.last_interactive_time}}</p>
                </v-col>
                <v-col cols="5" >
                    <p class="mb-0">HOOD UID: {{customerinfo.hood_uid}}</p>
                    <p class="mb-0">Messenger ID: {{customerinfo.messager_id}}</p>
                </v-col>
                <v-col cols="5">
                    <p class="mb-0">Email: {{customerinfo.email}}</p>
                    <p class="mb-0">Ph: {{customerinfo.ph}}</p>
                </v-col>
            </v-row>

            <v-row class="gray-bg">
                <v-col cols="6">
                    <v-card>
                        <v-card-title>
                        <v-row>
                            <v-col color="black--text" class="profile_body_header">Property Information</v-col>
                        </v-row>
                        </v-card-title>
                        <v-card-text>
                            <v-row class="pb-2">
                                <v-col cols="12" class="py-0"><p class="mb-0 profile-info-title"><span class=" font-weight-bold">Id#{{customerinfo.property_profile_id}}</span></p></v-col>
                                <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">Account Type:</span> {{customerinfo.property_account_type | mapAccountType}}</p></v-col>
                                <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">Life Support:</span> {{customerinfo.property_life_support}}</p></v-col>
                                <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold  ">Tenancy Type:</span> {{customerinfo.property_tenancy_type}}</p></v-col>
                                <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold  ">Solar Powered:</span> {{customerinfo.property_solar_powered}}</p></v-col>
                            </v-row>
                            <v-row class="py-2">
                                <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">ID Type:</span> {{customerinfo.property_id_type}}</p></v-col>
                                <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">EA Response Time:</span> {{customerinfo.property_ea_response_time}}</p></v-col>
                                <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">Estimated moving period:</span> {{customerinfo.property_estimated_moving_period}}</p></v-col>
                                <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold ">User Agree Time:</span>{{customerinfo.user_agree_time}}</p></v-col>
                            </v-row>

                            <v-row class="py-2">
                                <v-col cols="6"><p class="mb-0 profile-info-title"><span class="font-weight-bold profile-info-title">Is Manual Address:</span> {{customerinfo.is_manual_address}}</p></v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="6">
                    <v-card>
                        <v-card-title>
                            <v-row class="align-baseline">
                                <v-col cols="10" class="profile_body_header" >
                                    Connection Information
                                </v-col>
                                <v-spacer></v-spacer>
                                <v-btn small depressed>
                                    <v-icon small>mdi-pencil</v-icon>
                                </v-btn>
                            </v-row>
                        </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" class="py-0"><p class="mb-0"><span class="font-weight-bold  profile-info-title">Id#{{customerinfo.connection_id}}</span></p></v-col>
                                <v-col cols="6" class="py-1">
                                    <v-row>
                                        <v-col cols="12" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold">Provider:</span> {{customerinfo.connection_provider}}</p></v-col>
                                        <v-col cols="12" class="py-0"><p class="mb-0 profile-info-title "><span class="font-weight-bold">Selected Plan:</span> {{customerinfo.connection_selected_plan}}</p></v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title "><span class="font-weight-bold">Connection Address:</span></p><p class="profile-info-title">{{customerinfo.connection_address}}</p></v-col>
                            </v-row>
                            <v-row class="py-2">
                                <v-col cols="6" class="py-0 profile-info-title"><p class="mb-0 profile-info-title"><span class="font-weight-bold">Energy Type:</span> {{customerinfo.connection_energy_type | mapEnergyType}}</p></v-col>
                                <v-col cols="6" class="py-0 profile-info-title"><p class="mb-0 profile-info-title"><span class="font-weight-bold">Gas Provider:</span> {{customerinfo.connection_gas_provider}}</p></v-col>
                                <v-col cols="6" class="py-0 profile-info-title"><p class="mb-0 profile-info-title"><span class="font-weight-bold">E-Destributor:</span> {{customerinfo.e_destributor}}</p></v-col>
                                <v-col cols="6" class="py-0 profile-info-title"><p class="mb-0"><span class="font-weight-bold">Gas meter Reading Charge:</span> {{customerinfo.gas_meter_charge}}</p></v-col>
                                <v-col cols="6" class="py-0 profile-info-title"><p class="mb-0"><span class="font-weight-bold">Electricity Connection Fee:</span> {{customerinfo.connection_electricy_fee}}</p></v-col>
                            </v-row>
                            <v-row class="ml-0" >
                                <v-col cols="6" class="py-0">
                                    <v-row class="align-baseline">
                                        <label  class="font-weight-bold profile-info-title">MIRN no:</label>
                                        <v-col class="px-0 pb-0" cols="7"><v-text-field  class="border-radious-5" readonly filled rounded dense v-model="customerinfo.connection_mern_no"></v-text-field></v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="6" class="py-0">
                                    <v-row class="align-baseline">
                                        <label class="font-weight-bold profile-info-title">NMI no:</label>
                                        <v-col class="px-0 pb-0" cols="7"><v-text-field  class="border-radious-5" readonly filled rounded dense v-model="customerinfo.connection_nmi_no"></v-text-field></v-col>
                                    </v-row>
                                </v-col>

                            </v-row>
                            <v-row class="py-0">
                                <v-col cols="6" class="py-0"><p class="mb-0 profile-info-title"><span class="font-weight-bold">Connection Status:</span><span class="rejected-text">{{customerinfo.connection_status}}</span></p></v-col>
                                <v-col cols="12" class="py-0">
                                    <p class="mb-0 profile-info-title">Reason:</p>
                                    <div cols="12"  class="reason pa-5 profile-info-title">
                                        <p>{{customerinfo.connection_reason}}</p>
                                    </div>
                                </v-col>
                                <v-col col="12" class="form-submit">
                                    <div>
                                        <v-btn color="primary" >Save</v-btn>
                                    </div>
                                </v-col>

                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

        </v-container >
    </v-app>
</template>

<script>

import CustomerDetails from "@scripts/models/CustomerDetails";
import CustomerService from "@scripts/services/CustomerService";
import {mapEnergyType, mapAccountType} from "@scripts/data/CustomerDataMapper";

export default {
    name: "CustomerDetials",
    data() {
        return {
                customerinfo: this.getCustomerDetails(),
                customerinfo1:null,
        }
    },

    props: {
        id:{
            required: false,
        },
    },

    methods: {
        goBack() {
            this.$router.go(-1);
        },
        getCustomerDetails() {
            return new CustomerDetails();
        },

        async getCustomerDetailsData (id) {
            this.customerinfo = await CustomerService.getCustomerDetails(id);
        }
    },
    mounted() {
        this.getCustomerDetailsData(this.id);
    },
    filters: {
        mapEnergyType (value) {
            if(value) {
                return mapEnergyType(value);
            }
           return '';
        },
        mapAccountType (value) {
            if(value) {
                return mapAccountType(value);
            }
           return '';
        }
    }
}
</script>

<style scoped>
body {
    font-family: "Roboto" !important;
}
.gray-bg {
    background: rgb(242, 242, 242);
}
.header {
    display: flex;
    align-items: center;
    background: linear-gradient(to right bottom, #56CCF2 -75.93%, #542E89 42.76%, #9C27B0 118.83%);
    color: white;
    line-height: 28px;
    font-size: 16px;
    margin-top: 30px !important;
}

.profile-title {
    font-size: 24px !important;
}

.profile-subtitle {
    font-size: 16px;
    font-weight: 400 !important;
}

.profile_body_header {
    font-size: 14px;
    font-weight: 500 !important;
    color: rgba(37, 39, 51, 1);
    padding-top: 0px;
    padding-bottom: 0px;
}

.profile-info-title {
    font-size: 12px;
    color: rgba(37, 39, 51, 1);
}

.form-submit{
    display: flex;
    flex-direction: row-reverse;
}
.avatar-containner {
    flex-grow: 0;
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
.border-radious-5 {
    border-radius: 5px;
}


</style>
