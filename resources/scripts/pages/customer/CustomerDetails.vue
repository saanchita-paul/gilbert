<template>
    <v-app>
        <v-container>
            <v-row align-center class="header color--text">
                <v-col class="avatar-containner">
                    <v-avatar>
                        <v-img src="https://fiverr-res.cloudinary.com/images/q_auto,f_auto/gigs/106438752/original/3c4d95e3604313ecca407541a45b6a58dcc67c5c/update-your-online-dating-profile-bio-to-get-you-more-matches.jpg"/>
                    </v-avatar>
                </v-col>
                <v-col cols="10" class="pt-0 pt-5">
                    <h3 class="mb-0">{{customerinfo.name}}</h3>
                    <p class="mb-0 last-interactive">interact {{customerinfo.last_interactive_time}} ago</p>
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
            <v-row class="mt-7">
                <v-col cols="12"><h3>Profile Info:</h3></v-col>
                <v-col cols="5" class="py-1"><p class="mb-5"><span class="font-weight-bold">Id:</span>{{customerinfo.property_profile_id}}</p></v-col>
                <v-col cols="5" class="py-1">
                    <p class="mb-0"><span class="font-weight-bold">Estimated moving period:</span></p>
                    <p class="mb-5">{{customerinfo.property_estimated_moving_period}}</p>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="5" class="py-1">
                    <p class="mb-0"><span class="font-weight-bold">Current Address:</span></p>
                    <p class="mb-5">{{customerinfo.property_currnt_address}}</p>
                </v-col>
                <v-col cols="5" class="py-1">
                    <p class="mb-0"><span class="font-weight-bold">Current Address:</span></p>
                    <p class="mb-5">{{customerinfo.property_previous_address}}</p>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="5" class="py-1"><p><span class="font-weight-bold">Tenancy Type:</span> {{customerinfo.property_tenancy_type}}</p></v-col>
                <v-col cols="5" class="py-1"><p><span class="font-weight-bold">House Type:</span>{{customerinfo.property_house_type}}</p></v-col>
                <v-col cols="5" class="py-1"><p><span class="font-weight-bold">House Size:</span>{{customerinfo.property_house_size}}</p></v-col>
                <v-col cols="5" class="py-1"><p><span class="font-weight-bold">Solar Powered:</span>{{customerinfo.property_solar_powered}}</p></v-col>
                <v-col cols="5" class="py-1"><p><span class="font-weight-bold">Life Support:</span>{{customerinfo.property_life_support}}</p></v-col>
            </v-row>
            <v-divider></v-divider>
            <v-row>
                <v-col cols="12" class="mt-7"><h3>Connection Info:</h3></v-col>
                <v-col cols="5" class="py-3">
                    <v-row>
                        <v-col cols="10" class="py-1"><p class="mb-5"><span class="font-weight-bold">Id:</span>{{customerinfo.connection_id}}</p></v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="10" class="py-1"><p class="mb-5"><span class="font-weight-bold">Provider:</span> {{customerinfo.connection_provider}}</p></v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="10" class="py-1"><p class="mb-5"><span class="font-weight-bold">Selected Plan: </span>{{customerinfo.connection_selected_plan}}</p></v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="10" class="py-1"><p class="mb-5"><span class="font-weight-bold">Energy type:</span> {{customerinfo.connection_energy_type}}</p></v-col>
                    </v-row>
                </v-col>
                <v-col cols="5">
                    <v-row>
                        <v-col cols="10" class="py-1"><p class="mb-0"><span class="font-weight-bold">Connection Address:</span></p><p>{{customerinfo.connection_address}}</p></v-col>
                    </v-row>
                    <v-row>
                        <v-col>

                                <v-text-field
                                    filled
                                    label="MIRN No:"
                                    append-outer-icon="mdi-pencil"
                                ></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <label>
                                <v-text-field
                                    label="NMI No:"
                                    append-outer-icon="mdi-pencil"
                                ></v-text-field>
                            </label>
                        </v-col>
                    </v-row>
                </v-col>
                <v-col col="1">
                    <div class="form-submit">
                        <v-btn color="primary" >Submit</v-btn>
                    </div>
                </v-col>

            </v-row>
        </v-container>
    </v-app>
</template>

<script>

import CustomerDetails from "@scripts/models/CustomerDetails";

export default {
    name: "CustomerDetials",
    data() {
        return {
                customerinfo: this.getCustomerDetails()
        }
    },

    methods: {
        getCustomerDetails() {
            return new CustomerDetails();
        }
    }
}
</script>

<style scoped>
body {
    font-family: "Roboto" !important;
}
.header {
    display: flex;
    align-items: center;
    background: linear-gradient(133.34deg, #56CCF2 -75.93%, #542E89 42.76%, #9C27B0 118.83%);
    color: white;
    line-height: 28px;
    font-size: 16px;
}

.header-avater {
    display: flex;
    align-items: center;
}

.form-submit{
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column-reverse;
    max-width: 100px;
}
.avatar-containner {
    flex-grow: 0;
}
.last-interactive {
    font-size: 14px;
}



</style>
