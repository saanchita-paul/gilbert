<template>
    <v-container>
       <v-card class="hood-card mb-5">
          <v-row>
              <v-col cols="12" class="d-flex justify-space-between align-center">

                <div class="d-flex align-center">
                    <v-btn><v-icon left dark>mdi-arrow-left</v-icon>Back</v-btn>
                    <p class="page-title mx-4 mb-0 ">Staedtler Marker </p>
                    <div>
                      <small class="font-weight-bold">
                        Service Interests
                        <span class="mx-1 pa-2 elevation-3" ><v-icon color="yellow">mdi-flash</v-icon> Power</span>
                        <span class="mx-1 pa-2 elevation-3"><v-icon color="red">mdi-fire</v-icon> Gas</span>
                        <span><v-icon color="grey lighten-1">mdi-wifi</v-icon> Internet</span>
                        <span><v-icon color="grey lighten-1">mdi-water</v-icon> Water</span>
                      </small>
                    </div>
                </div>
                <div>
                  <v-btn outlined>Escalate</v-btn>
                  <v-btn outlined>Close Application</v-btn>
                </div>
              </v-col>

              <v-col cols="12">
                <v-divider></v-divider>
              </v-col>
          </v-row>
          <InfoField></InfoField>
       </v-card>


       <v-row>
         <v-col cols="8" class="mb-8 pb-8">
            <v-card class="hood-card">
              <ServiceApplications></ServiceApplications>
            </v-card>
         </v-col>
         <v-col cols="4" class="mb-8 pb-8">
           <v-card class="hood-card">
             <ApplicationNotes></ApplicationNotes>
            </v-card>
         </v-col>
       </v-row>


    <v-footer fixed  class="text-right">
        <v-col class="text-right" cols="12">
          <v-btn color="primary">Submit for Connection</v-btn>
        </v-col>
    </v-footer>

    </v-container>
</template>

<script>
import InfoField from "@scripts/components/crm/leadmanagement/InfoField";
import ServiceApplications from "@scripts/components/crm/leadmanagement/ServiceApplications";
import ApplicationNotes from "@scripts/components/crm/leadmanagement/ApplicationNotes";
export default {
  name: "ApplicationDetailScreen",
  components: {
        InfoField,
        ServiceApplications,
        ApplicationNotes
    },
};
</script>

<style scoped>
</style>
