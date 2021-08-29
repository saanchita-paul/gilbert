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
    }
};
</script>

<style scoped>
</style>
