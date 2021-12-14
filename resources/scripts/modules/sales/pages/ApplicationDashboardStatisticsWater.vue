<template>
  <div style="width: 90%; background: white;" class="mx-3">
    <div>
      <p class="font-weight-bold pt-4 text-center" style="font-size: 20px">
        Submissions to Retailer
      </p>
      <div class="d-flex justify-space-around ">
          <div>
              <div
                class="font-weight-bold text-center py-0 mb-n3"
                style="font-size: 12px; color: #7e8a8f"
              >
                Conversion rate 27.5%
                <span><v-icon color="success">trending_up </v-icon> </span>
              </div>
              <div
                class="font-weight-bold text-center py-0"
                style="font-size: 40px; color: #542e89"
              >
                16,032
              </div>
              <div
                class="font-weight-bold text-center py-0 mb-2 mt-n2"
                style="font-size: 14px"
              >
                Total converted Applications
              </div>
          </div>
      </div>

      <div
        style="
          background-color: #7e8a8f;
          width: 40px;
          height: 1px;
          margin: auto;
          margin-bottom: 20px;
          margin-top: 20px;
        "
      ></div>

      <div
        class="font-weight-bold text-center py-0 mb-2 mt-n2"
        style="font-size: 18px"
      >
        <span> <v-icon color="blue" >mdi-water</v-icon> </span> Water Retailer Segmentation
      </div>
      <div class="d-flex justify-center">
        <SalesSummaryChart v-if="is_laod" :data="data" title="Gas">
        </SalesSummaryChart>
      </div>

      <div
        style="
          background-color: #7e8a8f;
          width: 40px;
          height: 1px;
          margin: auto;
          margin-bottom: 20px;
          margin-top: 20px;
        "
      ></div>

      <div class="d-flex justify-space-around " style="flex-wrap: wrap">
          <div style="flex-basis: 100%;"> Submission Segmentation </div>
          <div>
              <div
                class="font-weight-bold text-center py-0"
                style="font-size: 40px; color: #542e89"
              >
                247
              </div>
              <div
                class="font-weight-bold text-center py-0 mb-2 mt-n2"
                style="font-size: 14px"
              >
                Manual Submission
              </div>
              
          </div>
          <div>
              <div
                class="font-weight-bold text-center py-0"
                style="font-size: 40px; color: #542e89"
              >
                247
              </div>
              <div
                class="font-weight-bold text-center py-0 mb-2 mt-n2"
                style="font-size: 14px"
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
                        'rgb(255, 99, 132)',
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
</style>