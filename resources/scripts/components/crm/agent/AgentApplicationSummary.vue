<template>
    <v-card class="pa-4">
        <h3 class="page-title">{{ application.applicant_name }}</h3>
        <p class="sub-title mt-4 mb-2">Personal Details</p>
        <table width="100%" class="application-info">
            <tr>
                <td class="font-weight-bold">Date of Birth</td>
                <td>{{ application.date_of_birth }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Mobile</td>
                <td>{{ application.phone }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Email</td>
                <td>{{ application.email }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Moving Date</td>
                <td>{{ application.moving_date }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Email billing</td>
                <td>{{ application.email_billing }}</td>
            </tr>
        </table>


        <p class="sub-title mt-4 mb-2">Property  Details</p>
        <table width="100%" class="application-info">
            <tr>
                <td class="font-weight-bold">Tenancy Type:</td>
                <td>{{ application.tenancy_type }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Service Address:</td>
                <td>{{ application.service_address }}</td>
            </tr>
        </table>

        <v-divider class="mt-4 mb-2"></v-divider>
        <p class="sub-title py-2">Service Interests
          <span class="mx-2"><v-icon color="yellow">mdi-flash</v-icon> Power</span>
          <span  class="mx-2"><v-icon color="red">mdi-fire</v-icon> Gas</span>
          <span  class="mx-2"><v-icon color="grey lighten-1">mdi-wifi</v-icon> Internet</span>
          <span  class="mx-2"><v-icon color="grey lighten-1">mdi-water</v-icon> Water</span>
        </p>

        <div>
          <p class="sub-title mb-1">Agent’s Additional Instructions</p>
          <v-textarea
            outlined
            placeholder="Additional Instructions goes here."
            v-model="application.additional_instruction"
        ></v-textarea>
        </div>

    </v-card>
</template>

<script>
export default {
    name: "AgentApplicationSummary",
    data() {
        return {
            application : {
                id: 1,
                applicant_name: 'Staedtler Marker',
                date_of_birth: '06/08/2021',
                phone: '0410758782',
                email: 'staedtler.marker@gmail.com',
                moving_date: '02/22/2022',
                email_billing: 'Email/Paper',
                tenancy_type: 'Renter or Home Owner',
                service_address: '398 Bourke Road, Camberwell 3124 VIC',
                service_types: 'Not Confirmed Connection',
                additional_instruction: 'Additional Instructions goes here. Maybe it’s extra long so I have ' +
                    'to write something down to show how it will look like when a property manager has soooo ' +
                    'much things to say on his lead.'
            },
        }
    },
};
</script>

<style scoped>
</style>
