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
                    <v-timeline-item color="primary" small v-for="note in notes" :color="getColor(note.active)" :key="note.id">
                            <v-card class="elevation-2 timeline-card" :class="{active:false}">
                                <p class="title">{{note.title}}</p>
                                <p>{{note.created_at}}</p>
                                <p>{{note.text}}</p>
                            </v-card>
                    </v-timeline-item>
                    <v-timeline-item color="gray" small>
                            <v-card class="elevation-2  timeline-card">
                                <p class="title">Escalated</p>
                                <p>00/00/2021_00:00:00</p>
                                <p>Called Cx. No Answer. Please Callback on 12/20 at 1pm.</p>
                            </v-card>
                    </v-timeline-item>
<!--                    <v-timeline-item color="gray" small>-->
<!--                            <v-card class="elevation-2  timeline-card">-->
<!--                                <p class="title">Confirmed Connection</p>-->
<!--                                <p>00/00/2021_00:00:00</p>-->
<!--                                <p>Called Cx. No Answer. Please Callback on 12/20 at 1pm.</p>-->
<!--                            </v-card>-->
<!--                    </v-timeline-item>-->
<!--                    <v-timeline-item color="gray" small>-->
<!--                            <v-card class="elevation-2  timeline-card">-->
<!--                                <p class="title">Lead Submitted for Connection</p>-->
<!--                                <p>00/00/2021_00:00:00</p>-->
<!--                                <p>Called Cx. No Answer. Please Callback on 12/20 at 1pm.</p>-->
<!--                            </v-card>-->
<!--                    </v-timeline-item>-->
             </v-timeline>
        </v-col>
    </v-row>
</template>

<script>
export default {
  name: "ApplicationNotes",
    props: {
      notes: {
          require: true
      }
    },
    methods: {
        getColor(isActive) {

            if(isActive == true) return 'primary';
            return 'gray';
        }
    }
};
</script>

<style scoped>
</style>
