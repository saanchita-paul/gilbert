<template>
    <v-card class="elevation-2 timeline-card" color="gray" :class="{active:false}">
        <p class="title">{{ getTitle }}</p>
        <p>{{ note.created_at }}</p>

        <p class="title">Utility Type:<span class="note-data"> {{ note.leads.utility_type || 'N/A' }}</span></p>
        <p class="title">Applicant Name: <span class="note-data">{{ note.leads.applicant_name || 'N/A' }}</span></p>
        <p class="title">Connection Address: <span class="note-data">{{ note.leads.connection_address || 'N/A' }}</span></p>
        <p class="title">
            Connection Date:
            <span class="note-data">{{ connection_date || 'N/A' }}</span>
        </p>
        <p class="title mt-3">Lead Source: <span class="note-data">{{ note.leads.lead_source || 'N/A' }}</span></p>
        <p class="title">Agency: <span class="note-data">{{ note.leads.agency || 'N/A' }}</span></p>
        <p class="title">Agent Name: <span class="note-data">{{ note.leads.agent_name || 'N/A' }}</span></p>
        <p class="title mt-3">Supplier Name: <span class="note-data">{{ note.leads.supplier_name || 'N/A' }}</span></p>
        <p class="title mt-3">Plan Name: <span class="note-data">{{ note.leads.plan_name || 'N/A' }}</span></p>
        <p class="title mt-3">Modem Type: <span class="note-data">{{ note.leads.modem_type || 'N/A' }}</span></p>
        <p class="title mt-3">Initial Payment Amount: <span class="note-data">${{ getInitialPaymentAmount || 'N/A' }}</span></p>
        <p class="title mt-3">Phone Calls: <span class="note-data">{{ note.leads.phone_calls || 'N/A' }}</span></p>
        <p class="title mt-3">Homephone: <span class="note-data">{{ note.leads.home_phone_number || 'N/A' }}</span></p>
        <p class="title mt-3">Current Provider: <span class="note-data">{{ note.leads.current_provider || 'N/A' }}</span></p>
        <p class="title mt-3">Account Number: <span class="note-data">{{ note.leads.account_number || 'N/A' }}</span></p>
        <p class="title mt-3">Medical or Security Alarm: <span class="note-data">{{ note.leads.medical_security || 'N/A' }}</span></p>
        <p class="title mt-3">Back to base: <span class="note-data">{{ note.leads.back_to_base || 'N/A' }}</span></p>

        <v-btn outlined small
               color="indigo" class="my-4" :href="note.leads.plan_link || '#'" target="_blank">
            Show Plan
        </v-btn>

    </v-card>
</template>

<script>
import DayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";

export default {
    name: "SubmittedGoodtelNote",
    props: ['note'],
    computed: {
        connection_date() {
            return new DayJs(this.note.leads.connection_date).format(DATE_FORMAT.DB_DATE);
        },
        getTitle() {
            return `${this.note.title} [${this.note.user_role}]` || '';
        },
        getInitialPaymentAmount() {
            return this.note.leads.initial_payment_amount + this.note.leads.modem_price ?? 0;
        }
    }
}
</script>

<style scoped>
.note-data {
    font-weight: normal;
    font-size: 0.8750em !important;
    line-height: 1em !important;
    letter-spacing: .02em !important;
    text-align: left !important;
}

.title {
    margin-top: 8px !important;
    font-size: 0.8750em !important;
    letter-spacing: .02em !important;
    line-height: 1em !important;
    font-weight: 500 !important;

}
</style>
