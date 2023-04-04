<template>
   <v-card class="hood-card" v-if="lead">
        <h3 class="page-title">{{lead.applicant_name}}</h3>
        <IdCopyToClipboard :applicationId="lead.id"/>

     <v-row>
       <v-col> <p class="sub-title mt-4 mb-4">Personal Details</p> </v-col>
       <v-col v-if="lead.is_duplicate"> <v-btn class="mt-2 view_application" text  @click="showDuplicatesMessage"> View all duplicates</v-btn> </v-col>
     </v-row>

       <v-row>

           <v-col cols="5" class="py-0 my-0">
                   <p class="font-weight-bold">Call Status</p>
           </v-col>
           <v-col cols="7"  class="py-0 my-0">
               <p>{{lead.tsa_call_status}}</p>
           </v-col>

           <v-col cols="5" class="py-0 my-0">
                   <p class="font-weight-bold">Date of Birth</p>
           </v-col>
           <v-col cols="7"  class="py-0 my-0">
               <p>{{date_of_birth}}</p>
           </v-col>

           <v-col  v-if="lead.phone_type == 1" cols="5"  class="py-0 my-0">
               <p class="font-weight-bold"> Mobile </p>
           </v-col>
           <v-col v-if="lead.phone_type == 1" cols="7"  class="py-0 my-0">
               <p>{{lead.phone}}</p>
           </v-col>

           <v-col cols="5" v-if="lead.phone_type === 2"  class="py-0 my-0">
               <p class="font-weight-bold"> Homephone </p>
           </v-col>

           <v-col cols="7" v-if="lead.phone_type === 2"  class="py-0 my-0">
               <p>{{lead.homephone}}</p>
           </v-col>

           <v-col cols="5" v-if="lead.phone_type === 3"  class="py-0 my-0">
               <p class="font-weight-bold"> I. Mobile Number </p>
           </v-col>

           <v-col cols="7" v-if="lead.phone_type === 3"  class="py-0 my-0">
               <p>{{lead.international_phone}}</p>
           </v-col>


           <v-col cols="5"  class="py-0 my-0">
               <p class="font-weight-bold">Email</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">

               <p>{{lead.email}}</p>
           </v-col>


           <v-col cols ="5"  class="py-0 my-0">
               <p class="font-weight-bold">Moving Date</p>
           </v-col>
           <v-col cols ="7" class="py-0 my-0">
               <p>{{moving_data}}</p>
           </v-col>


           <v-col cols ="5" class="py-0 my-0">
               <p class="font-weight-bold">Email billing</p>
           </v-col>
           <v-col cols ="7" class="py-0 my-0">
               <p>{{ lead.is_email_billing == emailBillingMapper.EMAIL_BILLING_EMAIL ?'Email': lead.is_email_billing == emailBillingMapper.EMAIL_BILLING_PAPER  ? 'Paper' : '' }}</p>
           </v-col>


           <v-col cols="5" class="py-0 my-0">
               <p class="font-weight-bold">Authorized person</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">
               <p>{{ lead.authorizedPersonName == null ? 'No Authorised Person' : lead.authorizedPersonName }}</p>
           </v-col>

           <v-col cols="5" class="py-0 my-0">
               <p class="font-weight-bold">Status</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">
               <p>{{ lead.status }}</p>
           </v-col>
       </v-row>


        <v-btn block class="my-4" color="primary" @click="goToLeadDetails(lead.id)">View Application Details</v-btn>

        <p class="sub-title mt-4 mb-2">Property Details</p>
<!--        <table  class="application-info">-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Tenancy Type:</td>-->
<!--                <td>{{lead.tenancy_type == 1? 'Renter': 'Owner'}}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Service Address:</td>-->
<!--                <td>{{lead.address_text}}</td>-->
<!--            </tr>-->
<!--        </table>-->


       <v-row>
           <v-col cols="5" class="py-0 my-0">
               <p class="font-weight-bold">Occupancy Type:</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">
               <p>{{lead.tenancy_type == 1? 'Renter': 'Owner'}}</p>
           </v-col>
           <v-col cols="5" class="py-0 my-0" v-if="lead.is_temporary_connection">
               <p class="font-weight-bold">Connection Type:</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0" v-if="lead.is_temporary_connection">
               <p>Temporary Connection</p>
           </v-col>
           <v-col cols="5" class="py-0 my-0">
               <p class="font-weight-bold">Service Address:</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">
               <p>{{lead.address_text}}</p>
               <span class="error--text" v-if="lead.embedded_nmi === 1">
                  <v-icon color="error">
                      info
                  </v-icon>
                  The electricity at this address is in an <strong>Embedded network.</strong>
              </span>
           </v-col>
       </v-row>



        <v-divider class="mt-4 mb-2"></v-divider>
       <!-- <p class="sub-title py-2">Service Interests
           <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(lead.service_interests, 'power')" color="yellow">mdi-flash</v-icon>

          </span>
           <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(lead.service_interests, 'gas')" color="red">mdi-fire</v-icon>
          </span>
           <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(lead.service_interests, 'internet')" color="green">mdi-wifi</v-icon>
          </span>
           <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(lead.service_interests, 'water')" color="blue" >mdi-water</v-icon>
          </span>
       </p> -->
       <p class="sub-title mt-4 mb-2">Service Preference</p>
        <div class="d-flex flex-wrap-100" >
            <div  class="my-0 py-0 mx-0 border-all">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-0">
                      <v-icon :disabled="isServiceAllowed(lead.service_interests, 'power')" color="yellow" size="17">mdi-flash</v-icon> Power
                  </span>
                </p>
                <p class="py-0 my-0 service-status" :class="getServiceClass('power')">
                    {{getServiceStatus('power')}}
                </p>
            </div>

            <div  class="my-0 py-0 mx-0 border-all">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-0">
                      <v-icon :disabled="isServiceAllowed(lead.service_interests, 'gas')" color="red" size="17">mdi-fire</v-icon> Gas
                  </span>
                </p>
                <p class="py-0 my-0 service-status" :class="getServiceClass('gas')">
                    {{getServiceStatus('gas')}}
                </p>
            </div>
            <div  class="my-0 py-0 mx-0 border-all">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-0">
                       <v-icon  :disabled="isServiceAllowed(lead.service_interests, 'internet')" color="green" size="17">mdi-wifi</v-icon> Internet
                  </span>
                </p>
                <p class="py-0 my-0 service-status" :class="getServiceClass('internet')">
                    {{getServiceStatus('internet')}}
                </p>
            </div>
            <div  class="my-0 py-0 mx-0 border-all">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-0">
                      <v-icon :disabled="isServiceAllowed(lead.service_interests, 'water')" color="blue" size="17">mdi-water</v-icon> Water
                  </span>
                </p>
                <p class="py-0 my-0 service-status" :class="getServiceClass('water')">
                    {{getServiceStatus('water')}}
                </p>
            </div>
        </div>

       <v-row>
           <v-col cols="12">
               <p class="sub-title mt-4 mb-2">Agent Details</p>
           </v-col>
           <v-col cols="5" class="py-0 my-0">
               <p class="font-weight-bold">Agent Name:</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">
               <p>{{lead.agent_name}}</p>
           </v-col>
           <v-col cols="5" class="py-0 my-0">
               <p class="font-weight-bold">Agency Office:</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">
               <p>{{lead.agency_office}}</p>
           </v-col>
       </v-row>

    </v-card>
</template>

<script>
import dayJs from "dayjs";
import { leadSourceMap } from '@scripts/data/LeadSourceMap';
import { emailBillingMapper } from '@scripts/data/ConnectionApplicationMapper';
import IdCopyToClipboard from '@scripts/components/common/IdCopyToClipboard.vue';
export default {
  name: "ApplicationDetails",
    components:{IdCopyToClipboard},
    props: {
      lead: {
          required: true
      }
    },
    methods: {
        getServiceStatus(conn_ser) {
            let service = this.lead.connection_services.find((svc)=>{
                return svc.service_type === conn_ser;
            })
            if(service) {
                return this.mapConnectionStatus(service.statusText);
            }
            return '';
        },
        getServiceClass(conn_ser) {
            let service = this.lead.connection_services.find((svc)=>{
                return svc.service_type === conn_ser;
            })
            if(service) {
                return service.statusText;
            }
            return 'common_color';
        },
        mapConnectionStatus(status) {
            // return ['unassigned','assigned', 'escalated'].includes(status)?'In Progress':
            //     status[0].toUpperCase() + status.slice(1);
            if(['unassigned', 'assigned', 'escalated', 'processing'].includes(status)) {
                return 'In Progress';
            } else if(status === 'accepted') {
                return 'Connected';
            } else if(status === 'failed') {
                return 'Manual Processing';
            } else {
                return (status[0].toUpperCase() + status.slice(1)).replace(/_/g, " ");
            }
        },
        goToLeadDetails(id) {
            this.$router.push({name:'applications.details', params:{id:id}});
        },
        isServiceAllowed(services, type) {
            return !services.includes(type);
        },
      showDuplicatesMessage() {
          if(this.$route.query.duplication_group_id === this.lead.duplication_group_id) {
              return;
          }
          const query = { ...this.$route.query, duplication_group_id: this.lead.duplication_group_id };
          this.$router.replace({ query })
      }
    },
    computed: {
      emailBillingMapper(){
          return emailBillingMapper;
      },
      leadSourceMap(){
          return leadSourceMap;
      },
      date_of_birth() {
          return dayJs(dayJs(this.lead.date_of_birth,'YYYY-MM-DD').format('DD/MM/YYYY')).isValid() ? dayJs(this.lead.date_of_birth,'YYYY-MM-DD').format('DD/MM/YYYY') : null;
      },
        moving_data() {
            return dayJs(this.lead.moving_date,'YYYY-MM-DD').format('DD/MM/YYYY');
        }
    },
     mounted(){
        // console.log('leads' ,  this.lead);
    }
};
</script>

<style scoped>
.layout-fixed-table{
    table-layout: fixed;
    width: 100%
}
.service-status{
    font-size: 14px !important;
}
.services{
    font-size: 15px !important;
    font-weight: 700;
}
.unassigned, .assigned, .escalated, .processing, .common_color, .closed, .can\'t_connect{
    color: black !important;
}
.submitted{
    color: #0CC4ED !important;
}
.accepted{
    color: #15DB64 !important;
}
.rejected{
    color: #E91E63 !important;
}
.need_more_info{
    color: #FF5722 !important;
}

.view_application {
    background: #FFC104
}

.border-all{
    /* border: 1px solid black; */
    flex-basis: 31%;
}

.flex-wrap-100{
    flex-wrap: wrap;
    width: 100%;
}
</style>
