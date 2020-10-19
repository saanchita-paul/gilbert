<template>
    <div>
        <PageHeader :breadcrumbs="getBreadcrumbs" title="Customer Dashboard">
            <date-range-picker v-model="dateRange"/>
        </PageHeader>
        <div>
            <v-row class="pt-0">
                <v-col md="12">
                    <v-row class="customer-insight">
                        <v-col  md="6" sm="12" class="box">
                            <CustomerInfos></CustomerInfos>
                        </v-col>
                        <v-col md="6" sm="12" class="box">
                            <ConversationInfos/>
                        </v-col>
<!--                        <v-col lg="4" md="6" sm="12" class="box">-->
<!--                            <InfoCard-->
<!--                                icon="mdi-email-receive"-->
<!--                                title="Message Received"-->
<!--                                :value="analytic.messages_received"-->
<!--                            ></InfoCard>-->
<!--                        </v-col>-->
                    </v-row>
                </v-col>

            </v-row>
        </div>
    </div>

</template>

<script>
import InfoCard from "@scripts/components/customer/analytics/InfoCard";
import GenderChart from "@scripts/components/customer/analytics/GenderChart";
import DateRangePicker from "@scripts/components/customer/analytics/DateRangePicker"
import PageHeader from "@scripts/components/common/PageHeader"
import CustomerAnalytics from "@scripts/models/CustomerAnalytics";
import CustomerService from "@scripts/services/CustomerService";
import DateRange from "@scripts/models/DateRange";
import merge from "lodash-es/merge";
import CustomerInfos from "@scripts/components/customer/analytics/CustomerInfos";
import ConversationInfos from "@scripts/components/customer/analytics/ConversationInfos";

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
            analytic: new CustomerAnalytics(),
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
    async mounted() {
        const analytics = await CustomerService.getCustomerAnalytics(this.dateRange)
        merge(this.analytic, analytics)
    }
}
</script>

<style scoped>

</style>
