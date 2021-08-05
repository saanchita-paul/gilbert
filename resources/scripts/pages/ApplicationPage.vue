<template>
    <v-container>
        <v-row>
            <v-col cols="8">
                <v-card class="pa-4">
                    <p>Your Metrics</p>
                    <h3 class="page-title">Total Applications: {{total_leads}}</h3>
                    <AgentLeadMetrics v-if="leadTypesFlag" :activeLeadType="activeLeadType" :leads="leadTypes" @updateTotal="updateTotal" @goToActiveApp="goToActiveApp"></AgentLeadMetrics>
                </v-card>
                <ApplicantTable :applications="leads"></ApplicantTable>
            </v-col>
            <v-col cols="4">
                <ApplicationDetails :lead="leadDetails"></ApplicationDetails>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import AgentLeadMetrics from "@scripts/components/crm/leadmanagement/ApplicationsMetrics";
import ApplicantTable from "@scripts/components/crm/leadmanagement/ApplicantTable";
import ApplicationDetails from "@scripts/components/crm/leadmanagement/ApplicationDetails";
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";
import ApplicationDetailScreen from "@scripts/components/crm/leadmanagement/ApplicationDetailScreen";
export default {
    name: "ApplicationPage",
    components: {
        ApplicationDetailScreen,
        AgentLeadMetrics,
        ApplicantTable,
        ApplicationDetails
    },

    data() {
        return {
            leadTypes:[],
            activeLeadType: 'My Applications',
            leads:[],
            leadFlag: false,
            leadTypesFlag: false,
            activeLead: null,
            total_leads: 0,
            leadDetails: null,
        }
    },

    methods: {
        async loadMetricTypes() {
            this.leadTypes = await LeadApplicationService.loadUserLeadMetrics();
            if(this.$route.query?.type) {
                this.activeLeadType = this.$route.query?.type
            }
            this.leadTypesFlag = true;
        },

        async loadLeads () {
            this.leads = await LeadApplicationService.loadUserLeads(this.activeLeadType);
            this.leadFlag = true;
        },

        async loadLead() {
            this.activeLead = this.$route.query?.lead;
            this.leadDetails = await LeadApplicationService.loadUserLead(this.activeLead);
        },

        updateTotal(total) {

            this.total_leads = total

        },
    },

    mounted() {
        this.loadMetricTypes();
        this.loadLeads();
        this.loadLead();
    },
    watch: {
        '$route': {
            handler() {
                this.activeLeadType = this.$route.query?.type;
                this.loadLeads(this.activeLeadType);
                this.loadLead(this.activeLead);


            }
        },
    },

}
</script>

<style scoped>
</style>

