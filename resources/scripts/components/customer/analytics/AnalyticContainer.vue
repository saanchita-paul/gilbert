<template>
    <div>

    </div>

</template>

<script>
import InfoCard from "@scripts/components/customer/analytics/InfoCard";
import GenderChart from "@scripts/components/customer/analytics/GenderChart";
import DateRangePicker from "@scripts/components/customer/analytics/DateRangePicker"
import PageHeader from "@scripts/components/common/PageHeader"
import DateRange from "@scripts/models/DateRange";
import merge from "lodash-es/merge";
import CustomerInfos from "@scripts/components/customer/analytics/CustomerInfos";
import ConversationInfos from "@scripts/components/customer/analytics/ConversationInfos";
import CustomerSummary from "@scripts/models/CustomerSummary";

export default {
    name: "AnalyticContainer",
    components: {
        InfoCard,
        GenderChart,
        DateRangePicker,
        PageHeader,
        CustomerInfos,
        ConversationInfos,
    },
    data() {
        return {
            customerSummary: new CustomerSummary(),
            dateRange: new  DateRange()
        }
    },
    computed: {
        getBreadcrumbs() {
            return [
                {
                    text: 'Customer',
                    disabled: false,
                    route_name: 'dashboard',
                },
                // {
                //     text: 'Customer Dashboard',
                //     disabled: false,
                //     route_name: 'breadcrumbs_link_1',
                // },
            ]
        }
    },
}
</script>

<style scoped>

</style>
