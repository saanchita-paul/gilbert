<template>
    <v-card class="elevation-2 timeline-card" color="gray" :class="{active:false}">
        <p class="title">{{note.title}}</p>
        <p>{{note.created_at}}</p>
        <p>{{note.text}}</p>
        <v-btn  outlined small
               color="indigo" class="mb-4">Export Note</v-btn>

        <p class="title">Utility Type:({{'ELE &/ Gas'}})</p>
        <p class="title">Applicant Name: <span class="note-date">{{'Mr Ervin '}}</span></p>
        <p class="title">Connection Address: <span class="note-date">{{'Mr Ervin '}}</span></p>
        <p class="title">Connection Date: <span class="note-date">{{'Mr Ervin '}}</span></p>
        <p class="title">Consent to Pay(Yes/No)*</p>
        <p class="title mt-3">Lead Source: <span class="note-date">{{'Mr Ervin '}}</span></p>
        <p class="title">Agency: <span class="note-date">{{'Mr Ervin '}}</span></p>
        <p class="title">Agent Name: <span class="note-date">{{'Mr Ervin '}}</span></p>
        <p class="title mt-3">NMI*:  <span class="note-date">{{'Mr Ervin '}}</span></p>
        <p class="title">MERN*: <span class="note-date">{{'Mr Ervin '}}</span></p>
        <p class="title mt-3">Supplier Name*: <span class="note-date">{{'Mr Ervin '}}</span></p>




        <EnergyPlanContainer
            :planDetails="note.plans"
            :postcode="postcode"
            :state="state"
            :plan="plan"
            :services="services">
        </EnergyPlanContainer>


    </v-card>
</template>

<script>
import EnergyPlanContainer from "@scripts/components/ea/EnergyPlanContainer";
export default {
name: "SubmittedNote",
    components: {EnergyPlanContainer},
    props: ['note'],
    mounted() {
        console.log('note11',  this.note.plans);
    },

    computed: {
        postcode() {
            return '3044';
        },

        state () {
            return 'Victoria ';
        },

        plan() {
            return 'no_frills '
        },

        services() {
            return ['power', 'gas'];
        }

    }
}
</script>

<style scoped>
.note-date{
    font-weight: normal;
    font-size: 14px !important;
}

.title {
    font-weight: 500 !important;
}
</style>
