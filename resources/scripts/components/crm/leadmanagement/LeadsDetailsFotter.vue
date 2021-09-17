<template>
    <v-footer fixed  class="text-right">
        <v-col class="text-right" cols="12">
<!--            <v-btn   :disabled="!isManualChangeFlag" :color="isManualChangeFlag?'primary':''" @click="submitConnection" >Submit for Connection</v-btn>-->
            <v-btn  color="primary"   @click="submitConnection" >Submit for Connection</v-btn>
        </v-col>
    </v-footer>
</template>

<script>
export default {
    name: "LeadsDetailsFotter",
    props:['loginLoading','isManualChangeFlag'],
  methods:{
      submitConnection() {
        this.$emit('submitConnection');
      }
  }
}
</script>

<style scoped>

</style>
