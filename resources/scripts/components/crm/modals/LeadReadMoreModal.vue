<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <v-container>
                    <v-row class="section-dialogs">
                        <v-col cols="12">
                            <span class="dialogs-title">
                                Agent’s Additional Instructions
                            </span>
                        </v-col>
                        <v-col cols="12">
                            <v-textarea
                                :value="readmore"
                                readonly
                                outlined
                                hide-details="auto"
                                placeholder="Additional Instructions goes here."
                            ></v-textarea>
                        </v-col>
                        <v-col cols="12">
                            <v-btn block color="primary" @click="closeReadMore">Close</v-btn>
                        </v-col>
                    </v-row>
                </v-container>

            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
export default {
name: "LeadReadMoreModal",
    props: {
        dialog: {
            required: true
        },
        readmore: {
            required: true
        }
    },

    methods: {
        closeReadMore() {
            this.$emit('close');
        }
    }

}
</script>

<style scoped>

</style>
