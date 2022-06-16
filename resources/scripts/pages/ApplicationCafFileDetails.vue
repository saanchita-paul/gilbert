<template>
    <div v-if="cafFileData" className="containerRootClass">
        <v-row>
            <v-col cols="6">
                <v-icon small color="red">mdi-phone-off-outline</v-icon>
                Waiting for consent...
            </v-col>
            <v-col cols="6" style="text-align:right">
                <v-btn
                    color="teal"
                    outlined
                >
                    <v-icon left>
                        mdi-pencil
                    </v-icon>
                    Edit
                </v-btn>
                <v-btn
                    color="success"
                >
                    <v-icon left>
                        mdi-checkbox-marked-circle
                    </v-icon>
                    Save
                </v-btn>
                <v-btn
                    color="error"
                >
                    <v-icon left>
                        mdi-cancel
                    </v-icon>
                    Cancel
                </v-btn>
            </v-col>
        </v-row>

        <v-row no-gutters>
            <v-col cols="4">
                <h4 className="header">Personal Details</h4>
                <div>
                    <div className="item">
                        <p className="item-title">Homephone</p>
                        <p className="item-value"></p>
                    </div>
                    <div className="item">
                        <p className="item-title">Mobile</p>
                        <p className="item-value"></p>
                    </div>
                    <div className="item">
                        <p className="item-title">I. Mobile Number</p>
                        <p className="item-value"></p>
                    </div>
                </div>
                <div className="item">
                    <p className="item-title">Date of Birth</p>
                    <p className="item-value"></p>
                </div>
                <div className="item">
                    <p className="item-title">Email</p>
                    <p className="item-value"></p>
                </div>
                <div className="item">
                    <p className="item-title">Moving Date</p>
                    <p className="item-value"></p>
                </div>
                <div className="item">
                    <p className="item-title">Billing</p>
                    <p className="item-value"></p>
                </div>
                <div className="item">
                    <p className="item-title">Identification</p>
                    <p className="item-value">

                    </p>
                </div>

            </v-col>
            <v-col cols="4" className="hr-bar pl-2">
                <h4 className="header">Property Details</h4>
                <div className="item">
                    <p className="item-title">Occupancy Type</p>
                    <p className="item-value"></p>
                </div>
                <div className="item">
                    <p className="item-title">Service Address:</p>
                    <p className="item-value">

                    </p>
                </div>
                <div className="item">
                    <p className="item-title">Billing Address</p>
                    <p className="item-value">

                    </p>
                </div>

                <div>
                    <p className="preferenceTitle mt-4 mb-2">Service Preference</p>
                    <v-row>

                        <v-col className="my-0 py-0 mx-0">
                            <p className="pt-2 pb-1 mb-0 services">
                  <span className="ml-1">
                      <v-icon color="yellow">mdi-flash</v-icon>Power
                  </span>
                            </p>
                            <p className="py-0 my-0 pl-5 service-status active-power-subtitle"
                            >

                                <!--                    Connected-->
                            </p>
                        </v-col>

                        <v-col className="my-0 py-0 mx-0">
                            <p className="pt-2 pb-1 mb-0 services">
                  <span className="ml-1">
                      <v-icon color="red">mdi-fire</v-icon>Gas
                  </span>
                            </p>
                            <p className="py-0 my-0 pl-5 service-status active-power-subtitle"
                            >

                                <!--                    Connected-->
                            </p>
                        </v-col>
                        <v-col className="my-0 py-0 mx-0">
                            <p className="pt-2 pb-1 mb-0 services">
                  <span className="ml-1">
                      <v-icon color="blue">mdi-water</v-icon>Water
                  </span>
                            </p>
                            <p className="py-0 my-0 pl-5 service-status "
                            >

                                <!--                    Connected-->
                            </p>
                        </v-col>
                        <v-col className="my-0 py-0 mx-0">
                            <p className="pt-2 pb-1 mb-0 services">
                  <span className="ml-1">
                       <v-icon color="green">mdi-wifi</v-icon>Internet
                  </span>
                            </p>
                            <p className="py-0 my-0 pl-5 service-status active-power-subtitle"
                            >

                                <!--                    Connected-->
                            </p>
                        </v-col>


                    </v-row>
                </div>

            </v-col>
            <v-col cols="4" className="hr-bar pl-2">
                <h4 className="header">Agent's Additional Instructions</h4>
                <div className="item">
                    <p className="item-title">Agency Office</p>
                    <p className="item-value"></p>
                </div>
                <div className="item">
                    <p className="item-title">Additional Instructions</p>
                    <p className="item-value">

                    </p>
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<script>

export default {
    name: "ApplicationCafFileDetails",
    props: ["cafFileData"],
    components: {},
    data() {
        return {};
    },
    computed: {},
    methods: {},
    mounted() {
    }
};
</script>

<style scoped>
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
