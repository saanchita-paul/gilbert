<template>
    <v-card class="hood-card  mt-4">
        <v-row>
            <v-col>
                <v-btn text v-if="agency.type === 0" @click="backToAgency">
                    <v-icon>mdi-chevron-left</v-icon> Buxton - Sunbury (Performance of Operations)
                </v-btn>
                    <v-btn text v-else @click="backToOffice">
                          <v-icon>mdi-chevron-left</v-icon> Buxton - Sunbury (Performance of Operations)
                    </v-btn>
               </v-col>
            <v-spacer></v-spacer>
            <v-col class="text-right">
                <v-btn outlined @click="viewOfficeProfile">Office Profile</v-btn>
            </v-col>
        </v-row>
        <v-row>
            <v-col>
                <v-row>
                    <v-col cols="12" class="pa-0 ma-0">
                        <p class="mb-0 matrics-header">Applications Data</p>
                    </v-col>

                    <v-col>
                        <AppMatric :data = "matrics"></AppMatric>
                    </v-col>
                    <v-col>
                        <AppMatric  :data = "matrics"></AppMatric>
                    </v-col>
                    <v-col>
                        <AppMatric  :data = "matrics"></AppMatric>
                    </v-col>
                    <v-col>
                        <AppMatric  :data = "matrics"></AppMatric>
                    </v-col>
                    <v-col>
                        <AppMatric  :data = "matrics"></AppMatric>
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
                    <v-col cols="4">
                        <p class="mb-0  text-center service"><v-icon color="yellow">mdi-flash</v-icon>Electricity</p>
                        <v-row>
                            <v-col>
                                <v-row class="matrics text-center">
                                    <p class="matrics-title mb-1">{{'1000'}}</p>
                                    <p class="matrics-subtitle">submitted</p>
                                </v-row>
                            </v-col>
                            <v-col cols="1" class="text-center">
                                <v-divider vertical></v-divider>
                            </v-col>
                            <v-col>
                                <v-row class="matrics text-center">
                                    <p class="matrics-title mb-1">{{'1000'}}</p>
                                    <p class="matrics-subtitle">Connected</p>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="4">
                        <p class="mb-0  text-center service"><v-icon color="orange">mdi-fire</v-icon>Gas</p>
                        <v-row>
                            <v-col>
                                <v-row class="matrics text-center">
                                    <p class="matrics-title mb-1">{{'1000'}}</p>
                                    <p class="matrics-subtitle">submitted</p>
                                </v-row>
                            </v-col>
                            <v-col cols="1" class="text-center">
                                <v-divider vertical></v-divider>
                            </v-col>
                            <v-col>
                                <v-row class="matrics text-center">
                                    <p class="matrics-title mb-1">{{'1000'}}</p>
                                    <p class="matrics-subtitle">Connected</p>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="4">
                        <p class="mb-0 service text-center"><v-icon color="#9C27B0">mdi-wifi</v-icon>Internet</p>
                        <v-row>
                            <v-col>
                                <v-row class="matrics text-center">
                                    <p class="matrics-title mb-1">{{'1000'}}</p>
                                    <p class="matrics-subtitle">submitted</p>
                                </v-row>
                            </v-col>
                            <v-col cols="1" class="text-center">
                                <v-divider vertical></v-divider>
                            </v-col>
                            <v-col>
                                <v-row class="matrics text-center">
                                    <p class="matrics-title mb-1">{{'1000'}}</p>
                                    <p class="matrics-subtitle">Connected</p>
                                </v-row>
                            </v-col>
                        </v-row>
                    </v-col>
                </v-row>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
import LeadMetrics from "@scripts/components/crm/LeadMetrics";
import AppMatric from "@scripts/modules/realestate/components/AppMatric";
export default {
    name: "REAMatrics",
    components: {
        AppMatric, LeadMetrics
    },
    props: ['agency'],
    data () {
        return {
            matrics : {
                value: 1000,
                title: 'Application Created'
            }
        }
    },
    methods: {
        backToAgency() {
            let agencyId = this.$route.params?.id;
            this.$router.push(
                {
                    name:'real.state.agency.home'

                });
        },
        backToOffice() {
            let officeId = this.$route.params?.officeId;
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
    },

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

