<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="550px"
        >
            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-toolbar-title>Please confirm.</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-card-title class="primary--text text-sm-body-1 mb-4">
                    <h3 class="text-large">
                        This will assign the application back to the chatbot.
                    </h3>
                    <p class="mt-5 text-color">Are you sure you want to continue?</p>
                </v-card-title>

                <v-card-actions class="justify-center mt-n5 pb-6">
                    <v-btn large class="ps-16 pe-16 mr-2" @click="dialog=false">
                        Cancel
                    </v-btn>
                    <v-btn large class="ps-16 pe-16" color="primary">
                        Yes, continue
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    name: "SendToChatbotConfirmModal",
    props: {
        dialog: {
            required: true,
        },
    },
    methods: {
        closeUnlockConfirmModal() {
            this.$emit('closeUnlockConfirmModal');
        }
    }
}
</script>

<style scoped>
.text-color {
    color: #263238;
}

.v-card__title {
    word-break: initial !important;
}
</style>
