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
                                v-model="agentInstruction"
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
        }
    },

    data() {
        return  {
            agentInstruction: 'Additional Instructions goes here. Maybe it’s extra long so I have to write something down to show how it will look like when a property manager has soooo much things to say about his lead.  Maybe another ine of the agent forgot to say something about the lead.',
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
