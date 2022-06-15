<template>
    <div class="containerRootClass">
        <v-row no-gutters>
            <v-col cols="6">
<!--                <CopyToClipboard title="Name" />-->
<!--                <IdCopyToClipboard class="mb-3" />-->
            </v-col>
            <!-- <v-col cols="6" style="text-align:right">
                <v-icon small color="red">mdi-phone-off-outline</v-icon>
                Waiting for consent...
            </v-col> -->
        </v-row>
        <v-row no-gutters>
            <v-col cols="4">
                <h4 class="header">Personal Details</h4>
                <div>
                    <div class="item" >
                        <p class="item-title">Homephone</p>
                        <p class="item-value"></p>
                    </div>
                    <div class="item" >
                        <p class="item-title">Mobile</p>
                        <p class="item-value"></p>
                    </div>
                    <div class="item" >
                        <p class="item-title">I. Mobile Number</p>
                        <p class="item-value"></p>
                    </div>
                </div>
                <div class="item">
                    <p class="item-title">Date of Birth</p>
                    <p class="item-value"></p>
                </div>
                <div class="item">
                    <p class="item-title">Email</p>
                    <p class="item-value"></p>
                </div>
                <div class="item">
                    <p class="item-title">Moving Date</p>
                    <p class="item-value"></p>
                </div>
                <div class="item">
                    <p class="item-title">Billing</p>
                    <p class="item-value"></p>
                </div>
                <div class="item">
                    <p class="item-title">Identification</p>
                    <p class="item-value">

                    </p>
                </div>
                <!-- <div class="item">
                    <p class="item-title">Consent Sent</p>
                    <p class="item-value">02/22/2022 HH:MM</p>
                </div>
                <div class="item">
                    <p class="item-title">Consent Received</p>
                    <p class="item-value">DD/MM/YYYY HH:MM (Email)</p>
                </div> -->
            </v-col>
            <v-col cols="4" class="hr-bar pl-2">
                <h4 class="header">Property Details</h4>
                <div class="item">
                    <p class="item-title">Occupancy Type</p>
                    <p class="item-value"></p>
                </div>
                <div class="item">
                    <p class="item-title">Service Address:</p>
                    <p class="item-value">

                    </p>
                </div>
                <div class="item">
                    <p class="item-title">Billing Address</p>
                    <p class="item-value">

                    </p>
                </div>

                <div >
                    <p class="preferenceTitle mt-4 mb-2">Service Preference</p>
                            <v-row>

                        <v-col  class="my-0 py-0 mx-0">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-1">
                      <v-icon color="yellow">mdi-flash</v-icon>Power
                  </span>
                </p>
                <p class="py-0 my-0 pl-5 service-status active-power-subtitle"
                    >

<!--                    Connected-->
                </p>
            </v-col>

            <v-col  class="my-0 py-0 mx-0">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-1">
                      <v-icon  color="red">mdi-fire</v-icon>Gas
                  </span>
                </p>
                <p class="py-0 my-0 pl-5 service-status active-power-subtitle"
                   >

<!--                    Connected-->
                </p>
            </v-col>
            <v-col  class="my-0 py-0 mx-0">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-1">
                      <v-icon  color="blue" >mdi-water</v-icon>Water
                  </span>
                </p>
                <p class="py-0 my-0 pl-5 service-status "
                   >

<!--                    Connected-->
                </p>
            </v-col>
            <v-col  class="my-0 py-0 mx-0">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-1">
                       <v-icon  color="green">mdi-wifi</v-icon>Internet
                  </span>
                </p>
                <p class="py-0 my-0 pl-5 service-status active-power-subtitle"
                >

<!--                    Connected-->
                </p>
            </v-col>




        </v-row>
                </div>

            </v-col>
            <v-col cols="4" class="hr-bar pl-2">
                <h4 class="header">Agent's Additional Instructions</h4>
                <div class="item">
                    <p class="item-title">Agency Office</p>
                    <p class="item-value"></p>
                </div>
                <div class="item">
                    <p class="item-title">Additional Instructions</p>
                    <p class="item-value">

                    </p>
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import dayjs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import IDENTIFICATION from "@scripts/data/constants/IDENTIFICATION";
import IdCopyToClipboard from '@scripts/components/common/IdCopyToClipboard.vue';
import CopyToClipboard from '@scripts/components/common/CopyToClipboard.vue';

export default {
    name: "ApplicationCafFileDetails",
    props: [],
    components: {
      IdCopyToClipboard,
      CopyToClipboard
    },
    data() {
        return {};
    },
    computed: {
        // applicant_name() {
        //     let title = this.application.title ? this.application.title + ' ' : '';
        //     let first_name = this.application.first_name ? this.application.first_name + ' ' : '';
        //     let middle_name = this.application.middle_name ? this.application.middle_name + ' ' : '';
        //     let last_name =  this.application.last_name ? this.application.last_name : '';
        //     return title + first_name + middle_name + last_name;
        // },
        // date_of_birth() {
        //   return this.application.date_of_birth
        //     ? dayjs(this.application.date_of_birth,'YYYY-MM-DD').format(DATE_FORMAT.DB_MONTH_FIRST)
        //     : null;
        // },
        // moving_date() {
        //   return this.application.moving_date
        //     ? dayjs(this.application.moving_date,'DD/MM/YYYY').format(DATE_FORMAT.DB_MONTH_FIRST)
        //     : null;
        // },
        // identification_type() {
        //     switch (this.application?.identification?.type) {
        //       case IDENTIFICATION.PASSPORT:
        //         return 'Passport';
        //       case IDENTIFICATION.DL:
        //         return 'DL';
        //       case IDENTIFICATION.MEDICARE:
        //         return 'Medicare';
        //       default:
        //         return '';
        //     }
        // },
        // identification_number() {
        //     return this.application?.identification?.card_number;
        // },
        // identification_expire_date() {
        //     return this.application?.identification?.expire_date
        //       ? 'Expires on ' + dayjs(this.application?.identification?.expire_date, 'YYYY-MM-DD').format('MM/YY')
        //       : null;
        // },
    },
        methods: {
        // getSubtitleColor(name){
        //     let status = this.getServiceStatus(name)
        //     if(status == 'In Progress'){
        //         return 'inprogress-color';
        //     }else if(status == 'Submitted'){
        //         return 'submitted-color';
        //     }else if(status == 'Rejected'){
        //         return 'rejected-color';
        //     }
        // },
        // isServiceAllowed(services, type) {
        //     return !services.includes(type);
        // },

        // getServiceStatus(conn_ser) {
        //     let service = this.application.connection_services.find((svc)=>{
        //         return svc.service_type === conn_ser;
        //     })
        //     if(service) {
        //         return this.mapConnectionStatus(service.statusText);
        //     }
        //     return '';
        // },

        // mapConnectionStatus(status) {
        //     return ['unassigned','assigned', 'escalated','processing'].includes(status)?'In Progress':
        //         status[0].toUpperCase() + status.slice(1);
        // }
    },
    mounted(){
        // console.log("application", this.application)
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

.application-consent{
    color:green !important;
    font-size: 14px !important;
}
.application-consent-waiting{
    color:#E91E63 !important;
    font-size: 14px !important;
}
.containerRootClass{
    background-color: #FAFAFE;
    margin-left: -12px;
    padding: 20px 30px;
}

.layout-fixed-table{
    table-layout: fixed;
    width: 100%
}
.service-status{
    font-size: 10px;
    font-weight: 400;
}
.services{
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
.preferenceTitle{
    font-size: 16px;
    font-weight: 700;
}

.submitted-color{
    color: #0CC4ED;
}

.connected-color{
    color: #16A948;
}

.needinfo-color{
    color: #16A948;
}

.inprogress-color{
    color: #263238;
}

.rejected-color{
    color: #E91E63;
}

</style>
