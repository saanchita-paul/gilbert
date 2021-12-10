<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12">
                <SalesFilter/>
            </v-col>
            <v-col cols="12">
                <SalesSummary/>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import SalesFilter from "@scripts/modules/sales/components/SalesFilter";
import SalesSummary from "@scripts/modules/sales/components/SalesSummary";

export default {
    name: "ApplicationsDashboardPage",
    components: {
        SalesFilter,
        SalesSummary
    },
    data() {
       
    },
}
</script>

