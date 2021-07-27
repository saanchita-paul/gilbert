<template>
  <v-container color="white">
    <v-row>
      <v-col cols="2">
        <div class="leade-badge">
            <h3>Leads</h3>
            <div class="leade-icon">
                <v-icon color="yellow">mdi-flash</v-icon>
                <span>104</span>
            </div>
            <p class="leade-text">Total submitted leads</p>
        </div>
      </v-col>

      <v-col cols="2">
        <div class="leade-badge">
            <h3>Non-payable</h3>
            <div class="leade-icon">
                <v-icon color="red">mdi-link</v-icon>
                <span>2</span>
            </div>
            <p class="leade-text">Non Connected Leads</p>
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
            <p class="leade-text">Total submitted leads</p>
        </div>
      </v-col>

      <v-col cols="2">
        <div class="leade-badge">
            <h3>Internet</h3>
            <div class="leade-icon">
                <v-icon color="grey lighten-1">mdi-wifi</v-icon>
                <span>104</span>
            </div>
            <p class="leade-text">Total submitted leads</p>
        </div>
      </v-col>

      <v-col cols="2">
        <div class="leade-badge">
            <h3>Water</h3>
            <div class="leade-icon">
                <v-icon color="grey lighten-1">mdi-water</v-icon>
                <span>104</span>
            </div>
            <p class="leade-text">Total submitted leads</p>
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "LeadMetrics",
};
</script>

<style scoped>
</style>
