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

        <v-card color="basil">
            <v-tabs
            v-model="tab"
            background-color="transparent"
            color="basil"
            grow
            >
                <v-tab v-for="item in items" :key="item">{{ item }}</v-tab>
            </v-tabs>

            <v-tabs-items v-model="tab">
                <v-tab-item v-for="item in items" :key="item">
                    
                    <v-col v-if="item == 'Internal Notes'" cols="12" class="notes-container">
                        <v-timeline dense>
                                <v-timeline-item color="primary" small v-for="nt in notes" :color="getColor(nt.active)" :key="nt.id">
                                    <SubmittedNote v-if="nt.type == 'submitted_connection'" :note="nt"> </SubmittedNote>
                                    <SubmittedOriginNote v-if="nt.type == 'submitted_origin'" :note="nt"> </SubmittedOriginNote>
                                    <SubmittedPowershopNote v-if="nt.type == 'submitted_powershop'" :note="nt"> </SubmittedPowershopNote>
                                    <InvalidNote v-else-if="nt.type == 'invalid_property_me_note'" :note="nt"> </InvalidNote>
                                    <Note v-else :note="nt"></Note>
                                </v-timeline-item>
                        </v-timeline>
                    </v-col>
                    
                    <div v-if="item == 'Call History'">
                        <v-col cols="12" class="notes-container" v-if="leadSummary.tsa_call_histories.length">
                            <v-timeline dense>
                                <v-timeline-item color="primary" small v-for="(nt, index) in leadSummary.tsa_call_histories" :key="nt.attempt_id">
                                    <div style="font-weight: bold;"> Attempt {{index+1}} </div>
                                    <div> {{nt.attempt_initiated_timestamp}} </div>
                                    <div> {{nt.attempt_outcome}} </div>
                                </v-timeline-item>
                        </v-timeline>
                    </v-col>
                    </div>
                    
                </v-tab-item>
            </v-tabs-items>
        </v-card>
    </v-row>
</template>

<script>
import Note from "@scripts/components/crm/leadmanagement/notes/Note";
import InvalidNote from "@scripts/components/crm/leadmanagement/notes/InvalidNote";
import SubmittedNote from "@scripts/components/crm/leadmanagement/notes/SubmittedNote";
import SubmittedOriginNote from "@scripts/components/crm/leadmanagement/notes/SubmittedOriginNote";
import SubmittedPowershopNote from "@scripts/components/crm/leadmanagement/notes/SubmittedPowershopNote";

export default {
  name: "ApplicationNotes",
    components: {SubmittedNote, Note, InvalidNote, SubmittedOriginNote, SubmittedPowershopNote},
    props: {
      notes: {
          require: true
      },
      leadSummary: {
          require: true
      },
    },
    data() {
      return {
          reverse: true,
          note: {
              text:'',
              title: '',
          },
          tab: null,
          items: [
              'Internal Notes', 'Call History'
              ],
        //   text: 'Lorem ipsm'
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
/* Helper classes */
.basil {
  background-color: #FFFBE6 !important;
}
.basil--text {
  color: #356859 !important;
  font-family: 'Courier New', Courier, monospace
}
</style>
