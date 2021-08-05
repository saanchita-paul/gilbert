<template>
    <v-row>
        <v-col cols="12" class="d-flex justify-space-between align-center">

            <div class="d-flex align-center">
                <v-btn @click="goToBack()"><v-icon left dark>mdi-arrow-left</v-icon>Back</v-btn>
                <p class="page-title mx-4 mb-0 ">Staedtler Marker </p>
                <div>
                    <small class="font-weight-thin">
                        Service Interests
                        <span class="mx-1 pa-2 elevation-3" ><v-icon small color="yellow">mdi-flash</v-icon> Power</span>
                        <span class="mx-1 pa-2 elevation-3"><v-icon small color="red">mdi-fire</v-icon> Gas</span>
                        <span><v-icon small color="grey lighten-1">mdi-wifi</v-icon> Internet</span>
                        <span><v-icon small color="grey lighten-1">mdi-water</v-icon> Water</span>
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
            //todo
        },

        closeConnection() {
            //todo
        }


    }
}
</script>

<style scoped>

</style>
