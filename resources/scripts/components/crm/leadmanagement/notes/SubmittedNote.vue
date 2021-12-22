<template>
    <v-card class="elevation-2 timeline-card" color="gray" :class="{active:false}">
        <p class="title">{{note.title}}</p>
        <p>{{note.created_at}}</p>
        <p>{{note.text}}</p>
        <v-btn  outlined small
               color="indigo" class="mb-4">Export Note</v-btn>

        <p class="title">Utility Type:<span class="note-data">({{note.leads.utility_type}})</span></p>
        <p class="title">Applicant Name: <span class="note-data">{{note.leads.application_name}}</span></p>
        <p class="title">Connection Address: <span class="note-data">{{note.leads.address_text}}</span></p>
        <p class="title">Connection Date: <span class="note-data">{{connection_data}}</span></p>
        <p class="title">Consent to Pay: <span class="note-data">{{note.leads.is_contacted}}</span></p>
        <p class="title mt-3">Lead Source: <span class="note-data">{{note.leads.source}}</span></p>
        <p class="title">Agency: <span class="note-date">{{note.leads.agency}}</span></p>
        <p class="title">Agent Name: <span class="note-data">{{note.leads.agent_name}}</span></p>
        <p class="title mt-3">NMI:  <span class="note-data">{{note.leads.nmi}}</span></p>
        <p class="title">MIRN: <span class="note-data">{{note.leads.mirn}}</span></p>
        <p class="title mt-3">Supplier Name: <span class="note-data">{{note.leads.supplier}}</span></p>

        <v-btn  outlined small
                color="indigo" class="my-4" @click="showPlan">show Plan</v-btn>


        <v-dialog
            v-model="dialog"
            width="500px"
        >
        <EnergyPlanContainer
            :planDetails="planDetails"
            :postcode="postcode"
            :state="state"
            :plan="plan"
            :services="services">
        </EnergyPlanContainer>
        </v-dialog>


    </v-card>
</template>

<script>
import EnergyPlanContainer from "@scripts/components/ea/EnergyPlanContainer";
import SubmittedNoteService from "@scripts/services/SubmittedNoteService";
import dayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
export default {
name: "SubmittedNote",
    components: {EnergyPlanContainer},
    props: ['note'],
    data() {
    return {
        dialog: false,
        planDetails: null
    }
    },

    computed: {
        postcode() {
            return this.note.leads.post_code;
        },

        state () {
            return this.note.leads.state;
        },

        plan() {
            return this.note.leads.plan_type;
        },

        services() {
            return this.note.leads.services;
        },
        connection_data()
        {
          return dayJs(this.note.leads.moving_date, DATE_FORMAT.DATE_DASH).format(DATE_FORMAT.DB_DATE);
        }

    },
    methods: {
        showPlan() {
            this.dialog = true;
        },

       async mapPlanDetail()
        {
            this.planDetails = await SubmittedNoteService.mapEnergyPlan(this.note.plans, this.note.leads.plan_type);
        }
    },

    async mounted() {
        await this.mapPlanDetail();
    }
}
</script>

<style scoped>
.note-data{
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
