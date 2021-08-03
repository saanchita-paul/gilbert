<template>
    <v-row>
      <v-col cols="2" v-for=" appMetric in appMetrics" :key="appMetric.id">
        <div class="leade-badge">
            <h3>{{appMetric.title}}</h3>
            <div class="leade-icon">
                <v-icon color="yellow">{{appMetric.icon}}</v-icon>
                <span>{{appMetric.lead_count}}</span>
            </div>
            <p class="leade-text">{{appMetric.status}}</p>
        </div>
      </v-col>

<!--      <v-col cols="2">-->
<!--        <div class="leade-badge">-->
<!--            <h3>Non-payable</h3>-->
<!--            <div class="leade-icon">-->
<!--                <v-icon color="red">mdi-link</v-icon>-->
<!--                <span>2</span>-->
<!--            </div>-->
<!--            <p class="leade-text">Non Connected</p>-->
<!--        </div>-->
<!--      </v-col>-->

<!--      <v-col cols="2">-->
<!--        <div class="leade-badge">-->
<!--            <h3>Power</h3>-->
<!--            <div class="leade-icon">-->
<!--                <v-icon color="yellow">mdi-flash</v-icon>-->
<!--                <span>43</span>-->
<!--            </div>-->
<!--            <p class="leade-text">Successfully connected</p>-->
<!--        </div>-->
<!--      </v-col>-->

<!--      <v-col cols="2">-->
<!--        <div class="leade-badge">-->
<!--            <h3>Gas</h3>-->
<!--            <div class="leade-icon">-->
<!--                <v-icon color="red">mdi-fire</v-icon>-->
<!--                <span>104</span>-->
<!--            </div>-->
<!--            <p class="leade-text">Successfully connected</p>-->
<!--        </div>-->
<!--      </v-col>-->

<!--      <v-col cols="2">-->
<!--        <div class="leade-badge">-->
<!--            <h3>Internet</h3>-->
<!--            <div class="leade-icon">-->
<!--                <v-icon color="grey lighten-1">mdi-wifi</v-icon>-->
<!--                <span>104</span>-->
<!--            </div>-->
<!--            <p class="leade-text">Successfully connected</p>-->
<!--        </div>-->
<!--      </v-col>-->

<!--      <v-col cols="2">-->
<!--        <div class="leade-badge">-->
<!--            <h3>Water</h3>-->
<!--            <div class="leade-icon">-->
<!--                <v-icon color="grey lighten-1">mdi-water</v-icon>-->
<!--                <span>104</span>-->
<!--            </div>-->
<!--            <p class="leade-text">Successfully connected</p>-->
<!--        </div>-->
<!--      </v-col>-->
    </v-row>
</template>

<script>
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";

export default {
  name: "LeadMetrics",
    data(){
      return {
          appMetrics: [],
      }
    },
    methods: {
      loadMetrics() {
          console.log('dddd');
          this.appMetrics = LeadApplicationService.loadMetrics();
          console.log(this.appMetrics);
      }
    },
    mounted() {
      this.loadMetrics();
    }
};
</script>

<style scoped>
</style>
