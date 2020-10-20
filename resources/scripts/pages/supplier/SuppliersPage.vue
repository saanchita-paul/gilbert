<template>
    <v-container>
        <page-header :breadcrumbs="getBreadcrumbs" title="Suppliers"></page-header>
    </v-container>
</template>

<script>
import PageHeader from "@scripts/components/common/PageHeader";

export default {
    name: "SuppliersPage",
    components: { PageHeader },
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Suppliers',
                    disabled: false,
                    route_name: 'insight.suppliers',
                },
                {
                    text: 'List',
                    disabled: false,
                    route_name: 'suppliers.list',
                },
            ]
        },
    }

}
</script>

<style scoped>

</style>
