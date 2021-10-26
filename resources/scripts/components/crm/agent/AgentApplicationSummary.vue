<template>
    <v-card class="hood-card" v-if="application">
        <h3 class="page-title">{{application.applicant_name }}</h3>
        <p class="sub-title mt-4 mb-2">Personal Details</p>
<!--        <table  class="application-info layout-fixed-table">-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Date of Birth</td>-->
<!--                <td>{{ date_of_birth }}</td>-->
<!--            </tr>-->
<!--            <tr v-if="application.phone_type == 1">-->
<!--                <td class="font-weight-bold"> Mobile </td>-->
<!--                <td>{{application.phone}}</td>-->
<!--            </tr>-->
<!--            <tr v-else>-->
<!--                <td class="font-weight-bold"> Homephone </td>-->
<!--                <td>{{application.homephone}}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Email</td>-->
<!--                <td>{{ application.email }}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Moving Date</td>-->
<!--                <td>{{ moving_date }}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Email billing</td>-->
<!--                <td>{{ application.is_email_billing == 1 ? 'Email' : 'Paper' }}</td>-->
<!--            </tr>-->
<!--             <tr>-->
<!--                <td class="font-weight-bold">Authorized Person</td>-->
<!--                <td>{{ application.authorizedPersonName == null ? 'Unassigned' : application.authorizedPersonName }}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Status:</td>-->
<!--                <td>{{['Unassigned','Assigned', 'Escalated'].includes(application.status)?'In Progress': application.status }}</td>-->
<!--            </tr>-->
<!--        </table>-->

        <v-row>
            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold pl-2">Date of Birth</p>
            </v-col>
            <v-col cols="7" class="my-0 py-0">
                <p>
                    {{ date_of_birth }}
                </p>
            </v-col>

            <v-col cols="5" class="my-0 py-0" v-if="application.phone_type == 1">
                <p class="font-weight-bold pl-2"> Mobile </p>
            </v-col>
            <v-col cols="7" class="my-0 py-0" v-if="application.phone_type == 1">
                <p>{{application.phone}}</p>
            </v-col>

            <v-col v-if="application.phone_type != 1" cols="5"  class="my-0 py-0">
                <p class="font-weight-bold  pl-2"> Homephone </p>
            </v-col>
            <v-col v-if="application.phone_type != 1" cols="6" class="my-0 py-0">
                <p>{{application.homephone}}</p>
            </v-col>

            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2">Email</p>
            </v-col>
            <v-col cols="7"class="my-0 py-0">
                <p>{{ application.email }}</p>
            </v-col>

            <v-col cols="5"  class="my-0 py-0">
                <p class="font-weight-bold  pl-2">Moving Date</p>
            </v-col>
            <v-col cols="7" class="my-0 py-1">
                <p>{{ moving_date }}</p>
            </v-col>




            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2">Email billing</p>
<!--                <p>{{ application.is_email_billing == 1 ? 'Email' : 'Paper' }}</p>-->
            </v-col>
            <v-col cols="7" class="my-0 py-0">
<!--                <p class="font-weight-bold">Email billing</p>-->
                <p>{{ application.is_email_billing == 1 ? 'Email' : 'Paper' }}</p>
            </v-col>
            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2" >Authorized Person</p>
<!--                <p>{{ application.authorizedPersonName == null ? 'Unassigned' : application.authorizedPersonName }}</p>-->
            </v-col>
            <v-col cols="7" class="my-0 py-0">
<!--                <p class="font-weight-bold">Authorized Person</p>-->
                <p>{{ application.authorizedPersonName == null ? 'Unassigned' : application.authorizedPersonName }}</p>
            </v-col>

            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2">Status</p>
<!--                <p>{{['Unassigned','Assigned', 'Escalated'].includes(application.status)?'In Progress': application.status }}</p>-->
            </v-col>
            <v-col cols="7" class="my-0 py-0">
<!--                <p class="font-weight-bold">Status:</p>-->
                <p>{{['Unassigned','Assigned', 'Escalated'].includes(application.status)?'In Progress': application.status }}</p>
            </v-col>

            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2">Identification</p>
            </v-col>
            <v-col cols="7" class="my-0 py-0">
                <p>Passport</p>
                <p>12343566467</p>
                <p>Expire on 10/23</p>
            </v-col>




        </v-row>

        <div class="pt-5">
            <v-divider></v-divider>
        </div>


        <p class="sub-title mt-4 mb-2">Property  Details</p>
<!--        <table  class="application-info">-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Tenancy Type:</td>-->
<!--                <td>{{ application.tenancy_type == 1? 'Renter': 'Owner' }}</td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td class="font-weight-bold">Service Address:</td>-->
<!--                <td>{{ application.address_text }}</td>-->
<!--            </tr>-->
<!--        </table>-->

        <v-row>
            <v-col cols="5" class="my-0 py-0 ">
                <p class="font-weight-bold  pl-2">Occupancy Type:</p>
            </v-col>
            <v-col cols="6" class="my-0 py-0">
                <p>
                    {{ application.tenancy_type == 1? 'Renter': 'Owner' }}
                </p>
            </v-col>
            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2">Service Address:</p>
            </v-col>
            <v-col cols="6" class="my-0 py-0">
                <p>
                    {{ application.address_text }}
                </p>
            </v-col>
        </v-row>


        <v-divider class="mt-4 mb-2"></v-divider>

        <p class="sub-title mt-4 mb-2">Service Preference</p>
        <v-row>

            <v-col  class="my-0 py-0 mx-0">
                <p class="py-2 mb-0">
                  <span class="ml-2">
                      <v-icon :disabled="isServiceAllowed(application.service_interests, 'power')" color="yellow">mdi-flash</v-icon>Power
                  </span>
                </p>
                <p class="py-0 my-0 pl-6 service-status">
                    Connected
                </p>
            </v-col>

            <v-col  class="my-0 py-0 mx-0">
                <p class="py-2 mb-0">
                  <span class="ml-2">
                      <v-icon :disabled="isServiceAllowed(application.service_interests, 'gas')" color="red">mdi-fire</v-icon>Gas
                  </span>
                </p>
                <p class="py-0 my-0 pl-6 service-status">
                    Connected
                </p>
            </v-col>
            <v-col  class="my-0 py-0 mx-0">
                <p class="py-2 mb-0">
                  <span class="ml-2">
                       <v-icon :disabled="isServiceAllowed(application.service_interests, 'internet')" color="green">mdi-wifi</v-icon>Internet
                  </span>
                </p>
                <p class="py-0 my-0 pl-6 service-status">
                    Connected
                </p>
            </v-col>
            <v-col  class="my-0 py-0 mx-0">
                <p class="py-2 mb-0">
                  <span class="ml-2">
                      <v-icon :disabled="isServiceAllowed(application.service_interests, 'water')" color="blue" >mdi-water</v-icon>Water
                  </span>
                </p>
                <p class="py-0 my-0 pl-6 service-status">
                    Connected
                </p>
            </v-col>



        </v-row>
<!--        <p class="sub-title py-2">-->
<!--          <span class="mx-2">-->
<!--              <v-icon :disabled="isServiceAllowed(application.service_interests, 'power')" color="yellow">mdi-flash</v-icon>-->
<!--          </span>-->
<!--          <span class="mx-2">-->
<!--              <v-icon :disabled="isServiceAllowed(application.service_interests, 'gas')" color="red">mdi-fire</v-icon>-->
<!--          </span>-->
<!--          <span class="mx-2">-->
<!--              <v-icon :disabled="isServiceAllowed(application.service_interests, 'internet')" color="green">mdi-wifi</v-icon>-->
<!--          </span>-->
<!--          <span class="mx-2">-->
<!--              <v-icon :disabled="isServiceAllowed(application.service_interests, 'water')" color="blue" >mdi-water</v-icon>-->
<!--          </span>-->
<!--        </p>-->

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
import dayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

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

        },
        date_of_birth()
        {
            return dayJs(this.application.date_of_birth,'yyyy-dd-mm').format(DATE_FORMAT.DB_DATE);
        },

        moving_date()
        {
            return dayJs(this.application.moving_date,'yyyy-dd-mm').format(DATE_FORMAT.DB_DATE);
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
.service-status{
    font-size: 10px;
}
</style>
