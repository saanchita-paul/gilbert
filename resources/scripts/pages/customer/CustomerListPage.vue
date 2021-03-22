<template>
    <v-container>
        <CustomersNewTable class="mt-2"/>
    </v-container>
</template>

<script>
import CustomersNewTable from "@scripts/components/customer/CustomersNewTable";

export default {
    name: "CustomerInsightPage",
    components: {CustomersNewTable},
}
</script>

<style scoped>

</style>
