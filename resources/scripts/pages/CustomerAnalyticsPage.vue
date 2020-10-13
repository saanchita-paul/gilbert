<template>
    <v-container>
        <v-row>
            <v-col sm="12"></v-col>
            <v-col lg="3" md="6" sm="12" class="box">
                <InfoCard icon="mdi-account-outline" title="New Users" value="37"></InfoCard>
            </v-col>
            <v-col lg="3" md="6" sm="12" class="box">
                <InfoCard icon="mdi-email-send" title="Message Sent" value="1281"></InfoCard>
            </v-col>
            <v-col lg="3" md="6" sm="12" class="box">
                <InfoCard icon="mdi-email-receive-outline" title="Message Received" value="1894"></InfoCard>
            </v-col>
            <v-col lg="3" md="6" sm="12" class="box">
                <GenderChart />
            </v-col>
        </v-row>

        <v-row>
            <v-col md="8">
                <CustomersTable />
            </v-col>
            <v-col md="4">
                <EmotionMeter />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import InfoCard from "@scripts/components/customer/InfoCard";
import GenderChart from "@scripts/components/customer/GenderChart";
import CustomersTable from "@scripts/components/customer/CustomersTable";
import EmotionMeter from "@scripts/components/customer/EmotionMeter";

export default {
    name: "CustomerAnalyticsPage",
    components: {
        InfoCard,
        GenderChart,
        CustomersTable,
        EmotionMeter
    },

}
</script>

<style scoped>
.box {
}
</style>
