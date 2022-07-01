<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <div class="dialogs-section">
                    <v-row>
                    <v-col cols="12">
                        <p class="dialogs-title title-design">Chatbot is in charge...</p>
                            <v-img class="center-design" src="/assets/images/lock-chatbot.png" max-height="100" max-width="100" />
                            
                      
                    </v-col>
                    <v-col>
                        <div class="justify-center">
                            <p>Application is currently locked because it is being handled by our chatbot.</p>
                            <p>Click the <span class="bolded">"Unlock Application" </span>button below to manually process this application.</p>
                            <p class="pro-tips-text"><span class="bolded">Pro Tip: </span> Unlocking will automatically assign this application to you.</p>
                        </div>
                    </v-col>
                    <v-col cols="12">
                        <div class="d-flex justify-center">
                            <v-btn border>Unlock Application <v-icon class="pl-3">mdi-lock-open</v-icon></v-btn>
                        </div>
                    </v-col>
                    </v-row>
                </div>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>

export default {
    name: "ChatbotInChargeModal",
    props:['dialog'],
    methods: {
        done() {
            console.log("click seems ok");
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
.pro-tips-text {
    align-content: center;
    color: #7E8A8F;
    margin-top: 10px;
}
.center-design {
    position: center;
}
.dialogs-section{
    padding: 50px 50px 50px 50px;
}
.bolded {
    font-weight: bold;
}
</style>
