<template>
    <v-row>
      <v-col cols="4" class="pb-0">
        <p class="mb-0">Leads Data</p>
      </v-col>
      <v-col cols="8" class="pb-0">
        <p class="mb-0 ">Successful Connections</p>
      </v-col>

      <div class="agent-leade-section">
          <div class="agent-leade">
              <h3>Applications</h3>
              <div class="leade-icon">
                  <v-icon color="yellow">mdi-flash</v-icon>
                  <span>{{appMetrics.applications}}</span>
              </div>
              <p class="leade-text">Total submitted</p>
          </div>

          <div class="agent-leade">
              <h3>Non-payable</h3>
              <div class="leade-icon">
                  <v-icon color="red">mdi-link</v-icon>
                  <span>{{appMetrics.nonpayable}}</span>
              </div>
              <p class="leade-text">Non Connected</p>
          </div>

          <div class="agent-leade">
            <h3>Power</h3>
            <div class="leade-icon">
                <v-icon color="yellow">mdi-flash</v-icon>
                <span>{{appMetrics.power}}</span>
            </div>
            <p class="leade-text">Successfully connected</p>
          </div>

          <div class="agent-leade">
            <h3>Gas</h3>
            <div class="leade-icon">
                <v-icon color="red">mdi-fire</v-icon>
                <span>{{appMetrics.gas}}</span>
            </div>
            <p class="leade-text">Successfully connected</p>
          </div>

          <div class="agent-leade">
            <h3>Internet</h3>
            <div class="leade-icon">
                <v-icon color="grey lighten-1">mdi-wifi</v-icon>
                <span>{{appMetrics.internet}}</span>
            </div>
            <p class="leade-text">Successfully connected</p>
          </div>

          <div class="agent-leade">
              <h3>Water</h3>
              <div class="leade-icon">
                  <v-icon color="grey lighten-1">mdi-water</v-icon>
                  <span>{{appMetrics.water}}</span>
              </div>
              <p class="leade-text">Successfully connected</p>
          </div>
      </div>

    </v-row>
</template>

<script>
import LeadApplicationService from "@scripts/services/crm/LeadApplicationService";

export default {
  name: "AgentLeadMetrics",
    data() {

      return {
          appMetrics: null,
      }
    },
    methods: {

      async loadMetrics() {
          const allMetric = await LeadApplicationService.loadMetrics();
          this.appMetrics = allMetric.data;

          console.log('metricsadfasf ',this.appMetrics);
      }

    },
    mounted() {
      this.loadMetrics();
    }
};
</script>
