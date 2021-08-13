<template>
    <v-row>
        <v-col cols="12">
            <p class="sub-title">Notes</p>
            <v-textarea v-model ="note.text"
                outlined
                hide-details="auto"
                placeholder="Notes goes here."
            ></v-textarea>

            <v-btn class="ma-2 float-right" @click="saveNote">Submit Note</v-btn>
        </v-col>

        <v-col cols="12">
                <v-timeline
                    :reverse="reverse"
                    dense
            >
                    <v-timeline-item color="primary" small v-for="nt in notes" :color="getColor(nt.active)" :key="nt.id">
                            <v-card class="elevation-2 timeline-card" :class="{active:false}">
                                <p class="title">{{nt.title}}</p>
                                <p>{{nt.created_at}}</p>
                                <p>{{nt.text}}</p>
                            </v-card>
                    </v-timeline-item>
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
    data() {
      return {
          note: {
              text:''
          },
      }
    },
    methods: {
        getColor(isActive) {

            if(isActive == true) return 'primary';
            return 'gray';
        },
        saveNote() {
            this.$emit('saveNote', this.note);
            this.note.text = '';
        },
    }
};
</script>

<style scoped>
</style>
