<template>
    <v-card class="hood-card-light">
        <v-row>
            <v-col cols="5">
                <h3 v-if="type === 'energy'" class="page-title-text">Here’s a summary of all applications for
                    <br>
                    Energy
                    <v-icon color="yellow">mdi-flash</v-icon>
                    <v-icon color="red">mdi-fire</v-icon>
                </h3>
                <h3 v-else class="page-title-text">Here’s a summary of all applications for
                    <br>
                    Water
                    <v-icon color="blue">mdi-water</v-icon>
                </h3>
                <p class="my-2">
                    As of <span class="font-weight-medium">today</span>.
                </p>
                <p class="my-2 text-light">You can change these parameters using the filters on the top right corner.</p>
            </v-col>
            <v-col cols="7" class="d-flex">
                <div style="width:60%" class="d-flex">
                    <v-divider class="vertical-divider" vertical></v-divider>
                    <div class="ml-4">
                        <h4>Open Applications</h4>
                        <div class="d-flex mt-4">
                            <div>
                                <h3 class="application-count">{{summaryData.total_open_application}}</h3>
                                <p class="text-small">Total open applications</p>
                            </div>
                            <div class="ml-6">
                                <h3 class="application-count">{{summaryData.total_closed}}</h3>
                                <p class="text-small">Consent pending</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="width:40%" class="d-flex">
                    <v-divider class="vertical-divider" vertical></v-divider>
                    <div class="ml-4">
                        <h4>Closed Applications</h4>
                        <div class='mt-4'>
                            <h3 class="application-count">{{summaryData.total_consent_pending}}</h3>
                            <p class="text-small">Total closed applications</p>
                        </div>
                    </div>
                </div>
            </v-col>
        </v-row>
    </v-card>
</template>

<script>
export default {
    name: "SalesSummary",
    props: {
        type: {
            type: String,
            default: 'energy'
        },

        summaryData: {
            type: Object,
            require: true
        }
    },
    mounted() {
        console.log('summaryData', this.summaryData);
    }
};
</script>

<style scoped>
    .page-title-text {
        font-size: 28px;
        font-weight: 700;
        color: #263238;
        line-height: 1.3;
    }
    .application-count {
        font-size: 35px;
        font-weight: bold;
        color: #5C229A
    }
    .text-light {
        font-size: 12px;
    }
    .text-small {
        font-size: 14px;
        font-weight: bold;
    }
    .vertical-divider {
        border-width: 1px !important;
    }
</style>
