<template>
    <v-col
        cols="12"
        v-if="selectedPowerProvider === 'ea' && afterHourFlag && selected_plan"
    >
        <p>
            <span class="font-weight-bold">Important:</span> You
            are about to submit a same-day connection.
            Processing same-day connections to Energy Australia
            will incur same-day connection fee for the customer.
        </p>

        <p class="my-0">
            <span class="font-weight-bold">Note:</span> If
            customer doesn’t consent to pay, a new connection
            date (at least 2 business days from today) will have
            to be selected.
        </p>

        <v-container class="px-0" fluid>
            <p>
                Does the applicant consent to pay for the
                additional fees?
            </p>
            <v-radio-group
                v-model="leadSummary.after_hour_payee"
                @change="changeAfterHourPayee"
            >
                <v-radio
                    label="Yes, applicant consents to pay"
                    value="applicant"
                ></v-radio>
                <v-radio
                    label="Hood will pay for the same-day connection fee"
                    value="hood"
                ></v-radio>
            </v-radio-group>
        </v-container>
    </v-col>
</template>

<script>

export default {
    name: "SameDayConnection",
    data() {
        return {

        };
    }
};
</script>