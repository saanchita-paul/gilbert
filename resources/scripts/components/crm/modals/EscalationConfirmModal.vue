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
                        <v-col class="section-dialogs" cols="12">
                            <div class="dialogs-title popup-escalate-title d-flex justify-center">
                                <p>{{title}} has been Escalated!!</p>
                            </div>
                            <div class="d-flex justify-center">
                                <p>{{title}} has now been moved to Escalated Applications.</p>
                            </div>
                            <div class="d-flex justify-center">
                                <v-btn @click="done"
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
name: "EscalationConfirmModal",
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
