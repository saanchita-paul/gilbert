<template>
    <v-row>
        <v-col cols="12">
            <p class="sub-title">Notes</p>
            <ValidationObserver ref="submit_note">
            <ValidationProvider name="Expired Date" rules="required"  v-slot="{ errors }">

            <v-textarea v-model ="note.text"
                outlined
                        hide-details="auto"
                placeholder="Notes goes here."
            ></v-textarea>
            </ValidationProvider>
            </ValidationObserver>

            <v-btn class="ma-2 float-right" @click="saveNote">Submit Note</v-btn>
        </v-col>

        <v-col cols="12" class="notes-container">
                <v-timeline
                    dense
            >
                    <v-timeline-item color="primary" small v-for="nt in notes" :color="getColor(nt.active)" :key="nt.id">
                        <SubmittedNote v-if="nt.type == 'submitted_connection'" :note="nt"> </SubmittedNote>
                        <SubmittedOriginNote v-if="nt.type == 'submitted_origin'" :note="nt"> </SubmittedOriginNote>
                        <InvalidNote v-else-if="nt.type == 'invalid_property_me_note'" :note="nt"> </InvalidNote>
                        <Note v-else :note="nt"></Note>
                    </v-timeline-item>
             </v-timeline>
        </v-col>
    </v-row>
</template>

<script>
import Note from "@scripts/components/crm/leadmanagement/notes/Note";
import InvalidNote from "@scripts/components/crm/leadmanagement/notes/InvalidNote";
import SubmittedNote from "@scripts/components/crm/leadmanagement/notes/SubmittedNote";
import SubmittedOriginNote from "@scripts/components/crm/leadmanagement/notes/SubmittedOriginNote";

export default {
  name: "ApplicationNotes",
    components: {SubmittedNote, Note, InvalidNote, SubmittedOriginNote},
    props: {
      notes: {
          require: true
      }
    },
    data() {
      return {
          reverse: true,
          note: {
              text:'',
              title: '',
          },
      }
    },
    methods: {
        getColor(isActive) {

            if(isActive == true) return 'primary';
            return 'gray';
        },
      async saveNote() {

            let v =  await this.$refs.submit_note.validate();
            if(!v) return;

            this.$emit('saveNote', this.note);
            this.note.text = '';
        },
    }
};
</script>

<style scoped>
.notes-container{
  max-height: 500px;
  overflow-y: auto;
}
</style>
