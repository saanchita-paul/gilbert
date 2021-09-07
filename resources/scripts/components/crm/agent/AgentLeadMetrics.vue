<template>
    <v-row v-if="appMetrics">
      <v-col cols="4" class="pb-0">
        <p class="mb-0">Applications Data</p>
      </v-col>
      <v-col cols="8" class="pb-0">
        <p class="mb-0 pl-2">Successful Connections</p>
      </v-col>

      <div class="agent-leade-section">
          <div class="agent-leade">
              <h3>Applications</h3>
              <div class="leade-icon">
                  <v-icon :color="appMetrics.applications>0?'success':'grey lighten-1'">mdi-home</v-icon>
                  <span>{{appMetrics.applications}}</span>
              </div>
              <p class="leade-text">Total submitted</p>
          </div>

          <div class="agent-leade">
              <h3>Non-payable</h3>
              <div class="leade-icon">
                  <v-icon :color="appMetrics.nonpayable>0?'red':'grey lighten-1'">mdi-link</v-icon>
                  <span>{{appMetrics.nonpayable}}</span>
              </div>
              <p class="leade-text">Non Connected</p>
          </div>

          <div class="agent-leade ml-5">
            <h3>Power</h3>
            <div class="leade-icon">
                <v-icon :color="appMetrics.power>0?'yellow':'grey lighten-1'">mdi-flash</v-icon>
                <span>{{appMetrics.power}}</span>
            </div>
            <p class="leade-text">Successfully connected</p>
          </div>

          <div class="agent-leade">
            <h3>Gas</h3>
            <div class="leade-icon">
                <v-icon  :color="appMetrics.gas>0?'red':'grey lighten-1'">mdi-fire</v-icon>
                <span>{{appMetrics.gas}}</span>
            </div>
            <p class="leade-text">Successfully connected</p>
          </div>

          <div class="agent-leade">
            <h3>Internet</h3>
            <div class="leade-icon">
                <v-icon  :color="appMetrics.internet>0?'grey lighten-1':'grey lighten-1'">mdi-wifi</v-icon>
                <span>{{appMetrics.internet}}</span>
            </div>
            <p class="leade-text">Successfully connected</p>
          </div>

          <div class="agent-leade">
              <h3>Water</h3>
              <div class="leade-icon">
                  <v-icon  :color="appMetrics.water>0?'grey lighten-1':'grey lighten-1'">mdi-water</v-icon>
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
          const allMetric = await LeadApplicationService.loadMetrics({});
          this.appMetrics = allMetric.data;
      }

    },
    mounted() {
      this.loadMetrics();
    }
};
</script>
