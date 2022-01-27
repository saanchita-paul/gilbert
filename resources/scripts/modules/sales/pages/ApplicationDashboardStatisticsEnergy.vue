<template>
    <div class="mx-3 mainContainer">
        <div>
            <p class="font-weight-bold pt-4 text-center titleFont">
                {{title}}
            </p>
            <div class="d-flex justify-space-around ">
                <template v-if="type === 'submission'">
                    <div class="d-flex" style="flex-wrap: wrap;">
                        <div style="flex-basis: 36%;">
                            <div class="mb-3"></div>
                            <div
                                class="font-weight-bold text-center py-0 statisticFont"
                            >
                                {{chartData.total}}
                            </div>
                            <div
                                class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitleFont"
                            >
                                Successful submissions to retailer
                            </div>
                        </div>
                        <v-divider style="flex-basis: 2px;" class="vertical-divider mx-1" vertical></v-divider>
                        <div style="flex-basis: 36%;">
                            <div class="mb-3"></div>
                            <div
                                class="font-weight-bold text-center py-0 countFont"
                            >
                                {{chartData.waitingForConnection}}
                            </div>
                            <div
                                class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitleFont"
                            >
                                Waiting for connection
                            </div>
                        </div>
                        <div class="sideDesign">

                           <div class="d-flex mb-2 align-center">
                               <div class=" mb-2 mr-1"><span class="font-weight-bold">{{ chartData.manualProcessing }}</span> </div>
                               <div class="messageFont">Manual Processing</div>
                           </div> 

                           <div class="d-flex align-center">
                               <div class=" mb-2 mr-1"><span class="font-weight-bold">{{ chartData.acManualProcessing }}</span> </div>
                               <div class="messageFont">AC Manual Processing</div>
                           </div>
                           
                           <!-- <div class="messageFont "><span class="font-weight-bold">20</span> AC Manual Processing</div> -->
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


            <div class="dividerDesign" :class="type !== 'submission' ? 'dividerMargin' : '' "></div>

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
  color: #7e8a8f;
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

.center-flex-items{
    display: flex;
    flex-direction: column;
    justify-content: center;
}
.vertical-divider {
    border-width: 1px !important;
    width: 1px;
}

.sideDesign{
    flex-basis: 80px; 
    display: flex; 
    flex-direction: column; 
    width: 100%; 
    justify-content: center;
}

.dividerMargin{
    margin-top: 54px;
}
</style>
