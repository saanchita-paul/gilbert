<template>
   <v-card class="pa-4">
        <h3 class="page-title">{{lead.title}}</h3>
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
                <td>{{lead.email_billing}}</td>
            </tr>
        </table>

        <v-btn block class="my-4" color="primary" @click="goToLeadDetails(lead.id)">View Application Details</v-btn>

        <p class="sub-title mt-4 mb-2">Property  Details</p>
        <table width="100%" class="application-info">
            <tr>
                <td class="font-weight-bold">Tenancy Type:</td>
                <td>{{lead.tenancy_type}}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Service Address:</td>
                <td>{{lead.service_address}}</td>
            </tr>
        </table>

        <v-divider class="mt-4 mb-2"></v-divider>
        <span class="mt-3"><b>Service Interests</b>
          <span class="mx-2"><v-icon color="yellow">mdi-flash</v-icon> Power</span>
          <span  class="mx-2"><v-icon color="red">mdi-fire</v-icon> Gas</span>
          <span  class="mx-2"><v-icon color="grey lighten-1">mdi-wifi</v-icon> Internet</span>
          <span  class="mx-2"><v-icon color="grey lighten-1">mdi-water</v-icon> Water</span>
        </span>

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
        }
    },
    mounted() {
      console.log(this.lead)
    }
};
</script>

<style scoped>
</style>
