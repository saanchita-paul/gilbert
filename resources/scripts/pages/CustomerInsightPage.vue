<template>
    <v-container>
        <PageHeader title="Customers Insight"/>
        <CustomersTable class="mt-5"/>
    </v-container>
</template>

<script>
import CustomersTable from "@scripts/components/customer/analytics/CustomersTable";
import PageHeader from "@scripts/components/common/PageHeader";

export default {
    name: "CustomerInsightPage",
    components: {CustomersTable, PageHeader}
}
</script>

<style scoped>

</style>
