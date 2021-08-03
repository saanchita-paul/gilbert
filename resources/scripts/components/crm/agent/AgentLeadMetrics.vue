<template>
    <v-row>
      <v-col cols="4" class="pb-0">
        <p class="mb-0">Leads Data</p>
      </v-col>
      <v-col cols="8" class="pb-0">
        <p class="mb-0 ">Successful Connections</p>
      </v-col>

      <v-col cols="2">
        <div class="leade-badge">
            <h3>Applications</h3>
            <div class="leade-icon">
                <v-icon color="yellow">mdi-flash</v-icon>
                <span>104</span>
            </div>
            <p class="leade-text">Total submitted</p>
        </div>
      </v-col>

      <v-col cols="2">
        <div class="leade-badge">
            <h3>Non-payable</h3>
            <div class="leade-icon">
                <v-icon color="red">mdi-link</v-icon>
                <span>2</span>
            </div>
            <p class="leade-text">Non Connected</p>
        </div>
      </v-col>

      <v-col cols="2">
        <div class="leade-badge">
            <h3>Power</h3>
            <div class="leade-icon">
                <v-icon color="yellow">mdi-flash</v-icon>
                <span>43</span>
            </div>
            <p class="leade-text">Successfully connected</p>
        </div>
      </v-col>

      <v-col cols="2">
        <div class="leade-badge">
            <h3>Gas</h3>
            <div class="leade-icon">
                <v-icon color="red">mdi-fire</v-icon>
                <span>104</span>
            </div>
            <p class="leade-text">Successfully connected</p>
        </div>
      </v-col>

      <v-col cols="2">
        <div class="leade-badge">
            <h3>Internet</h3>
            <div class="leade-icon">
                <v-icon color="grey lighten-1">mdi-wifi</v-icon>
                <span>104</span>
            </div>
            <p class="leade-text">Successfully connected</p>
        </div>
      </v-col>

      <v-col cols="2">
        <div class="leade-badge">
            <h3>Water</h3>
            <div class="leade-icon">
                <v-icon color="grey lighten-1">mdi-water</v-icon>
                <span>104</span>
            </div>
            <p class="leade-text">Successfully connected</p>
        </div>
      </v-col>
    </v-row>
</template>

<script>
export default {
  name: "AgentLeadMetrics",
};
</script>
