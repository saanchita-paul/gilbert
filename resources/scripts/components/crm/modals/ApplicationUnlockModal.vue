<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="700px"
        >
            <v-card color="primary" class="dialogs-section">
                <v-card-title>
                    <div class="d-flex align-center">
                        <v-img src="/assets/images/lock-chatbot.png" max-width="50" class="mr-4" />
                        <p class="title">Chatbot is in charge..</p>
                    </div>
                </v-card-title>
                <v-card-text class="mt-4">
                    <div class="white--text font-size-14">
                        <p>Application is locked because it is being handled by our chatbot. <br>
                            Click the <span class="font-weight-bolder">"Unlock Application"</span> button below to manually process this application.</p>
                        <p><span class="font-weight-bolder">Pro Tip: </span> Unlocking will assign this application to this role <span class="font-weight-bolder">"Unlocked Chatbot"</span></p>
                    </div>
                </v-card-text>
                <v-card-actions>
                    <div>
                        <v-btn border>
                           Escalate
                        </v-btn>
                        <v-btn border>
                            Close Application
                        </v-btn>
                    </div>
                    <div>
                        <v-btn @click="openUnlockConfirmModal" border>
                            Unlock Application <v-icon class="pl-3">mdi-lock-open</v-icon>
                        </v-btn>
                    </div>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    name: "ApplicationUnlockModal",
    props:{
        dialog: {
            require: true,
        },
    },
    data() {
        return {
        }
    },
    methods: {
        openUnlockConfirmModal() {
            this.$emit('openUnlockConfirmModal');
        },
    }
}
</script>

<style scoped>
.v-dialog__content {
    align-items: end !important;
    justify-content: end !important;
}
.dialogs-section {
    padding: 20px;
}
.title {
    font-weight: 700;
    font-size: 18px;
    line-height: 23px;
    color: #FFFFFF;
}
.font-size-14 {
    font-size: 14px;
}
.font-weight-bolder {
    font-weight: 700;
}
.v-card__actions {
    padding: 0 24px !important;
    justify-content: space-between !important;
}
</style>
