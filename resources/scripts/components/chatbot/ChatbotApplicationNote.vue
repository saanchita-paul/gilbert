<template>
    <v-row>
        <v-col cols="12" style="background-color: #f8f8f8">
            <p class="sub-title">Notes</p>
            <ValidationObserver ref="application_note_refs">
                <ValidationProvider
                    name="Application Note"
                    rules="required"
                    v-slot="{ errors }"
                >
                    <v-textarea
                        v-model ="note.text"
                        outlined
                        dense
                        hide-details="auto"
                        placeholder="Notes goes here."
                        :error-messages="errors[0]"
                    ></v-textarea>
                </ValidationProvider>
                <v-btn class="mt-2 float-right white--text note-button" @click="saveNote" color="#542E89" v-if="isNoteTextEmpty" :loading="loader">Submit Note</v-btn>
            </ValidationObserver>
        </v-col>
        <v-col cols="12" v-if="applications.length > 0" class="notes-container" style="background-color: #f8f8f8">
            <v-timeline dense>
                <v-timeline-item color="primary" small v-for="(item, index) in applications" :color="getColor(index)" :key="index">
                    <v-card class="elevation-2 timeline-card" color="gray" :class="{active:index===0}">
                        <!--need to make this title dynamic according to note title-->
                        <p class="title">Note by [Hood][HOOD ADMIN]</p>
                        <p>{{item.created_at}}</p>
                        <p>{{item.text}}</p>
                    </v-card>
                </v-timeline-item>
            </v-timeline>
        </v-col>
    </v-row>
</template>

<script>

import ChatbotApplicationService from "@scripts/services/chatbot/ChatbotApplicationService";
import Note from "@scripts/components/crm/leadmanagement/notes/Note";
import {isEmpty} from "lodash-es";
import dayjs from "dayjs";

export default {
    name: "ChatbotApplicationNote",
    props : ["applications"],
    components: {Note},
    data() {
        return {
            note: {
                text:'',
                title: '',
                moving_utility_data_id : this.$route.query.app_id ?? null,
            },
            loader : false
        }
    },
    computed: {
      isNoteTextEmpty(){
          return !isEmpty(this.note.text)
      }
    },
    methods: {
        async saveNote() {
            if(!await this.validateFormData('application_note_refs')) return;
            //todo need to call note api in chatbot site
            this.loader = true;
            let response = await ChatbotApplicationService.saveNote(this.note);
            response.created_at = dayjs.utc(response.created_at).local().format("DD/MM/YYYY hh:mm A");
            this.applications.unshift(response);
            this.note.text = '';
            await this.$refs['application_note_refs'].reset();
            this.loader = false;
        },
        getColor(index) {
            if(index === 0) return 'primary';
            return 'gray';
        },
        async validateFormData(reference){
            return await this.$refs[reference].validate();
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
.v-tab {
    text-transform: capitalize !important;
}
.note-button{
    font-weight: 700;
    font-size: 14px;
}
</style>
