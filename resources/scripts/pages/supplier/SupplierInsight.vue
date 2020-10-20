<template>
    <v-container>
        <page-header :breadcrumbs="getBreadcrumbs" title="Supplier Insight"></page-header>
    </v-container>
</template>

<script>
import PageHeader from "@scripts/components/common/PageHeader";

export default {
    name: "SupplierMessenger",
    components: { PageHeader },
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Supplier',
                    disabled: false,
                    route_name: 'suppliers.insight',
                }
            ]
        },
    }

}
</script>

<style scoped>

</style>
