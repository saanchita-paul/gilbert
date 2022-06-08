<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <div class="section-dialogs">
                    <v-row>
                    <v-col cols="12">
                        <p class="dialogs-title popup-escalate-title">What’s the reason why you want to Close this application?</p>
                    </v-col>
                    <v-col>
                        <ValidationObserver ref="submit_reason">
                            <ValidationProvider
                                name="Closing reason"
                                rules="required"
                                v-slot="{ errors }"
                            >
                                <v-textarea
                                v-model="close_reason"
                                auto-grow
                                hide-details="auto"
                                :error-messages="errors[0]"
                                placeholder="Please enter your closing reason"
                                ></v-textarea>
                            </ValidationProvider>
                        </ValidationObserver>
                    </v-col>
                    <!-- <v-select
                        outlined
                        dense
                        hide-details="auto"
                        :items="closeReasons"
                        v-model="close_reason"
                        :error-messages="errors[0]"
                        placeholder="Please choose one"
                        >
                    </v-select> -->
                    <v-col cols="12">
                        <div class="d-flex justify-space-between">
                            <v-btn @click="cancelClose">Back</v-btn>
                            <v-btn @click="sucessSaveClose" color="primary">Confirm</v-btn>
                        </div>
                    </v-col>
                    </v-row>
                </div>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>

export default {
    name: "CloseApplicationReasonModal",
    components: {},
    props:{
        dialog: {
            require: true,
        },
        leadSummary: {
            require: true,
        }
    },
    data() {
        return {
            close_reason: '',
            closeReasons: null,
        }
    },
    methods: {
        cancelClose() {
            this.$emit('cancelClose');
        },
        async sucessSaveClose() {
            // await LeadApplicationService.saveEscalateReason(this.close_reason, this.leadSummary.id);
            let v = await this.$refs.submit_reason.validate();
            if(v){
                this.$emit('sucessSaveClose' , this.close_reason);
            };
        }
    }
}
</script>

<style scoped>

</style>
