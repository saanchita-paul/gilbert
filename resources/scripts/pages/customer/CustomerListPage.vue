<template>
    <div>
        <div class="customer-list-header">
                <v-list-item>
                <v-icon medium class="customer-list-back-button"> mdi-arrow-left </v-icon>
                <v-text-field
                    filled
                    label="Search leads"
                    prepend-inner-icon="mdi-magnify"
                    class="customer-list-search-field"
                ></v-text-field>
                <v-icon medium class="customer-list-filter-button"> mdi-filter-variant </v-icon>
                </v-list-item>
        </div>
        <v-container>
            <CustomersNewTable class="mt-2"/>
        </v-container>
    </div>
</template>

<script>
import CustomersNewTable from "@scripts/components/customer/CustomersNewTable";

export default {
    name: "CustomerInsightPage",
    components: {CustomersNewTable},
}
</script>

<style scoped>
.customer-list-header {
    height: 70px !important;
    background-color: white;
}

</style>
