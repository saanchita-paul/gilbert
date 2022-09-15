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
                    <v-toolbar-title>Please confirm assignment</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-toolbar-items>
                        <v-btn
                            icon
                            dark
                            @click="closeModal"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-toolbar-items>
                </v-toolbar>
                <v-card-title>
                    <h3 class="text-large">
                        Are you sure you want to assign these applications?
                    </h3>
                </v-card-title>

                <v-card-subtitle>
                    <p class="text-small">
                        Selected applications will be removed from this office list and will be displayed in their
                        respective offices and agents.
                    </p>
                </v-card-subtitle>

                <v-card-actions class="justify-center">
                    <v-btn x-large class="ps-16 pe-16" rounded
                           @click="closeModal"
                    >
                        No
                    </v-btn>
                    <v-btn x-large class="ps-16 pe-16" rounded
                           color="primary" @click="done"
                    >
                        Yes
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
    name: "AssignApplicationsConfirmModal",
    props: {
        dialog: {
            require: true,
        },
        title: {
            require: true,
        },
    },
    methods: {
        // Done and open success modal
        done() {
            this.$emit('openSuccessModal');
        },
        // Close success modal
        closeModal() {
            this.$emit('closeConfirmModal');
        }
    }
}
</script>

<style scoped>

</style>
