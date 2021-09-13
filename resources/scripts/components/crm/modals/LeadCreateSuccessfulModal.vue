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
                            <div class="dialogs-title d-flex justify-center">
                                <p>Thank you!</p>
                            </div>
                            <div class="dialogs-title d-flex justify-center">
                                <p >{{title}} is added to your applications!</p>
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
import Created from "@scripts/components/crm/Created";

export default {
name: "LeadCreateSuccessfulModal" ,
    components: {Created},
    props:['dialog','title'],
    methods: {
        done() {
            this.$emit('done');
        }
    },

    watch:{
        title: function (newTitle, previousTitle) {
        }
    }

}
</script>

<style scoped>

</style>
