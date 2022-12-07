<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-toolbar-title>Application Status</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            icon
                            dark
                            @click="closeModal"
                        >
                            <v-icon>mdi-close</v-icon>
                            Close
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-title class="primary--text text-sm-body-1 mb-4">
                    <h3 class="text-large">
                        You are about to change this application status. <br>Do you want to continue?
                    </h3>
                </v-card-title>

                <v-card-actions class="justify-center mt-n5 pb-6">
                    <v-btn x-large class="ps-16 pe-16 mr-2" rounded
                           @click="closeModal"
                    >
                        Cancel
                    </v-btn>
                    <v-btn x-large class="ps-16 pe-16" rounded
                           color="primary" @click="done" :loading="isLoading"
                    >
                        Update Status
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    name: "StatusChangeConfirmationModal",
    props: {
        dialog: {
            required: true,
        },
        isLoading: {
            required: true,
        }
    },
    methods: {
        // Done and open success modal
        done() {
            this.$emit('submitStatus');
        },
        // Close success modal
        closeModal() {
            this.$emit('closeConfirmModal');
        }
    }
}
</script>
