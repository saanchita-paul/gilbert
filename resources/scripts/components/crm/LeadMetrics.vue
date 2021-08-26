<template>
    <v-row>
      <div class="section-leademetriics">
        <div class="leade-badge" v-for=" appMetric in appMetrics" :key="appMetric.id">
            <h3>{{appMetric.title}}</h3>
            <div class="leade-icon">
                <v-icon color="grey lighten-1">{{appMetric.icon}}</v-icon>
                <span>{{appMetric.lead_count}}</span>
            </div>
            <p class="leade-text">{{appMetric.status}}</p>
        </div>
      </div>
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
      async loadMetrics() {
          const allMetric = await LeadApplicationService.loadMetrics();
          this.appMetrics = allMetric.mapData;

      }
    },
    mounted() {
      this.loadMetrics();
    }
};
</script>

<style scoped>
</style>
