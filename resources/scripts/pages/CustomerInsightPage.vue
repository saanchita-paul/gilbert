<template>
    <v-container>
        <v-card>
            <v-card-title class="app-title">
                Customers Insight
            </v-card-title>
            <CustomersTable/>
        </v-card>
    </v-container>
</template>

<script>
import CustomersTable from "@scripts/components/customer/analytics/CustomersTable";

export default {
    name: "CustomerInsightPage",
    components: {CustomersTable}
}
</script>

<style scoped>

</style>
