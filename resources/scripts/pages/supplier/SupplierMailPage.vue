<template>
    <v-container>
        <page-header :breadcrumbs="getBreadcrumbs" title="Supplier's Mails"></page-header>
    </v-container>
</template>

<script>
import PageHeader from "@scripts/components/common/PageHeader";

export default {
    name: "SupplierMailPage",
    components: { PageHeader },
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Supplier',
                    disabled: false,
                    route_name: 'suppliers.insight',
                },
                {
                    text: 'Mails',
                    disabled: false,
                    route_name: 'suppliers.mails',
                },
            ]
        },
    }

}
</script>

<style scoped>

</style>
