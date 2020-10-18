<template>
    <v-container>
        <PageHeader :breadcrumbs="getBreadcrumbs" title="Customers Insight"/>
        <CustomersTable class="mt-5"/>
    </v-container>
</template>

<script>
import CustomersTable from "@scripts/components/customer/analytics/CustomersTable";
import PageHeader from "@scripts/components/common/PageHeader";

export default {
    name: "CustomerInsightPage",
    components: {CustomersTable, PageHeader},
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Dashboard',
                    disabled: false,
                    route_name: 'customerAnalytics',
                },
                {
                    text: 'Customer Insight',
                    disabled: false,
                    route_name: 'customerInsight',
                },
            ]
        },
    }
}
</script>

<style scoped>

</style>
