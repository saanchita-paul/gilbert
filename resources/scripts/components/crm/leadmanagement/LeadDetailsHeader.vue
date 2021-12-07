<template>
    <v-row v-if="leadSummary.id">
        <v-col cols="12" class="d-flex justify-space-between">

            <div class="d-flex" >
                <div class="mx-4 mb-0" >
                    <p class="page-title mb-0"><span><v-img @click="goToBack()" src="/assets/images/icons/back_btn.png" max-height="40px" max-width="40px" class="back-btn mt-1"> </v-img></span>{{leadSummary.applicant_name}} </p>
                    <!-- <small class="font-weight-bold">
                       Preference
                        <span class="mx-1 pa-2"  :class="{'mx-1':isActive('Power'), 'pa-2':isActive('Power'),}" ><v-icon size="16" :color="getColor('Power')">mdi-flash</v-icon> Power</span>
                        <span class="mx-1 pa-2" :class="{'mx-1':isActive('Gas'), 'pa-2':isActive('Gas'), }"><v-icon size="16" :color="getColor('Gas')">mdi-fire</v-icon> Gas</span>
                        <span class="mx-1 pa-2" :class="{'mx-1':isActive('Internet'), 'pa-2':isActive('Internet'), }"><v-icon size="16" :color="getColor('Internet')">mdi-wifi</v-icon> Internet</span>
                        <span class="mx-1 pa-2" :class="{'mx-1':isActive('Water'), 'pa-2':isActive('Water'), }"><v-icon  size="16" :color="getColor('Water')">mdi-water</v-icon> Water</span>
                        <span class="ml-4 mr-1 py-2 pl-2 font-weight-bold" >Status</span>
                        <span class="mx-1 font-normal" >{{leadSummary.status}}</span>
                    </small> -->

                </div>
            </div>

            <div>
                <div class="d-flex justify-end">
                    <v-btn outlined @click="escalate" right v-if="leadSummary.status != 3">Escalate</v-btn>
                    <v-btn v-if="leadSummary.status == 3"  outlined @click="escalate" right :disabled="leadSummary.status == 3" class="border-warning">Escalated</v-btn>
                    <v-btn v-if="leadSummary.status !== 'Closed'" outlined @click="closeApplicationWithReason" class="ml-1">Close Application</v-btn>
                </div>
                <p v-if="leadSummary.is_contacted" class="application-consent mt-5"><v-icon size="14px" color="success" class="mx-2">call</v-icon>Applicant consents to be contacted by HOOD</p>
            </div>

        </v-col>

                    <div style="width: 100%;" class="mb-4 ml-6 mr-4 pl-2">
                        <div class="d-flex justify-space-between" style="width: 100%;">
                            <div class="d-flex">
                                <div class="font-weight-bold">Service overview:</div>
                                <div class="d-flex justify-center" style="flex-wrap: wrap;">
                                    <div style="flex-basis: 100%; text-align: center;">
                                        <div class="font-weight-bold" :class="{'mx-1':isActive('Power')}" ><v-icon size="16" :color="getColor('Power')">mdi-flash</v-icon> Power</div>
                                    </div>
                                    <div :style="{ 'text-align': 'center', color: getServiceStatus('power').color }"  >   {{getServiceStatus('power').text}} </div>
                                </div>

                                <div class="d-flex justify-center" style="flex-wrap: wrap;">
                                    <div style="flex-basis: 100%; text-align: center;">
                                        <div class="font-weight-bold" :class="{'mx-1':isActive('Gas')}" ><v-icon size="16" :color="getColor('Gas')">mdi-fire</v-icon> Gas</div>
                                    </div>
                                    <div :style="{ 'text-align': 'center', color: getServiceStatus('gas').color }" :class="getStatusColor('gas')" >  {{getServiceStatus('gas').text}}  </div>
                                </div>


                                <div class="d-flex justify-center" style="flex-wrap: wrap;">
                                    <div style="flex-basis: 100%; text-align: center;">
                                        <div class="font-weight-bold" :class="{'mx-1':isActive('Water')}" ><v-icon size="16" :color="getColor('Water')">mdi-water</v-icon> Water</div>
                                    </div>
                                    <div :style="{ 'text-align': 'center', color: getServiceStatus('water').color }" :class="getStatusColor('water')" >  {{getServiceStatus('water').text}} </div>
                                </div>

                                <div class="d-flex justify-center" style="flex-wrap: wrap;">
                                    <div style="flex-basis: 100%; text-align: center;">
                                        <div class="font-weight-bold"  :class="{'mx-1':isActive('Internet')}" ><v-icon size="16" :color="getColor('Internet')">mdi-wifi</v-icon> Internet</div>
                                    </div>
                                    <div :style="{ 'text-align': 'center', color: getServiceStatus('internet').color }" :class="getStatusColor('internet')"  >  {{getServiceStatus('internet').text}} </div>
                                </div>

                            </div>

                            <div class="d-flex align-end">
                                <span class="font-weight-bold">Application Status: </span> <span class="grey--text pl-2"> {{ leadSummary.status }} </span>
                            </div>
                        </div>
                    </div>

        <v-col cols="12" class="mt-n6">
             <div class="d-flex justify-space-between ">
                <div class="d-flex">
                    <div class="ml-4"><span class="font-weight-bold text-sm">Agent Name:</span> <span>{{ this.leadSummary.agent_name }}</span></div>
                    <p class="ml-4"><span class="font-weight-bold">Agency:</span> <span>{{ this.leadSummary.agency_office }}</span></p>
                </div>
                <div class="d-flex" v-if="this.leadSummary.source == this.leadSourceMap['SOURCE_FOXIE']">
                    <p class="ml-4"><span class="font-weight-bold">Lead Source:</span> <span>{{ this.leadSummary.lead_source }}</span></p>
                    <p class="ml-4"><span class="font-weight-bold">LS Description:</span> <span>{{ this.leadSummary.lead_source_description }}</span></p>
                </div>
            </div>
        </v-col>
        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>

    </v-row>
</template>

<script>
import { leadSourceMap } from '@scripts/data/LeadSourceMap'
import { connectionApplicationMapper } from '@scripts/data/ConnectionApplicationMapper';
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
export default {
name: "LeadDetailsHeader",
    props: {
        leadSummary: {
            require: true
        }
    },

    data() {
        return {
           id:10
        };
    },
    computed:{
        connectionApplicationMapper(){
            return connectionApplicationMapper;
        },
        leadSourceMap(){
            return leadSourceMap;
        }
    },
    methods: {
        goToBack()
        {
            this.$router.push({name:'applications'});
        },

        escalate() {
            this.$emit('eacalate');
        },

        closeApplicationWithReason() {
            console.log("close Application");
            this.$emit('closeApplicationWithReason');
        },


        closeConnection() {
            this.$router.push({name:'applications'});
        },

        closeApplication() {
            this.$emit('closeApplication' , this.leadSummary);
        },

        isActive(service) {
            return this.leadSummary.service_interests.includes(service.toLowerCase())?true:false;
        },

        getColor(service) {
            if(this.isActive(service)) {
                if(service.toLowerCase() === 'power') {
                    return 'yellow';
                }
                if(service.toLowerCase() === 'gas') {
                    return 'orange';
                }
                if(service.toLowerCase() === 'internet') {
                    return '#9C27B0';
                }
                if(service.toLowerCase() === 'water') {
                    return 'blue';
                }
            }
            return 'grey lighten-1';
        },
        getServiceStatus(conn_ser) {
            let service = this.leadSummary.connection_services.find((svc)=>{
                return svc.service_type === conn_ser;
            })
            if(service) {
                return this.mapConnectionStatus(service.status);
            }
            return {
                text: 'Not Selected',
                color: 'black',
            };
        },
        mapConnectionStatus(status) {
            // return ['unassigned','assigned', 'escalated'].includes(status)?'In Progress':
            //     status[0].toUpperCase() + status.slice(1);
            return  LeadApplicationService.mapStatus(status)
        },
        getStatusColor(name){
            // TODO this function needs to be implemented for color
            return '';
        }
    },
    mounted() {
         console.log('load_summary_he', this.leadSummary);
    }
}
</script>

<style lang="scss" scoped>
.border-warning{
    border-color: #fb8c00 !important;
}
.font-normal {
    font-weight: 400 !important;
}
.application-consent{
    color:green !important;
    font-size: 14px !important;
}
.back-btn{
    float: left;
    cursor: pointer;

}

    $titleFontSize: 18px;
    $subtitleFontSize: 16px;
    $regularFontSize: 16px;
    $errorColor : #E91E63;
    $normalColor: black;
    $successColor: #16A948;
    $buttonBackgroundColor : #542E89;

    .titleFontSize{
        font-size: $titleFontSize;
    }
    .regularFontSize{
        font-size: $regularFontSize;
    }
    .subtitleFontSize{
        font-size: $subtitleFontSize;
    }
    .errorColor{
        color: $errorColor;
    }
    .successColor{
        color: $successColor;
    }
    .buttonBackgroundColor{
        background-color: $buttonBackgroundColor;
    }


</style>
