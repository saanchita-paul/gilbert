<template>
    <v-card class="elevation-2 timeline-card" color="gray" :class="{active:false}">
        <p class="title">{{note.title}}</p>
        <p>{{note.created_at}}</p>
        <p>{{note.text}}</p>
        <v-btn  outlined small
               color="indigo" class="mb-4" @click="exportNote(note.id)">Export Note</v-btn>

        <p class="title">Utility Type:<span class="note-data">({{note.leads.utility_type}})</span></p>
        <p class="title">Applicant Name: <span class="note-data">{{note.leads.application_name}}</span></p>
        <p class="title">Connection Address: <span class="note-data">{{note.leads.address_text}}</span></p>
        <p class="title">{{gas_connection_date ? 'Elec ' : ''}}Connection Date: <span class="note-data">{{connection_date}}</span></p>
        <p v-if="gas_connection_date" class="title">Gas Connection Date: <span class="note-data">{{gas_connection_date}}</span></p>
        <p class="title">Consent to Pay: <span class="note-data">{{note.leads.is_contacted}}</span></p>
        <p class="title mt-3">Lead Source: <span class="note-data">{{note.leads.source}}</span></p>
        <p class="title">Agency: <span class="note-date">{{note.leads.agency}}</span></p>
        <p class="title">Agent Name: <span class="note-data">{{note.leads.agent_name}}</span></p>
        <p class="title mt-3">NMI:  <span class="note-data">{{note.leads.nmi}}</span></p>
        <p class="title">MIRN: <span class="note-data">{{note.leads.mirn}}</span></p>
        <p class="title mt-3">Supplier Name: <span class="note-data">{{note.leads.supplier}}</span></p>
        <p class="title mt-3">Plan Name: <span class="note-data">{{plan}}</span></p>
        <p class="title mt-3"> Go Neutral: <span class="note-data">{{ note.leads.ea_go_neutral === 1 ? "Yes" : (note.leads.ea_go_neutral === 0 ? "No" : 'N/A') }}</span></p>

        <v-btn  outlined small
                color="indigo" class="my-4" @click="togglePlanDetails">show Plan</v-btn>


        <v-dialog
            v-model="dialog"
            max-width="450px"
        >
        <v-card>
            <OriginPlanDetails
                @toggleDialog="togglePlanDetails"
                :serviceType="serviceType"
                :selectedPlan="note.leads.plan_type"
                :leadSummary="note.plans"
            />
        </v-card>
        </v-dialog>


    </v-card>
</template>

<script>
import dayJs from "dayjs";
import DATE_FORMAT from "@scripts/data/constants/DATE_FORMAT";
import OriginPlanDetails from "@scripts/components/crm/leadmanagement/OriginPlanDetails";

export default {
name: "SubmittedOriginNote",
    components: {OriginPlanDetails},
    props: ['note'],
    data() {
        return {
            dialog: false,
            planDetails: null,
            serviceType: null,
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
            // let elecPlan = this.note.plans.plans.electricity? this.note.plans.plans.electricity.vdo.marketing_offer_name : null;
            // let gasPlan = this.note.plans.plans.gas? this.note.plans.plans.gas.bpid_links[0].offer_name : null;
            let elecPlan = this.note.plans.plans.electricity ?? null;
            let gasPlan = this.note.plans.plans.gas ?? null;

            if (elecPlan && gasPlan){
                elecPlan = elecPlan.plan_name_text ?? 'Origin Home Assist';
                gasPlan = gasPlan.plan_name_text ?? 'Origin Advantage Variable';
                return elecPlan + '(elec)' + '|' + gasPlan + '(gas)';
            }
            if (elecPlan && !gasPlan){
                elecPlan = elecPlan.plan_name_text ?? 'Origin Home Assist';
                return elecPlan + '(elec)';
            }
            if (!elecPlan && gasPlan)
            {
                gasPlan = gasPlan.plan_name_text ?? 'Origin Advantage Variable';
                return gasPlan + '(gas)';
            }

            return this.note.leads.plan_type;
        },

        services() {
            return this.note.leads.services;
        },
        connection_date(){
          return dayJs(this.note.leads.moving_date, DATE_FORMAT.DATE_DASH).format(DATE_FORMAT.DB_DATE);
        },
        gas_connection_date(){
            if (this.note.leads.gas_moving_date)
                return dayJs(this.note.leads.gas_moving_date, DATE_FORMAT.DATE_DASH).format(DATE_FORMAT.DB_DATE);
            return '';
        },
    },
    methods: {
        togglePlanDetails() {
            this.dialog = !this.dialog;
        },
        async mapServiceType() {
            let services = this.note.leads.services.toLowerCase();
            if(services.indexOf('elec') !== -1 && services.indexOf('gas') !== -1){
                this.serviceType = 'energy';
            }
            else if(services.indexOf('elec') !== -1){
                this.serviceType = 'power';
            }
            else {
                this.serviceType = 'gas';
            }
        },
        exportNote(id) {
            window.open(
                '/api/plans-details/' + id + '/export',
                '_blank'
            );

        }
    },

    async mounted() {
        await this.mapServiceType();
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
