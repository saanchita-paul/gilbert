<template>
    <v-row class="section-progressbar">
      <v-col cols="4" v-for="step in totalStep" :key="step">
        <span :class="{active: step <= (currentIndex + 1)}"></span>
      </v-col>
    </v-row>
</template>

<script>
export default {
  name: "ProgressBar",
    props:["totalStep","currentIndex"],
    mounted() {
      console.log(this.totalStep);
      console.log(this.currentIndex);

    }
};
</script>

<style scoped>
</style>
