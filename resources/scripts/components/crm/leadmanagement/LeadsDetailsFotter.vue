<template>
    <v-footer fixed  class="text-right">
        <v-col class="text-right" cols="12">
            <v-btn  color="#FFFFFF" @click="submitConnection" disabled>Submit for Connection</v-btn>
        </v-col>
    </v-footer>
</template>

<script>
export default {
    name: "LeadsDetailsFotter",
    props:['loginLoading'],
  methods:{
      submitConnection() {
        this.$emit('submitConnection');
      }
  }
}
</script>

<style scoped>

</style>
