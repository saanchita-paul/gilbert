<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" class='d-flex fixed-height'>
                <v-spacer></v-spacer>
                <v-select
                    class='type-select'
                    :items="items"
                    solo
                    dense
                    label="Type"
                ></v-select>
                <v-text-field
                    class='date-select'
                    solo
                    dense
                    label="Calender"
                    placeholder="Today"
                    append-icon="mdi-calendar-range"
                    readonly
                ></v-text-field>
                <v-btn class='download-button'>
                    Download
                    <v-icon right>mdi-download</v-icon>
                </v-btn>
            </v-col>
            <v-col cols="12">
                <v-card class="hood-card-light">
                    <v-row>
                        <v-col cols="6">
                            <h3 class="page-title">Here’s a summary of all applications for</h3>
                            <h3 class="page-title">
                                Energy
                                <v-icon color="yellow">mdi-flash</v-icon>
                                <v-icon color="red">mdi-fire</v-icon>
                            </h3>
                            <p class="my-2">
                                As of <span class="font-weight-medium">today</span>.
                            </p>
                            <p class="my-2 text-small">You can change these parameters using the filters on the top right corner.</p>
                        </v-col>
                        <v-col cols="6" class="d-flex">
                            <div style="width:64%" class="d-flex">
                                <v-divider class="vertical-divider" vertical></v-divider>
                                <div class="ml-4">
                                    <h4>Open Applications</h4>
                                    <div class="d-flex mt-4">
                                        <div>
                                            <h3 class="application-count">16,032</h3>
                                            <p class="text-small">Total open applications</p>
                                        </div>
                                        <div class="ml-6">
                                            <h3 class="application-count">0</h3>
                                            <p class="text-small">Consent pending</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="width:36%" class="d-flex">
                                <v-divider class="vertical-divider" vertical></v-divider>
                                <div class="ml-4">
                                    <h4>Closed Applications</h4>
                                    <div class='mt-4'>
                                        <h3 class="application-count">16,032</h3>
                                        <p class="text-small">Total closed applications</p>
                                    </div>
                                </div>
                            </div>
                        </v-col>
                    </v-row>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>

export default {
    name: "ApplicationsDashboardPage",
    components: { 
    },
    data() {
        return {
            items: ['Energy', 'Water'],
        }
    },
}
</script>

<style scoped>
    .type-select {
        max-width: 180px;
        margin-right: 8px;
    }
    .date-select {
        max-width: 250px;
        margin-right: 8px;
    }
    .download-button {
        background-color: white !important;
        max-width: 180px;
        margin-right: 8px;
    }
    .application-count {
        font-size: 34px;
        font-weight: bold;
        color: #5C229A
    }
    .text-small {
        font-size: 14px;
    }
    .fixed-height {
        max-height: 55px !important;
    }
    .vertical-divider {
        border-width: 1px !important;
    }
</style>

