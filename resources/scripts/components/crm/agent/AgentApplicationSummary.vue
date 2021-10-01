<template>
    <v-card class="hood-card" v-if="application">
        <h3 class="page-title">{{application.applicant_name }}</h3>
        <p class="sub-title mt-4 mb-2">Personal Details</p>
        <table  class="application-info layout-fixed-table">
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
                <td>{{ application.is_email_billing == 1 ? 'Email' : 'Paper' }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Status:</td>
                <td>{{['Unassigned','Assigned', 'Escalated'].includes(application.status)?'In Progress': application.status }}</td>
            </tr>
        </table>

        <div class="pt-5">
            <v-divider></v-divider>
        </div>


        <p class="sub-title mt-4 mb-2">Property  Details</p>
        <table  class="application-info">
            <tr>
                <td class="font-weight-bold">Tenancy Type:</td>
                <td>{{ application.tenancy_type == 1? 'Renter': 'Owner' }}</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Service Address:</td>
                <td>{{ application.address_text }}</td>
            </tr>

        </table>

        <v-divider class="mt-4 mb-2"></v-divider>
        <p class="sub-title py-2">Service Preference
          <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(application.service_interests, 'power')" color="yellow">mdi-flash</v-icon>
          </span>
          <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(application.service_interests, 'gas')" color="red">mdi-fire</v-icon>
          </span>
          <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(application.service_interests, 'internet')" color="green">mdi-wifi</v-icon>
          </span>
          <span class="mx-2">
              <v-icon :disabled="isServiceAllowed(application.service_interests, 'water')" color="blue" >mdi-water</v-icon>
          </span>
        </p>

        <div>
          <p class="sub-title mb-1">Agent’s Additional Instructions</p>
          <v-textarea
            outlined
            disabled
            background-color="#FAFAFA"
            class="agent-addition-instuction"
            placeholder="Additional Instructions goes here."
            v-model="application.additional_instruction"
            readonly
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
    computed: {
        full_name()
        {
            if(this.application.middle_name === '') {
                    return this.application.first_name + ' ' + this.application.middle_name + ' ' + this.application.last_name
                }
            return this.application.first_name + ' ' + this.application.last_name

        }
    },
    methods: {
        isServiceAllowed(services, type) {
            return !services.includes(type);
        }
    },

    mounted() {

    }
};
</script>

<style scoped>
.layout-fixed-table{
    table-layout: fixed;
    width: 100%
}
</style>
