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
                            <div class="dialogs-title d-flex justify-center">
                                <p class="text-center">{{message}}</p>
                            </div>
                            <div class="d-flex justify-center">
                                <v-btn @click="close" block
                                       color="primary"
                                >Close
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
name: "PreventSubmissionModal",
    props:{
        dialog: {
            require: true,
        },
        message: {
            require: true,
        }
    },
    methods: {
        close() {
            this.$emit('closeMessage');
        }
    }
}
</script>

<style scoped>

</style>
