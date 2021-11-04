<template>
    <v-card class="hood-card">
        <v-row class="pl-5 pt-5">
        <h4>Connection Details</h4>
        <v-col cols="12">
            <h4>Status</h4>
            <p class="status-subtitle">What is the status of this application?</p>

            <div>
                <v-btn class="ma-1 service-status" @click="changeStatus(7, 'In Progress')" :class="{'active-status-progress':getServicestatus(7, )}">In Progress</v-btn>
                <v-btn class="ma-1 service-status"  @click="changeStatus(10, 'Needs more info')" :class="{'active-status-more':getServicestatus(10)}">Needs more info</v-btn>
                <v-btn class="ma-1 service-status"  @click="changeStatus(4, 'Submitted')" :class="{'active-status-submitted':getServicestatus(4)}">Submitted</v-btn>
                <v-btn class="ma-1 service-status"  @click="changeStatus(5, 'Connected')" :class="{'active-status-connected':getServicestatus(5)}">Connected</v-btn>
                <v-btn class="ma-1 service-status"  @click="changeStatus(9, 'Can’t Connect')" :class="{'active-status-cn-connect':getServicestatus(9)}">Can’t Connect</v-btn>
            </div>

        </v-col>
        <v-col cols="8">
            <v-row>
                <v-col class="connection-date">
                    <h4 class="mt-2">Connection Date</h4>
                </v-col>
                <v-col cols="8">
                    <div class="text-field">
                        <ValidationProvider
                            name="Connection Date"
                            rules="required"
                            v-slot="{ errors }"
                        >
                            <v-menu
                                v-model="connection_date"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <ValidationProvider
                                        name="Connection Date"
                                        rules="required|valid-date|not-holiday:@h_state"
                                        v-slot="{ errors }"
                                    >
                                        <v-text-field
                                            placeholder="DD/MM/YYYY"
                                            outlined
                                            dense
                                            append-icon="mdi-calendar"
                                            v-model="water.connection_date"
                                            v-bind="attrs"
                                            :error-messages="errors[0]"
                                            hide-details="auto"


                                        >
                                            <template slot="append">
                                                <v-icon v-on="on">mdi-calendar</v-icon>
                                            </template>
                                        </v-text-field>
                                    </ValidationProvider>
                                </template>
                                <v-date-picker
                                    v-model="moving_date"
                                    @input="connection_date = false"
                                ></v-date-picker>
                            </v-menu>
                        </ValidationProvider>
                    </div>
                </v-col>
            </v-row>

        </v-col>
            <v-col cols="8">
                <v-divider></v-divider>
            </v-col>
        <v-col cols="8">
            <h4>Service Providers</h4>
            <p class="status-subtitle">Select a provider for <span class="p-bold">{{leadSummary.address_text}}</span></p>
            <v-select v-model="water.provider_name" :items="waterServiceDD" dense item-value="source" outlined placeholder="select provider" item-text="text">
            </v-select>
        </v-col>
    </v-row>
    </v-card>
</template>

<script>
import DayJs from "dayjs";
import WaterService from "@scripts/services/crm/WaterService";
import {isNull} from "lodash-es";
import dayJs from "dayjs";

export default {
name: "WaterService",
    props:['connection_id', 'leadSummary'],
    data() {
    return {
        moving_date_text: '',
        moving_date: '',
        old_date: '',
        connection_date: '',
        active_status: '',
        waterServiceDD: [
            {
                text: 'Greater Western Water',
                source: 'greater_western_water'
            },
            {
                text: 'South East Water',
                source: 'south_east_water'
            },
            {
                text: 'Yarra Valley Water',
                source: 'yarra_valley_water'
            },
        ],
        water: {
            id:'',
            provider_name: '',
            status: '',
            connection_date: '',
            service_type: 'water'

        }
    }
    },
    methods: {

        changeStatus(status, text) {
            this.$emit('updateStatus',text);
            this.active_status = status;
            this.water.status = status;
            this.water.service_type = 'water';
        },

        getServicestatus(status) {
            if(this.active_status === status) return true;
            return false;
        },

        async saveWater() {

            if(this.old_date != this.water.connection_date && !dayJs(this.water.connection_date, 'DD/MM/YYYY').isValid()) {
                return;
            }
            const responseData = await WaterService.saveWater(this.water, this.leadSummary.id);
            this.water.id = responseData.id;
            this.water.connection_date = dayJs(responseData.connection_date, 'YYYY-MM-DD').isValid()?
            dayJs(responseData.connection_date, 'YYYY-MM-DD').format('DD/MM/YYYY'):'';
            this.water.provider_name = responseData.provider_name;
            this.old_date = this.water.connection_date;
        },
         updateStatus() {
             const newServices = this.leadSummary?.connection_services?.find(svc=>{
                 return svc.service_type === 'water';
             });

             // console.log('newServices', newServices);

             if(newServices)
             {
                 this.active_status = newServices.status;
                 this.water.id = newServices.id;
                 this.water.status = newServices.status;
                 this.water.connection_date = dayJs(newServices.connection_date, 'YYYY-MM-DD').isValid()?
                     dayJs(newServices.connection_date, 'YYYY-MM-DD')
                     .format('DD/MM/YYYY'): '';
                 this.water.provider_name = newServices.provider_name;
                 // console.log('water', newServices);
                 // console.log('water', this.water);
                 this.$emit('updateStatus',this.mapStatus(newServices.status));
                 return ;
             }
             this.active_status = 9;
             this.$emit('updateStatus','can n\'t connect');
             return ;
         },

        mapStatus(statusCode) {

            let statustext = '';
                switch (statusCode){
                    case 4:
                        statustext = 'Submitted';
                        break;
                    case  5:
                        statustext = 'Connected';
                        break;
                    case  7:
                        statustext = 'In Progress';
                        break;
                    case  9:
                        statustext = 'Can’t Connect';
                        break;
                    case  10:
                        statustext = 'Needs more info';
                        break;
                    default:
                        break;
                }
                return statustext;
        }

    },
    watch: {
        moving_date() {
            this.water.connection_date = DayJs(this.moving_date, 'YYYY-MM-DD').format('DD/MM/YYYY');
        },
        water: {
            handler(water, oldWater) {
                this.saveWater();
            },
            deep: true
        }
    },
    async mounted() {
        await this.updateStatus();
    }
}
</script>

<style scoped>

.active-status {
    color:green;
    border: 1px solid #03A9F4 !important;
}

.service-status{
    border: 1px solid transparent;
}
.status-subtitle{
    font-size: 14px;
    color: #7E8A8F;
}
.p-bold
{
    font-weight: bold;
}

.connection-date{
    max-width: 150px !important;
}

.active-status-progress {
    color:#263238;
    border: 1px solid #263238 !important;
}
.active-status-more {
    color:#FF5722;
    border: 1px solid #FF5722 !important;
}

.active-status-submitted {
    color:#03A9F4;
    border: 1px solid #03A9F4 !important;
}

.active-status-connected {
    color:#16A948;
    border: 1px solid #16A948 !important;
}
.active-status-cn-connect {
    color:#E91E63;
    border: 1px solid #E91E63 !important;
}


</style>
