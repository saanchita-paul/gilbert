<template>
    <v-container>
        <PageHeader :breadcrumbs="getBreadcrumbs" title="Customers"/>
        <CustomersTable class="mt-2"/>
    </v-container>
</template>

<script>
import CustomersTable from "@scripts/components/customer/list/CustomersTable";
import CustomerInfos from "@scripts/components/customer/analytics/CustomerInfos";
import PageHeader from "@scripts/components/common/PageHeader";

export default {
    name: "CustomerInsightPage",
    components: {CustomersTable, PageHeader, CustomerInfos},
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Users',
                    disabled: false,
                    route_name: 'dashboard',
                },
                {
                    text: 'Customers',
                    disabled: false,
                    route_name: 'users.customers',
                },
            ]
        },
    }
}
</script>

<style scoped>

</style>
