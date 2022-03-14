<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12" class="py-0s">
                <SalesFilter @updateDate="updateDate"/>
            </v-col>
            <div style="width: 100%;" class="mb-4" v-if="is_laod">
<!--                <SalesSummary type="energy"  :summaryData="chartData" :dateRange='dateRange'/>-->
                <EnergyApplicationSummary  :summaryData="chartData" :dateRange='dateRange'/>
            </div>
            <v-card class="summary-container" v-if="is_laod">
                <p class="summary-title-text">Summary of Energy Utilities</p>

                <OpsDateTypeToggler
                    class="toggle-block"
                    :type="getDateTypeString()"
                    @changeType="changeDateType"
                >
                </OpsDateTypeToggler>

                <div class="summary-inner-container">
                    <div style="flex-basis: 33%;">
                        <ApplicationDashboardStatisticsEnergy title="Utility Submissions" type='submission' :chart-data="chartData.submitted"/>
                    </div>
                    <div style="flex-basis: 33%;">
                        <ApplicationDashboardStatisticsEnergy title="Connected" type='conversion' :chart-data="chartData.connected"/>
                    </div>
                    <div style="flex-basis: 33%;">
                        <ApplicationDashboardStatisticsEnergy title="Rejected" type='rejected' :chart-data="chartData.rejected"/>
                    </div>
                </div> 
            </v-card>
        </v-row>
    </v-container>
</template>

<script>
import SalesFilter from "@scripts/modules/sales/components/SalesFilter";
import SalesSummary from "@scripts/modules/sales/components/SalesSummary";
import EnergyApplicationSummary from "@scripts/modules/sales/components/EnergyApplicationSummary";
import SalesDashboardService from "@scripts/modules/sales/services/SalesDashboardService";
import SalesSummaryChart from "@scripts/modules/sales/components/SalesSummaryChart";
import ApplicationDashboardStatisticsEnergy from "@scripts/modules/sales/pages/ApplicationDashboardStatisticsEnergy";
import OpsDateTypeToggler from "@scripts/modules/sales/components/OpsDateTypeToggler";

export default {
    name: "SalesEnergyPage",
    components: {
        SalesSummaryChart,
        SalesFilter,
        SalesSummary,
        EnergyApplicationSummary,
        ApplicationDashboardStatisticsEnergy,
        OpsDateTypeToggler
    },
    data() {
        return {
            utilityDashboardData: null,
            is_laod: false,
            data: null,
            chartData: null,
            dateRange: null,
            dateType: this.getDateType(),
        }
    },
    methods: {
        async load(dateRange, dateType) {
            this.chartData = await SalesDashboardService.loadDashboardEnergyData(dateRange, dateType);
            this.is_laod = true;
        },
        updateDate(dateRange) {
            this.dateRange = dateRange;
            this.load(dateRange, this.dateType);
        },
        getDateTypeString() {
            return this.$route.query?.type ?
                this.mapDateType() : 'BasedOnSubmittedDate';
        },
        getDateType() {
            return this.$route.query?.type ? this.$route.query.type : 'submitted_date';
        },
        mapDateType() {
            return this.$route.query.type === 'created_date' ? 'BasedOnCreatedDate' : 'BasedOnSubmittedDate';
        },
        changeDateType(type) {
            this.$router.push({
                name: 'sales.energy',
                query: Object.assign({}, this.$route.query, { type: type })
            });
            this.dateType = type;
            this.load(this.dateRange, type);
        }
    },
}
</script>

<style scoped lang="scss">
.summary-container{
    display: flex;
    justify-content: space-around;
    width: 100%;
    flex-wrap: wrap;
    padding: 24px;
}
.summary-inner-container{
    display: flex;
    justify-content: space-around;
    width: 100%;
    flex-wrap: wrap;
}
.title-style{
    font-family: Roboto;
    font-size: 32px;
    font-style: normal;
    font-weight: 700;
    line-height: 42px;
    letter-spacing: 0em;
    text-align: left;
    margin-bottom: 0px;

}
.summary-title-text{
    flex-basis: 100%;
    @extend .title-style; 
}
.toggle-block{
    flex-basis: 100%;
    margin-bottom: 20px;
}
</style>

