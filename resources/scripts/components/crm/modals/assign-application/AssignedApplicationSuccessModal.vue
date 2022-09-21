<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <v-toolbar
                    dark
                    color="primary"
                >
                    <v-toolbar-title>Assigned!</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            icon
                            dark
                            @click="done"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-title class="text-sm-subtitle-1 primary--text mb-4">
                    <h3 class="text-large">
                        The applications have been successfully assigned.
                    </h3>
                </v-card-title>

                <v-card-subtitle class="black--text">
                    <p class="text-small">
                        Selected applications are now removed from this office list and will be displayed in their
                        respective offices and agents.
                    </p>
                </v-card-subtitle>

                <v-card-actions class="justify-center mt-n5 pa-5">
                    <v-btn x-large block rounded
                           color="primary" @click="done"
                    >
                        Done
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    name: "AssignedApplicationSuccessModal",
    props: {
        dialog: {
            require: true,
        },
        title: {
            require: true,
        },
    },
    methods: {
        // Close modal
        done() {
            this.$emit('closeSuccessModal');
        },
    }
}
</script>
