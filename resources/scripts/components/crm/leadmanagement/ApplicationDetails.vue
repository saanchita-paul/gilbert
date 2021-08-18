<template>
   <v-card class="pa-4" v-if="lead">
        <h3 class="page-title">{{lead.applicant_name}}</h3>
        <p class="sub-title mt-4 mb-2">Personal Details</p>
        <table width="100%" class="application-info">
            <tr>
                <td class="font-weight-bold">Date of Birth</td>
                <td>{{lead.date_of_birth}}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Mobile</td>
                <td>{{lead.phone}}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Email</td>
                <td>{{lead.email}}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Moving Date</td>
                <td>{{lead.moving_date}}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Email billing</td>
                <td>{{ lead.is_email_billing == 1?'Email':'Paper' }}</td>
            </tr>
        </table>

        <v-btn block class="my-4" color="primary" @click="goToLeadDetails(lead.id)">View Application Details</v-btn>

        <p class="sub-title mt-4 mb-2">Property  Details</p>
        <table width="100%" class="application-info">
            <tr>
                <td class="font-weight-bold">Tenancy Type:</td>
                <td>{{lead.tenancy_type == 1? 'Renter': 'Owner'}}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Service Address:</td>
                <td>{{lead.address_text}}</td>
            </tr>
        </table>

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
            outlined
            placeholder="Additional Instructions goes here."
          ></v-textarea>
        </div>

    </v-card>
</template>

<script>
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
    }
};
</script>

<style scoped>
</style>
