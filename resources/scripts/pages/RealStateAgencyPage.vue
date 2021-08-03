<template>
    <v-container>
        <v-card class="pa-4">
            <h2>All Application Metrics</h2>
            <LeadMetrics></LeadMetrics>
        </v-card>
        <router-view></router-view>
    </v-container>
</template>

<script>
import LeadMetrics from "@scripts/components/crm/LeadMetrics";
import CrmAgencyDataTable from "@scripts/components/crm/agency/CrmAgencyDataTable";
import CrmOfficeDataTable from "@scripts/components/crm/office/CrmOfficeDataTable";
import CrmUserDatatable from "@scripts/components/crm/user/CrmUserDatatable";
export default {
    name: "RealStateAgencyPage",
    components: {
        CrmUserDatatable,
        CrmOfficeDataTable,
        CrmAgencyDataTable,
        LeadMetrics,
    },


}
</script>

<style scoped>

</style>
