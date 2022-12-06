<template>
    <v-row justify="center">
        <v-dialog
            v-model="dialog"
            persistent
            max-width="900px"
        >
            <div>
                <ConfirmInternetSubmission
                    @backToEdit="backToEdit"
                    @confirmSubmitLead="confirmSubmitLead">
                </ConfirmInternetSubmission>
            </div>
        </v-dialog>
    </v-row>
</template>

<script>
import ConfirmInternetSubmission from "@scripts/components/crm/leadmanagement/ConfirmInternetSubmission";

export default {
    name: "InternetSubmitConfirmationModal",
    components: {ConfirmInternetSubmission},
    props: {
        dialog: {
            require: true,
        },
    },
    methods: {
        backToEdit() {
            this.$emit('backToEdit');
        },
        confirmSubmitLead() {
            this.$emit('confirmSubmitLead');
        }
    }
}
</script>

<style scoped>

</style>
