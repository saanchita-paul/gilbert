<template>
    <v-row>
        <v-col cols="12" v-if="applications.length > 0" class="notes-container">
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
        <v-col cols="12" >
            <p class="sub-title">Notes</p>
            <v-textarea
                v-model ="note.text"
                outlined
                hide-details="auto"
                placeholder="Notes goes here."
            ></v-textarea>
            <v-btn class="mt-2 float-right white--text note-button" @click="saveNote" color="#542E89">Submit Note</v-btn>
        </v-col>
    </v-row>
</template>

<script>

import ChatbotApplicationService from "@scripts/services/chatbot/ChatbotApplicationService";
import Note from "@scripts/components/crm/leadmanagement/notes/Note";

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
        }
    },
    methods: {
        async saveNote() {
            if (!this.note.text) return;
            //todo need to call note api in chatbot site
            await ChatbotApplicationService.saveNote(this.note)
            this.note.text = ''
            this.$emit("newNote");
        },
        getColor(index) {
            if(index === 0) return 'primary';
            return 'gray';
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
