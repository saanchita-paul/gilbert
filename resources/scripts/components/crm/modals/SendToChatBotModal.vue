<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-container>
                    <v-row>
                        <v-col>
                            <div class="dialogs-title d-flex title-design">
                                <p >{{title}} has been assigned to HOOD Chatbot.</p>
                            </div>
                            <p class="title-design" style="font-size: 20px;">It will now show on the chatbot leads.</p>

                            <div class="d-flex justify-center">
                                <v-btn @click="done" color="primary">Done</v-btn>
                            </div>
                        </v-col>

                    </v-row>
                </v-container>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>

export default {
name: "SendToChatBotModal" ,

    props:['dialog','title'],
    methods: {
        done() {
            this.$emit('done');
        }
    },

    watch:{
        title: function (newTitle, previousTitle) {
        }
    }

}
</script>

<style scoped>
.title-design {
    text-align: center;
}
</style>
