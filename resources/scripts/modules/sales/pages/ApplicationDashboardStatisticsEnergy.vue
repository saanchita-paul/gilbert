<template>
    <div class="mx-3 mainContainer">
        <div>
            <p class="font-weight-bold pt-4 text-center titleFont">
                {{title}}
            </p>
            <div class="d-flex justify-space-around ">
                <template v-if="type === 'submission'">
                    <div>
                        <div class="mb-3"></div>
                        <div
                            class="font-weight-bold text-center py-0 statisticFont"
                        >
                            {{chartData.total}}
                        </div>
                        <div
                            class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitleFont"
                        >
                            Total submitted to retailer
                        </div>
                    </div>
                </template>

                <template v-if="type === 'conversion'">
                    <div>
                        <div
                            class="font-weight-bold text-center py-0 mb-n3 messageFont"
                        >
                            Conversion rate {{chartData.conversiton_rate}}%
                            <span><v-icon color="success">trending_up </v-icon> </span>
                        </div>
                        <div
                            class="font-weight-bold text-center py-0 countFont"
                        >
                            {{chartData.total}}
                        </div>
                        <div
                            class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitleFont"
                        >
                            Total connected applications
                        </div>
                    </div>
                </template>


                <template v-if="type === 'rejected'">
                    <div>
                        <div class="mb-3"></div>
                        <div
                            class="font-weight-bold text-center py-0 countFont"
                        >
                            {{chartData.total}}
                        </div>
                        <div
                            class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitleFont"
                        >
                            Total rejected applications
                        </div>
                    </div>
                    <div>
                        <div class="mb-3"></div>
                        <div
                            class="font-weight-bold text-center py-0 countFont"
                        >
                            {{chartData.declined}}
                        </div>
                        <div
                            class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitleFont"
                        >
                            Declined credits
                        </div>
                    </div>
                </template>
            </div>


            <div class="dividerDesign"></div>

            <div
                class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitleFont2"
            >
                Retailer Segmentation
            </div>
            <div class="d-flex justify-center pb-3">
                <SalesSummaryChart v-if="is_laod" icon-color="primary" :data="chartData.gasChartData" title="Gas">
                </SalesSummaryChart>
                <SalesSummaryChart v-if="is_laod" :data="chartData.powerChartData" title="Power">
                </SalesSummaryChart>
            </div>
        </div>
    </div>
</template>

<script>
import SalesSummaryChart from "@scripts/modules/sales/components/SalesSummaryChart";

export default {
    name: "ApplicationDashboardStatisticsEnergy",
    components: {
        SalesSummaryChart,
    },
    props: {
        chartData: {
            type: Object,
            required: true
        },
        title: {
            required: true
        },
        type: {
            required: true
        }
    },
    data() {
        return {
            utilityDashboardData: null,
            is_laod: false,
            data: null,
        }
    },

    mounted() {
        this.load();
    },

    methods: {
        async load() {
            this.is_laod = true;
        }
    }
};
</script>

<style lang="scss" scoped>
.mainContainer{
  width: 90%;
  background: white;
}

.titleFont{
  font-size: 20px;
}

.messageFont{
  font-size: 12px;
  color: #7e8a8f
}

.statisticFont{
  font-size: 40px;
  color: #542e89;
}

.subtitleFont{
  font-size: 14px;
}

.dividerDesign{
  background-color: #7e8a8f;
  width: 40px;
  height: 1px;
  margin: auto;
  margin-bottom: 20px;
  margin-top: 20px;
}

.subtitleFont2{
  font-size: 18px;
}

.flexWrap{
  flex-wrap: wrap;
}

.flex100{
  flex-basis: 100%;
}

.countFont{
  font-size: 40px; 
  color: #542e89;
}
</style>
