<template>
    <v-row>
        <v-col cols="12" class="d-flex justify-space-between align-center">

            <div class="d-flex align-center">
                <v-btn @click="goToBack()"><v-icon left dark>mdi-arrow-left</v-icon>Back</v-btn>
                <p class="page-title mx-4 mb-0 ">{{leadSummary.applicant_name}} </p>
                <div>
                    <small class="font-weight-thin">
                        Service Interests
                        <span  :class="{'mx-1':isActive('Power'), 'pa-2':isActive('Power'), 'elevation-3':isActive('Power'),}" ><v-icon :color="getColor('Power')">mdi-flash</v-icon> Power</span>
                        <span  :class="{'mx-1':isActive('Gas'), 'pa-2':isActive('Gas'), 'elevation-3':isActive('Gas'),}"><v-icon :color="getColor('Gas')">mdi-fire</v-icon> Gas</span>
                        <span :class="{'mx-1':isActive('Internet'), 'pa-2':isActive('Internet'), 'elevation-3':isActive('Internet'),}"><v-icon :color="getColor('Internet')">mdi-wifi</v-icon> Internet</span>
                        <span :class="{'mx-1':isActive('Water'), 'pa-2':isActive('Water'), 'elevation-3':isActive('Water'),}"><v-icon color="grey lighten-1">mdi-water</v-icon> Water</span>
                    </small>
                </div>
            </div>



            <div>
                <v-btn outlined @click="escalate">Escalate</v-btn>
                <v-btn outlined @click="closeConnection">Close Application</v-btn>
            </div>
        </v-col>

        <v-col cols="12">
            <v-divider></v-divider>
        </v-col>
    </v-row>
</template>

<script>
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

        isActive(service) {
            return this.leadSummary.service_types.includes(service.toLowerCase())?true:false;
        },

        getColor(service) {
            if(this.isActive(service)) {
                if(service.toLowerCase() === 'power') {
                    return 'yellow';
                }
                if(service.toLowerCase() === 'gas') {
                    return 'red';
                }
                if(service.toLowerCase() === 'internet') {
                    return 'red';
                }
                if(service.toLowerCase() === 'water') {
                    return 'red';
                }
            }
            return 'grey lighten-1';
        }



    }
}
</script>

<style scoped>

</style>
