<template>
    <v-row>
        <v-col cols="12" >
            <v-tabs
                v-model="tab"
                background-color="transparent"
                color="basil"
                grow
            >
                <!--  Internal note start-->
                <v-tab href="#internalNotes">
                    Internal Notes
                </v-tab>
                <v-tab-item value="internalNotes">
                    <p class="sub-title py-2">Notes</p>
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
                        <v-row v-if="isNoteTextEmpty" class="pa-3">
                            <v-col cols="7" class="pa-0">
                                <span class="orange--text">{{ unsavedWarningMessage }}</span>
                            </v-col>
                            <v-col cols="5" class="d-flex px-0 justify-end">
                                <v-btn class="white--text note-button" @click="saveNote" color="#542E89"  :loading="loader">Submit Note</v-btn>
                            </v-col>
                        </v-row>

                    </ValidationObserver>
                    <v-col cols="12" v-if="applications.length > 0" class="notes-container" >
                        <v-timeline dense>
                            <v-timeline-item color="primary" small v-for="(item, index) in applications" :color="getColor(index)" :key="index">
                                <v-card class="elevation-2 timeline-card" color="gray" :class="{active:index===0}">
                                    <!--need to make this title dynamic according to note title-->
                                    <p class="title">Note by [{{ item.created_by }}] [{{ item.user_role }}]</p>
                                    <p>{{item.created_at}}</p>
                                    <p>{{item.text}}</p>
                                </v-card>
                            </v-timeline-item>
                        </v-timeline>
                    </v-col>
                </v-tab-item>
                <!--  Internal note end-->

                <!--  Status Log start-->
                <v-tab href="#statusLog">
                    Status Log
                </v-tab>
                <v-tab-item value="statusLog">
                    <v-col cols="12" v-if="statusLog.length > 0" class="notes-container" >
                        <v-timeline dense>
                            <v-timeline-item color="primary" small v-for="(item, index) in statusLog" :color="getColor(index)" :key="index">
                                <v-card class="elevation-2 timeline-card" color="gray" :class="{active:index===0}">
                                    <!--need to make this title dynamic according to note title-->
                                    <p class="title">Note by [{{ item.created_by }}] [{{ item.user_role }}]</p>
                                    <p>{{item.created_at}}</p>

                                    <div class="my-4" v-if="item.text.new_status">
                                        <p class=" title">New Status</p>
                                        <p v-for="[key, value] of Object.entries(item.text.new_status)">
                                            {{key}} : {{value}}
                                        </p>
                                    </div>

                                    <div class="my-4" v-if="item.text.old_status">
                                        <p class="title">Previous Status</p>
                                        <p v-for="[key, value] of Object.entries(item.text.old_status)">
                                            {{key}} : {{value}}
                                        </p>
                                    </div>
                                </v-card>
                            </v-timeline-item>
                        </v-timeline>
                    </v-col>
                </v-tab-item>
                <!--  Status Log end-->
            </v-tabs>

        </v-col>

    </v-row>
</template>

<script>

import ChatbotApplicationService from "@scripts/services/chatbot/ChatbotApplicationService";
import Note from "@scripts/components/crm/leadmanagement/notes/Note";
import {isEmpty} from "lodash-es";
import dayjs from "dayjs";
import CHATBOT_APP_DATA from "@scripts/data/constants/CHATBOT_APP_DATA";

export default {
    name: "ChatbotApplicationNote",
    props : ["applications", "statusLog"],
    components: {Note},
    data() {
        return {
            note: {
                text:'',
                title: '',
                moving_utility_data_id : this.$route.query.app_id ?? null,
            },
            loader : false,
            unsavedWarningMessage : CHATBOT_APP_DATA.WARNING_MESSAGE,
            tab: null,
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
