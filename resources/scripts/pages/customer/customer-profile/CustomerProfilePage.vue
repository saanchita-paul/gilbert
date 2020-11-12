<template>
    <v-container>
        <page-header :breadcrumbs="getBreadcrumbs" title="Customer Details">
            <date-range-picker v-model="dateRange" />
        </page-header>
    </v-container>
</template>

<script>
import PageHeader from "@scripts/components/common/PageHeader";
import DateRange from "@scripts/models/DateRange";
import DateRangePicker from "@scripts/components/customer/analytics/DateRangePicker";

export default {
    name: "CustomerProfilePage",
    components: { PageHeader, DateRangePicker },
    props: ['customerId'],
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Customer',
                    disabled: false,
                    route_name: 'dashboard',
                },
                {
                    text: 'List',
                    disabled: false,
                    route_name: 'customers.list',
                },                {
                    text: this.customerId,
                    disabled: false,
                    route_name: 'customers.profile',
                },
            ]
        }
    },
    data() {
        return {
            dateRange: new DateRange()
        }
    }
}
</script>

<style scoped>

</style>
