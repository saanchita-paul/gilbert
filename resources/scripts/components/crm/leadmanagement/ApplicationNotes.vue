<template>
    <v-row>
        <v-col cols="12">
            <p class="sub-title">Notes</p>
            <v-textarea
                outlined
                hide-details="auto"
                placeholder="Notes goes here."
            ></v-textarea>

            <v-btn class="ma-2 float-right">Submit Note</v-btn>
        </v-col>

        <v-col cols="12">
                <v-timeline
                    :reverse="reverse"
                    dense
            >
                    <v-timeline-item color="primary" small>
                            <v-card class="elevation-2 timeline-card active">
                                <p class="title">Note by John</p>
                                <p>00/00/2021_00:00:00</p>
                                <p>Called Cx. No Answer. Please Callback on 12/20 at 1pm.</p>
                            </v-card>
                    </v-timeline-item>
                    <v-timeline-item color="gray" small>
                            <v-card class="elevation-2  timeline-card">
                                <p class="title">Escalated</p>
                                <p>00/00/2021_00:00:00</p>
                                <p>Called Cx. No Answer. Please Callback on 12/20 at 1pm.</p>
                            </v-card>
                    </v-timeline-item>
                    <v-timeline-item color="gray" small>
                            <v-card class="elevation-2  timeline-card">
                                <p class="title">Confirmed Connection</p>
                                <p>00/00/2021_00:00:00</p>
                                <p>Called Cx. No Answer. Please Callback on 12/20 at 1pm.</p>
                            </v-card>
                    </v-timeline-item>
                    <v-timeline-item color="gray" small>
                            <v-card class="elevation-2  timeline-card">
                                <p class="title">Lead Submitted for Connection</p>
                                <p>00/00/2021_00:00:00</p>
                                <p>Called Cx. No Answer. Please Callback on 12/20 at 1pm.</p>
                            </v-card>
                    </v-timeline-item>
             </v-timeline>
        </v-col>
    </v-row>
</template>

<script>
export default {
  name: "ApplicationNotes",
};
</script>

<style scoped>
</style>
