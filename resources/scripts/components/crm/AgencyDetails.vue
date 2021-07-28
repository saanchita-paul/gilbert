<template>
    <v-row>
      <v-col class="section-dialogs" cols="12">
        <div class="dialogs-title">
          <p>Agency Details</p>
        </div>

        <div class="dialogs-area">
          <p class="title">What type of agency?</p>
          <v-select outlined dense label="Please select agency type"></v-select>
          <v-text-field
            label="Order ID"
            placeholder="Office name"
            outlined
            dense
          ></v-text-field>
        </div>
      </v-col>
    </v-row>
</template>

<script>
export default {
  name: "AgencyDetails",
};
</script>

<style scoped>
</style>
