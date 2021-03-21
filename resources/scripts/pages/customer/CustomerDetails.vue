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

            <v-row class="body_row">
                <v-col cols="6">
                    <v-card>
                        <v-card-title>
                        <v-row>
                            <v-col color="black--text">Property Information</v-col>
                        </v-row>
                        </v-card-title>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Id#1901</span></p></v-col>
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Account Type:</span> Residensial</p></v-col>
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Life Support:</span> Y/N</p></v-col>
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Tenancy Type:</span> Rent</p></v-col>
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Solar Powered:</span> Yes</p></v-col>
                            </v-row>
                            <v-row class="py-2">
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">ID Type:</span> Residensial</p></v-col>
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">EA Response Time:</span> Y/N</p></v-col>
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Estimated moving period:</span> Rent</p></v-col>
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">User Agree Time:</span> Yes</p></v-col>
                            </v-row>

                            <v-row class="py-2">
                                <v-col cols="6"><p class="mb-0"><span class="font-weight-bold black--text">Is Manual Address:</span> Email</p></v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="6">
                    <v-card>
                        <v-card-title>
                            <v-row class="align-baseline">
                                <v-col cols="10">
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
                                <v-col cols="12" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Id#1901</span></p></v-col>
                                <v-col cols="6" class="py-1">
                                    <v-row>
                                        <v-col cols="12" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Provider:</span> Residensial</p></v-col>
<!--                                        <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold">Connection Address:</span></p><p>26 Highpoint, Sunbury VIC3429 Australia</p></v-col>-->
                                        <v-col cols="12" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Selected Plan:</span> Total Plan</p></v-col>
                                    </v-row>
                                </v-col>
<!--                                <v-col cols="6" class="py-1" style="border: 1px solid red;"><p class="mb-0"><span class="font-weight-bold">Provider:</span> Residensial</p></v-col>-->
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Connection Address:</span></p><p>26 Highpoint, Sunbury VIC3429 Australia</p></v-col>
<!--                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold">Selected Plan:</span> Total Plan</p></v-col>-->
                            </v-row>
                            <v-row class="py-2">
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Energy Type:</span> Residensial</p></v-col>
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Gas Provider:</span> Y/N</p></v-col>
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">E-Destributor:</span> Rent</p></v-col>
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Gas meter Reading Charge:</span> Yes</p></v-col>
                            </v-row>
                            <v-row class="ml-0" >
                                <v-col cols="6" class="py-1">
                                    <v-row class="align-baseline">
                                        <label  class="font-weight-bold black--text">MERN no:</label>
                                        <v-col class="px-0 my-0" cols="7"><v-text-field style="border-radius:5px" readonly filled rounded dense></v-text-field></v-col>
                                    </v-row>
                                </v-col>
                                <v-col cols="6" class="py-1">
                                    <v-row class="align-baseline">
                                        <label class="font-weight-bold black--text">NMI no:</label>
                                        <v-col class="px-0" cols="7"><v-text-field style="border-radius:5px" readonly filled rounded dense></v-text-field></v-col>
                                    </v-row>
                                </v-col>

                            </v-row>
                            <v-row class="py-0">
                                <v-col cols="6" class="py-1"><p class="mb-0"><span class="font-weight-bold black--text">Connection Status:</span> Residensial</p></v-col>
                                <v-col cols="12" class="py-1">
                                    <p class="mb-0">Reason:</p>
                                    <div cols="12"  class="reason pa-5">
                                        <p>The Woodman set to work at once, and so sharp was his axe that the tree was soon chopped nearly through.</p>
                                    </div>
                                </v-col>
                                <v-col col="12" class="form-submit">
                                    <div>
                                        <v-btn color="primary" >Submit</v-btn>
                                    </div>
                                </v-col>

                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

        </v-container>
    </v-app>
</template>

<script>

import CustomerDetails from "@scripts/models/CustomerDetails";
import CustomerService from "@scripts/services/CustomerService";

export default {
    name: "CustomerDetials",
    data() {
        return {
                customerinfo: this.getCustomerDetails(),
                customerinfo1:null,
        }
    },

    props: {
        customerId:{
            required: false,
            type: Number
        }
    },

    methods: {
        getCustomerDetails() {
            return new CustomerDetails();
        },

        async getCustomerDetailsData (customerId) {
            this.customerinfo1 = await CustomerService.getCustomerDetails(customerId);
            console.log('hello world');
            console.log(this.customerinfo1);
            return this.customerinfo1;
        }
    },
    mounted() {
        this.getCustomerDetailsData(this.customerId);
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
    background: linear-gradient(to right bottom, #56CCF2 -75.93%, #542E89 42.76%, #9C27B0 118.83%);
    color: white;
    line-height: 28px;
    font-size: 16px;
}

.header-avater {
    display: flex;
    align-items: center;
}

.form-submit{
    display: flex;
    flex-direction: row-reverse;
}
.avatar-containner {
    flex-grow: 0;
}
.last-interactive {
    font-size: 14px;
}
.body_row {
    background: rgba(227, 224, 231, 1);

}
.reason {
    background: rgba(242, 242, 242, 1);
}



</style>
