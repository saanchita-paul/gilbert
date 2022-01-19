<template>
    <v-card class="hood-card" v-if="application">
        <h3 class="page-title">{{application.applicant_name }}</h3>
        <IdCopyToClipboard :applicationId="application.id"/>
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
                <p class="font-weight-bold pl-2 mb-2">Date of Birth</p>
            </v-col>
            <v-col cols="7" class="my-0 py-0">
                <p class="mb-2">
                    {{ date_of_birth }}
                </p>
            </v-col>

            <v-col cols="5" class="my-0 py-0" v-if="application.phone_type == 1">
                <p class="font-weight-bold pl-2 m-2"> Mobile </p>
            </v-col>
            <v-col cols="7" class="my-0 py-0" v-if="application.phone_type == 1">
                <p class="mb-2">{{application.phone}}</p>
            </v-col>

            <v-col v-if="application.phone_type != 1" cols="5"  class="my-0 py-0">
                <p class="font-weight-bold  pl-2 mb-2"> Homephone </p>
            </v-col>
            <v-col v-if="application.phone_type != 1" cols="6" class="my-0 py-0">
                <p class="mb-2">{{application.homephone}}</p>
            </v-col>

            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2 mb-2">Email</p>
            </v-col>
            <v-col cols="7" class="my-0 py-0">
                <p class="mb-2">{{ application.email }}</p>
            </v-col>

            <v-col cols="5"  class="my-0 py-0">
                <p class="font-weight-bold  pl-2 mb-2">Moving Date</p>
            </v-col>
            <v-col cols="7" class="my-0 py-1">
                <p class="mb-2">{{ moving_date }}</p>
            </v-col>




            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2 mb-2">Email billing</p>
<!--                <p>{{ application.is_email_billing == 1 ? 'Email' : 'Paper' }}</p>-->
            </v-col>
            <v-col cols="7" class="my-0 py-0">
<!--                <p class="font-weight-bold">Email billing</p>-->
                <p class="mb-2">{{ application.is_email_billing == 1 ? 'Email' : 'Paper' }}</p>
            </v-col>
            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2 mb-2" >Authorized Person</p>
<!--                <p>{{ application.authorizedPersonName == null ? 'Unassigned' : application.authorizedPersonName }}</p>-->
            </v-col>
            <v-col cols="7" class="my-0 py-0">
<!--                <p class="font-weight-bold">Authorized Person</p>-->
                <p class="mb-2">{{ application.authorizedPersonName == null ? 'No Authorised Person' : application.authorizedPersonName }}</p>
            </v-col>

            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2 mb-2">Status</p>
<!--                <p>{{['Unassigned','Assigned', 'Escalated'].includes(application.status)?'In Progress': application.status }}</p>-->
            </v-col>
            <v-col cols="7" class="my-0 py-0">
<!--                <p class="font-weight-bold">Status:</p>-->
                <p class="mb-2">{{['Unassigned', 'UnAssigned', 'Assigned', 'Escalated' , 'Submitted', 'inprogress' , 'Inprogress'].includes(application.status)?'In Progress': application.status }}</p>
            </v-col>

            <v-col cols="5" class="my-0 py-0">
                <p class="font-weight-bold  pl-2 mb-2">Identification</p>
            </v-col>
            <v-col cols="7" class="my-0 py-0">
                <p class="mb-2">{{identification_type}}</p>
                <p class="mb-2">{{identification_number}}</p>
                <p class="mb-2">Expires on {{identification_expire_data}}</p>
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
                <p class="font-weight-bold  pl-2 mb-2">Occupancy Type:</p>
            </v-col>
            <v-col cols="6" class="my-0 py-0">
                <p class="mb-2">
                    {{ application.tenancy_type == 1? 'Renter': 'Owner' }}
                </p>
            </v-col>
            <v-col cols="5" class="my-0 py-0 mb-2">
                <p class="font-weight-bold  pl-2">Service  Address:</p>
            </v-col>
            <v-col cols="6" class="my-0 py-0">
                <p class="mb-2">
                    {{ application.address_text }}
                </p>
            </v-col>
        </v-row>


        <v-divider class="mt-4 mb-2"></v-divider>

        <p class="sub-title mt-4 mb-2">Service Preference</p>
        <v-row>

            <v-col  class="my-0 py-0 mx-0">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-1">
                      <v-icon :disabled="isServiceAllowed(application.service_interests, 'power')" color="yellow">mdi-flash</v-icon>Power
                  </span>
                </p>
                <p class="py-0 my-0 pl-6 service-status active-power-subtitle"
                   :class="{ 'active-power-subtitle': !isServiceAllowed(application.service_interests, 'power') }" >
                    {{getServiceStatus('power')}}
<!--                    Connected-->
                </p>
            </v-col>

            <v-col  class="my-0 py-0 mx-0">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-1">
                      <v-icon :disabled="isServiceAllowed(application.service_interests, 'gas')" color="red">mdi-fire</v-icon>Gas
                  </span>
                </p>
                <p class="py-0 my-0 pl-6 service-status active-power-subtitle"
                   :class="{ 'active-gas-subtitle': !isServiceAllowed(application.service_interests, 'gas') }">
                    {{getServiceStatus('gas')}}
<!--                    Connected-->
                </p>
            </v-col>
            <v-col  class="my-0 py-0 mx-0">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-1">
                       <v-icon :disabled="isServiceAllowed(application.service_interests, 'internet')" color="green">mdi-wifi</v-icon>Internet
                  </span>
                </p>
                <p class="py-0 my-0 pl-6 service-status active-power-subtitle"
                   :class="{ 'active-internet-subtitle': !isServiceAllowed(application.service_interests, 'internet') }">
                    {{getServiceStatus('internet')}}
<!--                    Connected-->
                </p>
            </v-col>
            <v-col  class="my-0 py-0 mx-0">
                <p class="pt-2 pb-1 mb-0 services">
                  <span class="ml-1">
                      <v-icon :disabled="isServiceAllowed(application.service_interests, 'water')" color="blue" >mdi-water</v-icon>Water
                  </span>
                </p>
                <p class="py-0 my-0 pl-6 service-status "
                   :class="{ 'active-water-subtitle': !isServiceAllowed(application.service_interests, 'water') }">
                    {{getServiceStatus('water')}}
<!--                    Connected-->
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

        <div class="mt-4">
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
import IDENTIFICATION from "@scripts/data/constants/IDENTIFICATION";
import IdCopyToClipboard from '@scripts/components/common/IdCopyToClipboard.vue';
export default {
    name: "AgentApplicationSummary",
    components:{IdCopyToClipboard},
    props: ["application"],
    data() {
        return {
            powerText: '',
            gasText: '',
            internetText: '',
            waterText: '',
        }
    },
    computed: {
        full_name() {
            if(this.application.middle_name === '') {
                    return this.application.first_name + ' ' + this.application.middle_name + ' ' + this.application.last_name
                }
            return this.application.first_name + ' ' + this.application.last_name

        },
        date_of_birth() {
          return dayJs(dayJs(this.application.date_of_birth,'YYYY-MM-DD').format('DD/MM/YYYY')).isValid() ? dayJs(this.application.date_of_birth,'YYYY-MM-DD').format('DD/MM/YYYY') : null;
        },

        moving_date() {
            return dayJs(this.application.moving_date).format(DATE_FORMAT.DB_DATE);
        },

        identification_type() {
            return this.application?.identification?.type === IDENTIFICATION.PASSPORT? 'Passport':
                this.application?.identification?.type === IDENTIFICATION.DL? 'DL':
                this.application?.identification?.type === IDENTIFICATION.MEDICARE? 'Medicare':'';
        },

        identification_number() {
            return this.application?.identification?.card_number;
        },
        identification_expire_data() {
            return dayJs(this.application?.identification?.expire_date, 'YYYY-MM-DD').format('MM/YY');
        },


    },
    methods: {
        isServiceAllowed(services, type) {
            return !services.includes(type);
        },

        getServiceStatus(conn_ser) {
            let service = this.application.connection_services.find((svc)=>{
                return svc.service_type === conn_ser;
            })
            if(service) {
                return this.mapConnectionStatus(service.statusText);
            }
            return '';
        },

        mapConnectionStatus(status) {
            return ['unassigned','assigned', 'escalated','processing'].includes(status)?'In Progress':
                status[0].toUpperCase() + status.slice(1);
        }
    },
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
.services{
    font-size: 14px !important;
}

.active-power-subtitle {
    color: #15DB64;
}
.active-gas-subtitle {
    color: #263238;
}
.active-water-subtitle {
    color: #263238;
}
.active-internet-subtitle {
    color: #E91E63;
 }



</style>
