<template>
   <v-card class="hood-card" v-if="lead">
        <h3 class="page-title">{{lead.applicant_name}}</h3>
        <p class="sub-title mt-4 mb-2">Personal Details</p>
<!--        <table  class="application-info layout-fixed-table">-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Date of Birth</td>-->
<!--                <td>{{lead.date_of_birth}}</td>-->
<!--            </tr>-->
<!--            <tr v-if="lead.phone_type == 1">-->
<!--                <td class="font-weight-bold"> Mobile </td>-->
<!--                <td>{{lead.phone}}</td>-->
<!--            </tr>-->
<!--            <tr v-else>-->
<!--                <td class="font-weight-bold"> Homephone </td>-->
<!--                <td>{{lead.homephone}}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Email</td>-->
<!--                <td>{{lead.email}}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Moving Date</td>-->
<!--                <td>{{lead.moving_date}}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Email billing</td>-->
<!--                <td>{{ lead.is_email_billing == 1?'Email':'Paper' }}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Authorized Person</td>-->
<!--                <td>{{ lead.authorizedPersonName == null ? 'Unassigned' : lead.authorizedPersonName }}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Status:</td>-->
<!--                <td>{{ lead.status }}</td>-->
<!--            </tr>-->
<!--        </table>-->


       <v-row>

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

           <v-col cols="5" v-if="lead.phone_type != 1"  class="py-0 my-0">
               <p class="font-weight-bold"> Homephone </p>
           </v-col>

           <v-col cols="7" v-if="lead.phone_type != 1"  class="py-0 my-0">
               <p>{{lead.homephone}}</p>
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
               <p>{{ lead.is_email_billing == 1?'Email':'Paper' }}</p>
           </v-col>


           <v-col cols="5" class="py-0 my-0">
               <p class="font-weight-bold">Authorized person</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">
               <p>{{ lead.authorizedPersonName == null ? 'Unassigned' : lead.authorizedPersonName }}</p>
           </v-col>

           <v-col cols="5" class="py-0 my-0">
               <p class="font-weight-bold">Status</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">
               <p>{{ lead.status }}</p>
           </v-col>
       </v-row>


        <v-btn block class="my-4" color="primary" @click="goToLeadDetails(lead.id)">View Application Details</v-btn>

        <p class="sub-title mt-4 mb-2">Property  Details</p>
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
               <p class="font-weight-bold">Tenancy Type:</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">
               <p>{{lead.tenancy_type == 1? 'Renter': 'Owner'}}</p>
           </v-col>
           <v-col cols="5" class="py-0 my-0">
               <p class="font-weight-bold">Service Address:</p>
           </v-col>
           <v-col cols="7" class="py-0 my-0">
               <p>{{lead.address_text}}</p>
           </v-col>
       </v-row>



        <v-divider class="mt-4 mb-2"></v-divider>
       <p class="sub-title py-2">Service Interests
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
       </p>

        <div class="mt-3">
          <p class="sub-title mb-1">Agent’s Additional Instructions</p>
          <v-textarea v-model="lead.additional_instruction"
                      background-color="#FAFAFA"
                      color="#7E8A8F"
            outlined
            placeholder="Additional Instructions goes here"
                      readonly
          ></v-textarea>
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

export default {
  name: "ApplicationDetails",
    props: {
      lead: {
          required: true
      }
    },
    methods: {
        goToLeadDetails(id) {
            this.$router.push({name:'applications.details', params:{id:id}});
        },
        isServiceAllowed(services, type) {
            return !services.includes(type);
        }
    },
    computed: {
      date_of_birth() {
          return dayJs(this.lead.date_of_birth,'YYYY-MM-DD').format('DD/MM/YYYY');
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
</style>
