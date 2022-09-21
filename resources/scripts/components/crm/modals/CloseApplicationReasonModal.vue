<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="400px"
        >
            <v-card>
                <div class="section-dialogs">
                <ValidationObserver ref="submit_reason">
                    <v-row>
                        <v-col cols="12">
                            <p class="dialogs-title popup-escalate-title">What’s the reason why you want to Close this application?</p>
                        </v-col>

                        <v-col cols="12">
                                    <ValidationProvider
                                        name="Closing reason"
                                        rules="required"
                                        v-slot="{ errors }"
                                    >
                                    <v-select
                                        hide-details="auto"
                                        :error-messages="errors[0]"
                                        outlined
                                        dense
                                        :items="closeReasons"
                                        v-model="closeReasonId"
                                        placeholder="Please choose one"
                                        >
                                    </v-select>
                                    </ValidationProvider>

                        </v-col>
                        <v-col cols="12" v-if="closeReasonId">
                                <ValidationProvider
                                    name="Closing reason"
                                    v-slot="{ errors }"
                                >
                                    <v-textarea
                                    v-model="closeReason"
                                    outlined
                                    auto-grow
                                    hide-details="auto"
                                    :error-messages="errors[0]"
                                    placeholder="Please add additional closure information (if any)"
                                    ></v-textarea>
                                </ValidationProvider>
                        </v-col>
                    </v-row>
                    
                    <!-- <v-row> -->
                        <v-col cols="12">
                            <div class="d-flex justify-space-between">
                                <v-btn @click="cancelClose">Back</v-btn>
                                <v-btn @click="sucessSaveClose" color="primary">Confirm</v-btn>
                            </div>
                        </v-col>
                    <!-- </v-row> -->
                </ValidationObserver>
                </div>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>
import AppCloseReasonService from "@scripts/services/AppCloseReasonService";

export default {
    name: "CloseApplicationReasonModal",
    components: {},
    props:{
        dialog: {
            require: true,
        },
        leadSummary: {
            require: true,
        },
    },
    data() {
        return {
            closeReason: '',
            closeReasonId: null,
            close_other_reason: '',
            closeReasons: [],
        }
    },
    async mounted() { 
        this.closeReasons = await AppCloseReasonService.getAppCloseReasonData();
    },
    computed: {
         isOthersReason() {
             let item = this.closeReasons.find(r => r.value === this.closeReasonId);

             return item?.text?.toLowerCase() === 'others';

        },
    },
    
    methods: {
        cancelClose() {
            this.$emit('cancelClose');
        },
        async sucessSaveClose() {
            // await LeadApplicationService.saveEscalateReason(this.close_reason, this.leadSummary.id);
            let v =  await this.$refs.submit_reason.validate();
            if(v && this.closeReasonId){
                this.$emit('sucessSaveClose' , {reason_id: this.closeReasonId, reason_text: this.closeReason});
            };
        },

    }
}
</script>

<style scoped>

</style>
