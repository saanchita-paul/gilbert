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
                    <v-toolbar-title>Unlock the application?</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-card-title class="primary--text text-sm-body-1 mb-4">
                    <h3 class="text-large">
                        Unlocking this application will lock the customer’s view and application will still be assigned
                        to chatbot user.
                    </h3>
                    <p class="mt-5 text-color">Are you sure you want to continue?</p>
                </v-card-title>

                <v-card-actions class="justify-center mt-n5 pb-6">
                    <v-btn large class="ps-16 pe-16 mr-2" @click="closeUnlockConfirmModal">
                        Cancel
                    </v-btn>
                    <v-btn large class="ps-16 pe-16" color="primary" @click.prevent="submitHandler">
                        Yes, continue
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    name: "ApplicationUnlockConfirmModal",
    props: {
        dialog: {
            required: true,
        },
    },
    methods: {
        closeUnlockConfirmModal() {
            this.$emit('closeUnlockConfirmModal');
        },
        submitHandler() {
            this.$emit('confirmUnlock');
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
