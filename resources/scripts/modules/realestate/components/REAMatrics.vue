<template>
    <v-card class="hood-card  mt-0 pt-0">
        <v-row>
            <v-col class="pl-0 pb-0">
                <v-btn class="px-0" text  v-if="agency.type === 0" @click="backToAgency">
                    <v-icon>mdi-chevron-left</v-icon> {{ agency.name }} {{ ' ('+ buttonLabel +')' }}
                </v-btn>
                    <v-btn  class="px-0" text v-else @click="backToOffice">
                          <v-icon>mdi-chevron-left</v-icon>{{ office.name }} {{ ' ('+ buttonLabel +')' }}
                    </v-btn>
               </v-col>
            <v-spacer class="pb-0"></v-spacer>
            <v-col class="text-right  pb-0">
                <v-btn  outlined @click="viewOfficeProfile">Office Profile</v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-row>
                    <v-col cols="12" class="pa-0 ma-0">
                        <p class="mb-0 matrics-header">Applications Data</p>
                    </v-col>

                    <v-col>
                        <AppMetric :data = "matrics.application_metrics.app_created"></AppMetric>
                    </v-col>
                    <v-col>
                        <AppMetric  :data = "matrics.application_metrics.app_waiting_tenant"></AppMetric>
                    </v-col>
                    <v-col>
                        <AppMetric  :data = "matrics.application_metrics.app_submitted_retialer"></AppMetric>
                    </v-col>
                    <v-col>
                        <AppMetric  :data = "matrics.application_metrics.app_successful"></AppMetric>
                    </v-col>
                    <v-col>
                        <AppMetric  :data = "matrics.application_metrics.app_closed"></AppMetric>
                    </v-col>


                </v-row>
            </v-col>
            <v-col cols="1" class="text-center px-0">
                <v-divider vertical></v-divider>
            </v-col>
            <v-col>
                <v-row>
                    <v-col cols="12" class="pa-0 ma-0">
                        <p class="mb-0 matrics-header">Utility Service</p>
                    </v-col>
                    <ServiceMetrics :data="matrics.utility_metrics.ele_metric" title="Electricity" ></ServiceMetrics>
                    <ServiceMetrics :data="matrics.utility_metrics.gas_metric" title="Gas" ></ServiceMetrics>
                    <ServiceMetrics :data="matrics.utility_metrics.internet_metric" title="Internet" ></ServiceMetrics>

                </v-row>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
import LeadMetrics from "@scripts/components/crm/LeadMetrics";
import AppMetric from "@scripts/modules/realestate/components/AppMatric";
import ServiceMetrics from "@scripts/modules/realestate/components/ServiceMetrics";
export default {
    name: "REAMatrics",
    components: {
        AppMetric, LeadMetrics, ServiceMetrics
    },
    props: ['agency', 'matrics', 'office'],
    data(){
        return {
            buttonLabel: 'Performance Operation'
        }
    },
    methods: {
        backToAgency() {
            this.$router.push(
                {
                    name:'real.state.agency.home'
                });
        },
        backToOffice() {
            let agencyId = this.$route.params?.id;
            this.$router.push(
                {
                    name:'real.state.agency.office',params: {'id': agencyId}

                });
        },
        viewOfficeProfile() {
            let officeId = this.$route.params?.officeId;
            let agencyId = this.$route.params?.id;
            this.$router.push(
                {
                    name:'real.state.office.profile',params: {'id': agencyId, 'officeId': officeId}

                });
        },
        setLabelOfBackButton(value){
            if(value == 'user'){
                this.buttonLabel = 'Backend Of Agency'
            } else{
                this.buttonLabel = 'Performance Operation'
            }
        }
    },
    mounted(){
        this.setLabelOfBackButton(this.$route.query?.type);
    },
    watch:{
        $route(value){
            this.setLabelOfBackButton(value.query?.type);
        }
    }

}
</script>

<style scoped>

.matrics{
    display: flex;
    flex-direction: column;
}
.matrics-title {
    font-size: 1.5em;
    color: #542E89;
    font-weight: 700;
}
.matrics-subtitle{
    font-size:  0.75em;
    font-weight: normal;
    color: #7E8A8F;
}
.service {
    font-size: 0.875em;
    font-weight: 700;
}

.matrics-header {
    font-size: 1em;
    font-weight: 700;
}

</style>

