<template>
    <v-card class="pa-4" v-if="application">
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
          <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(application.service_types, 'power')" color="yellow">mdi-flash</v-icon>
          </span>
          <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(application.service_types, 'gas')" color="red">mdi-fire</v-icon>
          </span>
          <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(application.service_types, 'internet')" color="green">mdi-wifi</v-icon>
          </span>
          <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(application.service_types, 'water')" color="blue" >mdi-water</v-icon>
          </span>
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
    props: ["application"],
    data() {
        return {

        }
    },
    methods: {
        isServiceAllowed(services, type) {
            return !services.includes(type);
        }
    },
};
</script>

<style scoped>
</style>
