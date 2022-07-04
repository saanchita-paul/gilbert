<template>
    <v-row v-if="leadSummary.id">
        <v-col cols="12" class="d-flex justify-space-between">
            <div class="d-flex" >
                <div class="mx-4 mb-0" >
                    <p class="page-title mb-0">
                        <span>
                            <v-img @click="goToBack()" src="/assets/images/icons/back_btn.png" max-height="40px" max-width="40px" class="back-btn mt-1">
                            </v-img>
                        </span>
                        <span>
                            {{leadSummary.applicant_name}}
                            <span style="margin-left:30px; margin-top: -10px;">
                                <IdCopyToClipboard :applicationId="leadSummary.id"/>
                            </span>
                        </span>
                    </p>
                </div>
            </div>
            <div>
                <div class="d-flex justify-end">
                    <v-btn outlined @click="escalate" right v-if="leadSummary.status != 3">Escalate</v-btn>
                    <v-btn v-if="leadSummary.status == 3"  outlined @click="escalate" right :disabled="leadSummary.status == 3" class="border-warning">Escalated</v-btn>
                    <v-btn v-if="leadSummary.status !== 'Closed'" outlined @click="closeApplicationWithReason" class="ml-1">Close Application</v-btn>
                    <v-btn outlined @click="sendToChatBot" class="ml-1">Send to Chatbot<v-icon class="pl-3">mdi-facebook-messenger</v-icon></v-btn>
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
                            <div class="font-weight-bold" :class="{'mx-1':isEnergyActive('Power')}" ><v-icon size="16" :color="getEnergyColor('Power')">mdi-flash</v-icon> Power</div>
                        </div>
                        <div :style="{ 'text-align': 'center', color: getEnergyServiceStatus('power').color }"> {{ getEnergyServiceStatus('power').text }} </div>
                    </div>

                    <div class="d-flex justify-center" style="flex-wrap: wrap;">
                        <div style="flex-basis: 100%; text-align: center;">
                            <div class="font-weight-bold" :class="{'mx-1':isEnergyActive('Gas')}" ><v-icon size="16" :color="getEnergyColor('Gas')">mdi-fire</v-icon> Gas</div>
                        </div>
                        <div :style="{ 'text-align': 'center', color: getEnergyServiceStatus('gas').color }"> {{ getEnergyServiceStatus('gas').text }} </div>
                    </div>


                    <div class="d-flex justify-center" style="flex-wrap: wrap;">
                        <div style="flex-basis: 100%; text-align: center;">
                            <div class="font-weight-bold" :class="{'mx-1':isActive('Water')}" ><v-icon size="16" :color="getColor('Water')">mdi-water</v-icon> Water</div>
                        </div>
                        <div :style="{ 'text-align': 'center', color: getServiceStatus('water').color }"> {{ getServiceStatus('water').text }} </div>
                    </div>

                    <div class="d-flex justify-center" style="flex-wrap: wrap;">
                        <div style="flex-basis: 100%; text-align: center;">
                            <div class="font-weight-bold"  :class="{'mx-1':isActive('Internet')}" ><v-icon size="16" :color="getColor('Internet')">mdi-wifi</v-icon> Internet</div>
                        </div>
                        <div :style="{ 'text-align': 'center', color: getServiceStatus('internet').color }"> {{ getServiceStatus('internet').text }} </div>
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
import IdCopyToClipboard from '@scripts/components/common/IdCopyToClipboard.vue';
import leadApplicationService from "@scripts/services/crm/LeadApplicationService";
import UtilityStoreService from "@scripts/services/crm/UtilityStoreService";

export default {
name: "LeadDetailsHeader",
    components:{ IdCopyToClipboard },
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
            this.$emit('closeApplicationWithReason');
        },

        closeConnection() {
            this.$router.push({name:'applications'});
        },

        closeApplication() {
            this.$emit('closeApplication' , this.leadSummary);
        },

        isActive(service) {
            return this.leadSummary.service_interests.includes(service.toLowerCase()) ? true : false;
        },

        isEnergyActive(service) {
            let status = service.toLowerCase() === 'power' ? UtilityStoreService.getPowerStatus() : UtilityStoreService.getGasStatus();
            return status ? true : false;
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

        getEnergyColor(service) {
            if(this.isEnergyActive(service)) {
                if(service.toLowerCase() === 'power') {
                    return 'yellow';
                }
                if(service.toLowerCase() === 'gas') {
                    return 'orange';
                }
            }
            return 'grey lighten-1';
        },

        getServiceStatus(conn_ser) {
            return LeadApplicationService.mapStatus(leadApplicationService.getServiceObj(this.leadSummary.connection_services, conn_ser)?.status);
        },

        getEnergyServiceStatus(service) {
            let status = service.toLowerCase() === 'power' ? UtilityStoreService.getPowerStatus() : UtilityStoreService.getGasStatus();
            return LeadApplicationService.mapStatus(status);
        },

        mapConnectionStatus(status) {
            return  LeadApplicationService.mapStatus(status)
        },
        sendToChatBot() {
            this.$emit('sendToChatBot');
            // this.$eventBus.$emit("busUtilitySubmit", subType);
        },

    },
    mounted() {
        // console.log('load_summary_he', this.leadSummary);
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
