<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <v-container>
                    <v-row>
                        <v-col cols="12" class="px-6 py-6">
                            <div class="dialogs-title popup-escalate-title d-flex justify-center">
                                <p class="text-center">Rejection Reason</p>
                            </div>

                            <div class="d-flex justify-center">
                                <p class="text-center">Invalid Something</p>
                            </div>
                            <div class="d-flex justify-center">
                                <v-btn @click="$emit('close')" block
                                       color="primary"
                                >OK
                                </v-btn>
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
    name: "RejectionReasonModal",
    props: {
        dialog: {
            require: true,
        },
    },
    data() {
        return {

        }
    },
    methods: {
        done() {

        }
    }
}
</script>

<style scoped>

</style>
