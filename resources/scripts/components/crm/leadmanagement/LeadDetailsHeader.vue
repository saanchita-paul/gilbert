<template>
    <v-row>
        <v-col cols="12" class="d-flex justify-space-between">

            <div class="d-flex">
<!--                <v-btn color="transperent"  small class="back-btn mt-2"><v-img @click="goToBack()" src="/assets/images/icons/back_btn.png"/></v-btn>-->
                <div class="mx-4 mb-0">
                    <p class="page-title mb-0"><span><v-img @click="goToBack()" src="/assets/images/icons/back_btn.png" max-height="40px" max-width="40px" class="back-btn mt-1"> </v-img></span>{{leadSummary.applicant_name}} </p>
                    <small class="font-weight-bold">
                       Preference
                        <span class="mx-1 pa-2"  :class="{'mx-1':isActive('Power'), 'pa-2':isActive('Power'),}" ><v-icon size="16" :color="getColor('Power')">mdi-flash</v-icon> Power</span>
                        <span class="mx-1 pa-2" :class="{'mx-1':isActive('Gas'), 'pa-2':isActive('Gas'), }"><v-icon size="16" :color="getColor('Gas')">mdi-fire</v-icon> Gas</span>
                        <span class="mx-1 pa-2" :class="{'mx-1':isActive('Internet'), 'pa-2':isActive('Internet'), }"><v-icon size="16" :color="getColor('Internet')">mdi-wifi</v-icon> Internet</span>
                        <span class="mx-1 pa-2" :class="{'mx-1':isActive('Water'), 'pa-2':isActive('Water'), }"><v-icon  size="16" :color="getColor('Water')">mdi-water</v-icon> Water</span>
                        <span class="ml-4 mr-1 py-2 pl-2 font-weight-bold" >Status</span>
                        <span class="mx-1 font-normal" >{{leadSummary.status}}</span>
                    </small>
                </div>
            </div>

            <div>
                <div class="d-flex justify-end">
                    <v-btn outlined @click="escalate" right v-if="leadSummary.status != 3">Escalate</v-btn>
                    <v-btn v-if="leadSummary.status == 3"  outlined @click="escalate" right :disabled="leadSummary.status == 3" class="border-warning">Escalated</v-btn>
    <!--                <v-btn outlined @click="closeApplication">Close Application</v-btn>-->
                </div>
                <p v-if="leadSummary.is_contacted" class="application-consent mt-5"><v-icon size="14px" color="success" class="mx-2">call</v-icon>Applicant consents to be contacted by HOOD</p>
            </div>
            
        </v-col>

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
        }



    },
    mounted() {
         // console.log('load_summary_he', this.leadSummary);
    }
}
</script>

<style scoped>
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

</style>
