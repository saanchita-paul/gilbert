<template>
  <div class="mx-3 mainContainer">
    <div>
      <p class="font-weight-bold pt-4 text-center titleFont">
        Submissions to Retailer
      </p>
      <div class="d-flex justify-space-around ">
          <div>
              <div
                class="font-weight-bold text-center py-0 mb-n3 messageFont"
              >
                Conversion rate 27.5%
                <span><v-icon color="success">trending_up </v-icon> </span>
              </div>
              <div
                class="font-weight-bold text-center py-0 statisticFont"
              >
                16,032
              </div>
              <div
                class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitleFont"
              >
                Total converted Applications
              </div>
          </div>
      </div>

      <div class="dividerDesign"></div>

      <div
        class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitleFont2"
      >
        <span> <v-icon color="blue" >mdi-water</v-icon> </span> Water Retailer Segmentation
      </div>
      <div class="d-flex justify-center">
        <SalesSummaryChart v-if="is_laod" :data="data" title="Gas">
        </SalesSummaryChart>
      </div>

       <div class="dividerDesign"></div>

      <div class="d-flex justify-space-around flexWrap">
          <div class="font-weight-bold text-center py-2 flex100"> Submission Segmentation </div>
          <div>
              <div
                class="font-weight-bold text-center py-0 countFont"
              >
                247
              </div>
              <div
                class="font-weight-bold text-center py-0 mb-2 mt-n2 subtitleFont"
              >
                Manual Submission
              </div>
              
          </div>
          <div>
              <div
                class="font-weight-bold text-center py-0 statisticFont"
              >
                247
              </div>
              <div
                class="font-weight-bold text-center py-0 pb-3 mb-2 mt-n2 subtitleFont"
              >
                Automated Submission
              </div>
              
          </div>
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
      data() {
        return{
            utilityDashboardData: null,
            is_laod: false,
            data: null,
        }
    },

    mounted() {
        this.load();
    },

    methods : {
        async load() {
            this.data  = {
                labels: [
                    'Energy Australia',
                    'Sumo',
                    'Yellow'
                ],
                toolTips: [
                    [
                        {
                            key: 100, value: 'Ea',
                        },
                        {
                            key: 100, value: 'Ea',
                        }
                    ],
                    [
                        {
                            key: 100, value: 'Ea',
                        },
                        {
                            key: 100, value: 'Ea',
                        }
                    ],
                    [
                        {
                            key: 100, value: 'Ea',
                        },
                        {
                            key: 100, value: 'Ea',
                        }
                    ],
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [300, 50, 100],
                    backgroundColor: [
                        'rgb(0, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)'
                    ],
                }]
            };
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