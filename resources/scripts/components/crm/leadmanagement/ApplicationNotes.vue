<template>
    <v-row>
        <v-col cols="12">
            <p class="sub-title">Notes</p>
            <v-textarea v-model ="newNote"
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
                    <v-timeline-item color="primary" small v-for="note in notes" :color="getColor(note.active)" :key="note.id">
                            <v-card class="elevation-2 timeline-card" :class="{active:false}">
                                <p class="title">{{note.title}}</p>
                                <p>{{note.created_at}}</p>
                                <p>{{note.text}}</p>
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
          newNote: null,
      }
    },
    methods: {
        getColor(isActive) {

            if(isActive == true) return 'primary';
            return 'gray';
        },
        saveNote() {
            this.$emit('saveNote', this.newNote);
            this.newNote = null;
        }
    }
};
</script>

<style scoped>
</style>
