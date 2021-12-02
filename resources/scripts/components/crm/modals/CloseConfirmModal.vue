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
                                <p class="text-center">{{title}} has been Closed!!</p>
                            </div>
                            <div class="d-flex justify-center">
                                <p class="text-center">{{title}} has now been Closed.</p>
                            </div>
                            <div class="d-flex justify-center">
                                <p class="text-center">To access this application again, click on the "Closed" applications card.</p>
                            </div>
                            <div class="d-flex justify-center">
                                <v-btn @click="done" block
                                       color="primary"
                                >Done
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
name: "CloseConfirmModal",
    props:{
        dialog: {
            require: true,
        },
        title: {
            require: true,
        },
    },
    methods: {
        done() {
            this.$emit('escalateCompleted');
            this.$router.push({name:'applications'});
        }
    }
}
</script>

<style scoped>

</style>
