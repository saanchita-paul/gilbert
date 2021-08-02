<template>
   <v-card class="pa-4">
        <h3 class="page-title">Staedtler Marker</h3>
        <p class="sub-title mt-4 mb-2">Personal Details</p>
        <table width="100%" class="application-info">
            <tr>
                <td class="font-weight-bold">Date of Birth</td>
                <td>02/22/2022</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Mobile</td>
                <td>0410758792</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Email</td>
                <td>staedtler.marker@gmail.com</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Moving Date</td>
                <td>02/22/2022</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Email billing</td>
                <td>Email/Paper</td>
            </tr>
        </table>

        <v-btn block class="my-4" color="primary">View Application Details</v-btn>
        
        <p class="sub-title mt-4 mb-2">Property  Details</p>
        <table width="100%" class="application-info">
            <tr>
                <td class="font-weight-bold">Tenancy Type:</td>
                <td>Renter or Home Owner</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Service Address:</td>
                <td>398 Bourke Road, Camberwell 3124 VIC</td>
            </tr>
        </table>

        <v-divider class="mt-4 mb-2"></v-divider>
        <span class="mt-3"><b>Service Interests</b>   
          <sapan class="mx-2"><v-icon color="yellow">mdi-flash</v-icon> Power</sapan>
          <sapan  class="mx-2"><v-icon color="red">mdi-fire</v-icon> Gas</sapan>
          <sapan  class="mx-2"><v-icon color="grey lighten-1">mdi-wifi</v-icon> Internet</sapan>
          <sapan  class="mx-2"><v-icon color="grey lighten-1">mdi-water</v-icon> Water</sapan>
        </span>

        <div class="mt-3">
          <p class="sub-title mb-1">Agent’s Additional Instructions</p>
          <v-textarea
            outlined
            placeholder="Additional Instructions goes here."
          ></v-textarea>
        </div>

    </v-card>
</template>

<script>
export default {
  name: "ApplicationDetails",
};
</script>

<style scoped>
</style>
